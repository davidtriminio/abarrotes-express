<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Plantilla en blanco (con una fila de ejemplo) que un administrador
 * puede descargar, llenar y volver a subir con la acción "Importar".
 * Las columnas coinciden exactamente con OrdenesImport.
 */
class OrdenesPlantillaExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            // Fila de ejemplo para una orden NUEVA con dos productos.
            // Ambas filas comparten el mismo referencia_lote y dejan orden_id vacío.
            [
                null, 'LOTE-001', 'cliente@correo.com', 'efectivo', 'procesando', 'nuevo', null, '0',
                'Pedido de ejemplo', 'Juan', 'Pérez', '99999999', 'francisco-morazan', 'distrito-central',
                'Tegucigalpa', 'Barrio El Centro, casa #12', 1, 'Producto de ejemplo A', 2, '50.00',
            ],
            [
                null, 'LOTE-001', 'cliente@correo.com', 'efectivo', 'procesando', 'nuevo', null, '0',
                'Pedido de ejemplo', 'Juan', 'Pérez', '99999999', 'francisco-morazan', 'distrito-central',
                'Tegucigalpa', 'Barrio El Centro, casa #12', 2, 'Producto de ejemplo B', 1, '120.00',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'orden_id',
            'referencia_lote',
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
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
