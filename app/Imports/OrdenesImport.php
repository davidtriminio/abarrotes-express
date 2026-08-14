<?php

namespace App\Imports;

use App\Models\Direccion;
use App\Models\Orden;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Importa órdenes desde un Excel donde cada fila representa UN producto
 * de una orden (formato "una fila por elemento"). Varias filas se agrupan
 * en la misma orden por:
 *  - orden_id: si viene lleno, ACTUALIZA esa orden existente.
 *  - referencia_lote: si orden_id viene vacío, agrupa filas nuevas que
 *    pertenecen a la misma orden (ver App\Exports\OrdenesPlantillaExport).
 *
 * Después de Excel::import(...), revisa creadas/actualizadas/errores para
 * mostrarle un resumen al usuario.
 */
class OrdenesImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    use Importable;

    /** @var array<int> */
    public array $creadas = [];

    /** @var array<int> */
    public array $actualizadas = [];

    /** @var array<int, string> */
    public array $errores = [];

    public function collection(Collection $filas): void
    {
        $grupos = $filas->groupBy(function (Collection $fila, int $indice) {
            $ordenId = trim((string) ($fila['orden_id'] ?? ''));
            if ($ordenId !== '') {
                return 'id:' . $ordenId;
            }

            $lote = trim((string) ($fila['referencia_lote'] ?? ''));
            if ($lote !== '') {
                return 'lote:' . $lote;
            }

            return 'fila:' . $indice;
        });

        foreach ($grupos as $clave => $filasDelGrupo) {
            try {
                DB::transaction(function () use ($clave, $filasDelGrupo) {
                    if (str_starts_with($clave, 'id:')) {
                        $ordenId = $this->procesarActualizacion($filasDelGrupo);
                        $this->actualizadas[] = $ordenId;
                    } else {
                        $ordenId = $this->procesarCreacion($filasDelGrupo);
                        $this->creadas[] = $ordenId;
                    }
                });
            } catch (\Throwable $e) {
                $this->errores[] = "Grupo [{$clave}]: " . $e->getMessage();
            }
        }
    }

    protected function procesarCreacion(Collection $filas): int
    {
        $primera = $filas->first();

        $usuario = User::where('email', trim((string) ($primera['usuario_email'] ?? '')))->first();
        if (! $usuario) {
            throw new \RuntimeException("No existe un usuario con el correo '{$primera['usuario_email']}'.");
        }

        $lineas = $this->validarYNormalizarLineas($filas, esNueva: true);
        $subTotal = collect($lineas)->sum('monto_total');
        $costosEnvio = (float) ($primera['costos_envio'] ?? 0);

        $orden = new Orden();
        $orden->user_id = $usuario->id;
        $orden->metodo_pago = $this->validarEnum($primera['metodo_pago'] ?? null, ['efectivo', 'tarjeta', 'par'], 'metodo_pago');
        $orden->estado_pago = $this->validarEnum($primera['estado_pago'] ?? 'procesando', ['pagado', 'procesando', 'error'], 'estado_pago');
        $orden->estado_entrega = $this->validarEnum($primera['estado_entrega'] ?? 'nuevo', ['nuevo', 'procesado', 'enviado', 'entregado', 'cancelado'], 'estado_entrega');
        $orden->fecha_entrega = $this->parsearFecha($primera['fecha_entrega'] ?? null);
        $orden->costos_envio = $costosEnvio;
        $orden->notas = $primera['notas'] ?? null;
        $orden->sub_total = $subTotal;
        $orden->descuento_total = 0;
        $orden->total_final = $subTotal + $costosEnvio;
        $orden->save();

        $validador = Validator::make($primera->toArray(), [
            'nombres' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:20'],
            'departamento' => ['required', 'string'],
            'municipio' => ['required', 'string'],
            'ciudad' => ['required', 'string'],
        ]);
        $validador->validate();

        $direccion = new Direccion();
        $direccion->orden_id = $orden->id;
        $direccion->nombres = $primera['nombres'];
        $direccion->apellidos = $primera['apellidos'] ?? null;
        $direccion->telefono = $primera['telefono'];
        $direccion->departamento = $primera['departamento'];
        $direccion->municipio = $primera['municipio'];
        $direccion->ciudad = $primera['ciudad'];
        $direccion->direccion_completa = $primera['direccion_completa'] ?? null;
        $direccion->save();

        $orden->elementos()->createMany($lineas);

        return $orden->id;
    }

    protected function procesarActualizacion(Collection $filas): int
    {
        $primera = $filas->first();
        $ordenId = (int) $primera['orden_id'];

        /** @var Orden|null $orden */
        $orden = Orden::withTrashed()->find($ordenId);
        if (! $orden) {
            throw new \RuntimeException("No existe la orden #{$ordenId}.");
        }

        if (! empty($primera['metodo_pago'])) {
            $orden->metodo_pago = $this->validarEnum($primera['metodo_pago'], ['efectivo', 'tarjeta', 'par'], 'metodo_pago');
        }
        if (! empty($primera['estado_pago'])) {
            $orden->estado_pago = $this->validarEnum($primera['estado_pago'], ['pagado', 'procesando', 'error'], 'estado_pago');
        }
        if (! empty($primera['estado_entrega'])) {
            $orden->estado_entrega = $this->validarEnum($primera['estado_entrega'], ['nuevo', 'procesado', 'enviado', 'entregado', 'cancelado'], 'estado_entrega');
        }
        if (! empty($primera['fecha_entrega'])) {
            $orden->fecha_entrega = $this->parsearFecha($primera['fecha_entrega']);
        }
        if (isset($primera['costos_envio']) && $primera['costos_envio'] !== '') {
            $orden->costos_envio = (float) $primera['costos_envio'];
        }
        if (! empty($primera['notas'])) {
            $orden->notas = $primera['notas'];
        }

        if (! empty($primera['nombres']) || ! empty($primera['telefono'])) {
            Direccion::updateOrCreate(
                ['orden_id' => $orden->id],
                array_filter([
                    'nombres' => $primera['nombres'] ?? null,
                    'apellidos' => $primera['apellidos'] ?? null,
                    'telefono' => $primera['telefono'] ?? null,
                    'departamento' => $primera['departamento'] ?? null,
                    'municipio' => $primera['municipio'] ?? null,
                    'ciudad' => $primera['ciudad'] ?? null,
                    'direccion_completa' => $primera['direccion_completa'] ?? null,
                ], fn ($valor) => $valor !== null && $valor !== '')
            );
        }

        $filasConProducto = $filas->filter(fn (Collection $fila) => ! empty($fila['producto_id']));
        if ($filasConProducto->isNotEmpty()) {
            $lineas = $this->validarYNormalizarLineas($filasConProducto, esNueva: false);
            $orden->elementos()->delete();
            $orden->elementos()->createMany($lineas);
            $orden->sub_total = collect($lineas)->sum('monto_total');
            $orden->total_final = $orden->sub_total + (float) $orden->costos_envio - (float) $orden->descuento_total;
        }

        $orden->save();

        return $orden->id;
    }

    /**
     * @return array<int, array{producto_id:int, cantidad:int, monto_unitario:float, monto_total:float}>
     */
    protected function validarYNormalizarLineas(Collection $filas, bool $esNueva): array
    {
        return $filas->map(function (Collection $fila) use ($esNueva) {
            $reglas = [
                'producto_id' => ['required', 'integer', 'exists:productos,id'],
                'cantidad' => ['required', 'integer', 'min:1'],
                'monto_unitario' => ['required', 'numeric', 'min:0'],
            ];

            $validador = Validator::make($fila->only(['producto_id', 'cantidad', 'monto_unitario'])->toArray(), $reglas);
            $validador->validate();

            $cantidad = (int) $fila['cantidad'];
            $montoUnitario = (float) $fila['monto_unitario'];

            return [
                'producto_id' => (int) $fila['producto_id'],
                'cantidad' => $cantidad,
                'monto_unitario' => $montoUnitario,
                'monto_total' => round($cantidad * $montoUnitario, 2),
            ];
        })->all();
    }

    protected function validarEnum(?string $valor, array $permitidos, string $campo): string
    {
        $valor = trim((string) $valor);
        if (! in_array($valor, $permitidos, true)) {
            throw new \RuntimeException("Valor inválido '{$valor}' para {$campo}. Debe ser uno de: " . implode(', ', $permitidos) . '.');
        }

        return $valor;
    }

    protected function parsearFecha(mixed $valor): ?\Carbon\Carbon
    {
        if (empty($valor)) {
            return null;
        }

        if (is_numeric($valor)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($valor)
                ? \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($valor))
                : null;
        }

        return \Carbon\Carbon::parse((string) $valor);
    }
}
