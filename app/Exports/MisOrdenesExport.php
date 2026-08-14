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
 * Exportación de resumen para un cliente: solo sus propias órdenes,
 * a nivel de cabecera (sin datos de otros usuarios ni de envío).
 */
class MisOrdenesExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    public function __construct(protected int $userId)
    {
    }

    public function query(): Builder
    {
        return Orden::query()
            ->where('user_id', $this->userId)
            ->orderByDesc('created_at');
    }

    public function headings(): array
    {
        return [
            'orden_id',
            'fecha',
            'total_final',
            'metodo_pago',
            'estado_pago',
            'estado_entrega',
            'fecha_entrega',
        ];
    }

    public function map($orden): array
    {
        return [
            $orden->id,
            $orden->created_at?->format('Y-m-d H:i'),
            $orden->total_final,
            $orden->metodo_pago,
            $orden->estado_pago,
            $orden->estado_entrega,
            $orden->fecha_entrega?->format('Y-m-d H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
