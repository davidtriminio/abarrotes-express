<?php

namespace App\Filament\Resources\ReportesProblemasResource\Pages;

use App\Filament\Resources\ReportesProblemasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportesProblemas extends EditRecord
{
    protected static string $resource = ReportesProblemasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
