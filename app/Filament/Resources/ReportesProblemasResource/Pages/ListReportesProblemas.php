<?php

namespace App\Filament\Resources\ReportesProblemasResource\Pages;

use App\Filament\Resources\ReportesProblemasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ListReportesProblemas extends ListRecords
{
    protected static string $resource = ReportesProblemasResource::class;


}
