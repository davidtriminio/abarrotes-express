<?php

namespace App\Exports;

use App\Models\Orden;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Exportación detallada para el panel de administración: una fila por
 * cada producto (elemento) dentro de una orden. Las filas exportadas
 * (que siempre traen orden_id) pueden editarse y volver a subirse con
 * la acción "Importar" para ACTUALIZAR esas mismas órdenes.
 */
class OrdenesDetalladoExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    /** @param array<int>|null $ordenIds Limita la exportación a estos IDs (usado por la acción masiva) */
    public function __construct(protected ?array $ordenIds = null)
    {
    }

    public function query(): Builder
    {
        $query = Orden::query()
            ->with(['user', 'direccion', 'elementos.producto'])
            ->orderBy('id');

        if ($this->ordenIds !== null) {
            $query->whereIn('id', $this->ordenIds);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'orden_id',
            'usuario_email',
            'metodo_pago',
            'estado_pago',
            'estado_entrega',
            'fecha_entrega',
            'costos_envio',
            'notas',
            'nombres',
            'apellidos',
            'telefono',
            'departamento',
            'municipio',
            'ciudad',
            'direccion_completa',
            'producto_id',
            'producto_nombre',
            'cantidad',
            'monto_unitario',
            'monto_total',
            'sub_total_orden',
            'descuento_total_orden',
            'total_final_orden',
            'creado_en',
        ];
    }

    /**
     * @return array<int, array<int, array<string, mixed>>>
     */
    public function map($orden): array
    {
        $direccion = $orden->direccion;
        $filaBase = [
            $orden->id,
            $orden->user?->email,
            $orden->metodo_pago,
            $orden->estado_pago,
            $orden->estado_entrega,
            $orden->fecha_entrega?->format('Y-m-d H:i'),
            $orden->costos_envio,
            $orden->notas,
            $direccion?->nombres,
            $direccion?->apellidos,
            $direccion?->telefono,
            $direccion?->departamento,
            $direccion?->municipio,
            $direccion?->ciudad,
            $direccion?->direccion_completa,
        ];

        $filaTotales = [
            $orden->sub_total,
            $orden->descuento_total,
            $orden->total_final,
            $orden->created_at?->format('Y-m-d H:i'),
        ];

        if ($orden->elementos->isEmpty()) {
            return [array_merge($filaBase, [null, null, null, null, null], $filaTotales)];
        }

        return $orden->elementos->map(function ($elemento) use ($filaBase, $filaTotales) {
            return array_merge($filaBase, [
                $elemento->producto_id,
                $elemento->producto?->nombre,
                $elemento->cantidad,
                $elemento->monto_unitario,
                $elemento->monto_total,
            ], $filaTotales);
        })->all();
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
