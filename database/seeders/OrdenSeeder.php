<?php

namespace Database\Seeders;

use App\Models\Direccion;
use App\Models\ElementoOrden;
use App\Models\Orden;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrdenSeeder extends Seeder
{
    // Órdenes antiguas (> 15 días): el ciclo de vida ya terminó, casi todas entregadas.
    private const TOTAL_HISTORICAS = 150;

    // Órdenes de los últimos 14 días: aquí vive el pipeline activo (nuevo/procesado/enviado).
    private const TOTAL_RECIENTES = 50;

    // Se fuerzan algunas entregas exactamente hoy/ayer para que el stat de
    // "Ganancias Diarias" (que compara hoy vs. ayer) tenga datos con los que comparar.
    private const ENTREGAS_POR_DIA_RECIENTE = 7;

    // Con solo 50 órdenes recientes repartidas en 14 días, dejar nuevo/procesado/
    // enviado al azar puede dar 0 en alguno (como pasó con "nuevo"). Se garantiza
    // un mínimo por etapa para que EstadisticasOrdenes y UltimasOrdenes nunca
    // aparezcan vacíos.
    private const MINIMO_POR_ETAPA_PIPELINE = 8;

    private const UBICACIONES = [
        ['departamento' => 'Francisco Morazán', 'municipio' => 'Distrito Central', 'ciudad' => 'Tegucigalpa'],
        ['departamento' => 'Cortés', 'municipio' => 'San Pedro Sula', 'ciudad' => 'San Pedro Sula'],
        ['departamento' => 'El Paraíso', 'municipio' => 'Danlí', 'ciudad' => 'Danlí'],
        ['departamento' => 'Atlántida', 'municipio' => 'La Ceiba', 'ciudad' => 'La Ceiba'],
        ['departamento' => 'Comayagua', 'municipio' => 'Comayagua', 'ciudad' => 'Comayagua'],
        ['departamento' => 'Choluteca', 'municipio' => 'Choluteca', 'ciudad' => 'Choluteca'],
    ];

    public function run(): void
    {
        $clientes = User::role('Cliente')->get(['id', 'name']);
        $productos = Producto::all();

        if ($clientes->isEmpty() || $productos->isEmpty()) {
            $this->command?->warn('OrdenSeeder: se necesitan usuarios con rol "Cliente" y productos antes de generar órdenes.');

            return;
        }

        DB::transaction(function () use ($clientes, $productos) {
            // 1) Historial resuelto: mucho volumen viejo, casi todo entregado/cancelado.
            for ($i = 0; $i < self::TOTAL_HISTORICAS; $i++) {
                $creadaEn = Carbon::instance(fake()->dateTimeBetween('-9 months', '-15 days'));
                $estadoEntrega = fake()->randomElement(['entregado', 'entregado', 'entregado', 'entregado', 'entregado', 'entregado', 'entregado', 'entregado', 'entregado', 'cancelado']);
                $fechaEntrega = $estadoEntrega === 'entregado'
                    ? Carbon::instance(fake()->dateTimeBetween($creadaEn, (clone $creadaEn)->addDays(6)))
                    : null;
                $estadoPago = $estadoEntrega === 'entregado' ? 'pagado' : fake()->randomElement(['error', 'procesando']);

                $this->crearOrden($clientes, $productos, $creadaEn, $estadoEntrega, $fechaEntrega, $estadoPago);
            }

            // 2) Pipeline activo: órdenes recientes en distintas etapas del ciclo de vida.
            for ($i = 0; $i < self::TOTAL_RECIENTES; $i++) {
                $creadaEn = Carbon::instance(fake()->dateTimeBetween('-14 days', 'now'));
                $diasTranscurridos = $creadaEn->diffInDays(now());

                $estadoEntrega = match (true) {
                    $diasTranscurridos < 1 => fake()->randomElement(['nuevo', 'nuevo', 'nuevo', 'procesado']),
                    $diasTranscurridos < 3 => fake()->randomElement(['nuevo', 'procesado', 'procesado', 'enviado']),
                    $diasTranscurridos < 6 => fake()->randomElement(['procesado', 'enviado', 'enviado', 'entregado']),
                    default => fake()->randomElement(['enviado', 'entregado', 'entregado', 'entregado', 'cancelado']),
                };

                [$fechaEntrega, $estadoPago] = match ($estadoEntrega) {
                    'entregado' => [Carbon::instance(fake()->dateTimeBetween($creadaEn, 'now')), 'pagado'],
                    'cancelado' => [null, fake()->randomElement(['error', 'procesando'])],
                    default => [null, fake()->randomElement(['pagado', 'pagado', 'procesando'])],
                };

                $this->crearOrden($clientes, $productos, $creadaEn, $estadoEntrega, $fechaEntrega, $estadoPago);
            }

            // 3) Entregas garantizadas hoy y ayer, para que el stat diario tenga
            // una base > 0 y un porcentaje de cambio real que mostrar.
            foreach ([now(), now()->subDay()] as $diaEntrega) {
                for ($i = 0; $i < self::ENTREGAS_POR_DIA_RECIENTE; $i++) {
                    $creadaEn = (clone $diaEntrega)->subDays(fake()->numberBetween(1, 4));

                    $this->crearOrden($clientes, $productos, $creadaEn, 'entregado', $diaEntrega->copy(), 'pagado');
                }
            }

            // 4) Mínimo garantizado por etapa activa del pipeline (independiente del azar).
            $etapasGarantizadas = [
                'nuevo' => [0, 20],       // horas atrás
                'procesado' => [24, 48],
                'enviado' => [72, 120],
            ];

            foreach ($etapasGarantizadas as $estadoEntrega => [$minHoras, $maxHoras]) {
                for ($i = 0; $i < self::MINIMO_POR_ETAPA_PIPELINE; $i++) {
                    $creadaEn = now()->subHours(fake()->numberBetween($minHoras, $maxHoras));
                    $estadoPago = fake()->randomElement(['pagado', 'pagado', 'procesando']);

                    $this->crearOrden($clientes, $productos, $creadaEn, $estadoEntrega, null, $estadoPago);
                }
            }
        });
    }

    private function crearOrden(
        Collection $clientes,
        Collection $productos,
        Carbon $creadaEn,
        string $estadoEntrega,
        ?Carbon $fechaEntrega,
        string $estadoPago,
    ): void {
        $cliente = $clientes->random();

        $lineas = $productos->random(min(fake()->numberBetween(1, 4), $productos->count()));

        $subTotal = 0;
        $elementos = [];

        foreach ($lineas as $producto) {
            $cantidad = fake()->numberBetween(1, 4);
            $montoUnitario = ($producto->en_oferta && $producto->porcentaje_oferta > 0)
                ? round($producto->precio * (1 - $producto->porcentaje_oferta / 100), 2)
                : (float) $producto->precio;
            $montoTotal = round($montoUnitario * $cantidad, 2);
            $subTotal += $montoTotal;

            $elementos[] = [
                'producto_id' => $producto->id,
                'cantidad' => $cantidad,
                'monto_unitario' => $montoUnitario,
                'monto_total' => $montoTotal,
            ];
        }

        $costosEnvio = fake()->randomElement([0, 50, 75, 90]);
        $descuentoTotal = fake()->boolean(20) ? round($subTotal * 0.05, 2) : 0;
        $totalFinal = round($subTotal - $descuentoTotal + $costosEnvio, 2);

        $orden = new Orden([
            'user_id' => $cliente->id,
            'sub_total' => $subTotal,
            'descuento_total' => $descuentoTotal,
            'total_final' => $totalFinal,
            'metodo_pago' => fake()->randomElement(['par', 'efectivo', 'tarjeta']),
            'estado_pago' => $estadoPago,
            'estado_entrega' => $estadoEntrega,
            'fecha_entrega' => $fechaEntrega,
            'costos_envio' => $costosEnvio,
            'notas' => fake()->optional(0.3)->sentence(),
        ]);
        // created_at/updated_at no son mass-assignable; se fijan aparte para
        // poder repartir las órdenes en el tiempo y alimentar los charts.
        $orden->created_at = $creadaEn;
        $orden->updated_at = $fechaEntrega ?? $creadaEn;
        $orden->save();

        foreach ($elementos as $elemento) {
            $elementoOrden = new ElementoOrden($elemento);
            $elementoOrden->orden_id = $orden->id;
            $elementoOrden->created_at = $creadaEn;
            $elementoOrden->updated_at = $creadaEn;
            $elementoOrden->save();
        }

        $ubicacion = fake()->randomElement(self::UBICACIONES);
        $nombreCompleto = explode(' ', $cliente->name, 2);

        $direccion = new Direccion([
            'nombres' => $nombreCompleto[0] ?? $cliente->name,
            'apellidos' => $nombreCompleto[1] ?? '',
            'telefono' => fake()->numerify('####-####'),
            'departamento' => $ubicacion['departamento'],
            'municipio' => $ubicacion['municipio'],
            'ciudad' => $ubicacion['ciudad'],
            'direccion_completa' => fake()->streetAddress(),
        ]);
        $direccion->orden_id = $orden->id;
        $direccion->created_at = $creadaEn;
        $direccion->updated_at = $creadaEn;
        $direccion->save();
    }
}
