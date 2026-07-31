<?php

namespace App\Filament\Exports;

use App\Models\Log;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Spatie\Activitylog\Models\Activity;

class ActivityExporter extends Exporter
{
    protected static ?string $model = Activity::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('event')
            ->label('Evento'),
            ExportColumn::make('description')
            ->label('Descripción'),
            ExportColumn::make('subject_type')
                ->label('Tipo de asunto')
                ->getStateUsing(function (Activity $activity) {
                    if ($activity->subject) {
                        $subjectName = $activity->subject->subject_name ?? 'Sin nombre';
                        return $subjectName . ' # ' . $activity->subject_id;
                    }
                    return 'Sin Asunto';
                }),
            ExportColumn::make('causer.name')
            ->label('Realizado por'),
            ExportColumn::make('created_at')
            ->label('Fecha de registro'),
        ];
    }
    public static function cleanUpLogs(): void
    {
        Activity::truncate();
    }
    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Se han exportado ' . number_format($export->successful_rows) . ' ' . str('filas')->plural($export->successful_rows) . ' completamente.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('filas')->plural($failedRowsCount) . ' Error al exportar.';
        }
        return $body;
    }
}
