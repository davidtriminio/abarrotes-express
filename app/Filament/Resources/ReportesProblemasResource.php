<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportesProblemasResource\Pages;
use App\Filament\Resources\ReportesProblemasResource\RelationManagers;
use App\Models\ReporteProblema;
use App\Models\ReportesProblemas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReportesProblemasResource extends Resource
{
    protected static ?string $model = ReporteProblema::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $activeNavigationIcon = 'heroicon-s-exclamation-triangle';
    protected static ?string $slug = 'reportes_problemas';
    protected static ?string $modelLabel = 'Reporte de Problemas';
    protected static ?string $navigationLabel = 'Reporte de Problemas';
    protected static ?string $navigationGroup = 'Soporte';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([


                TextColumn::make('titulo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('seccion')
                    ->searchable()
                    ->sortable(),


            ])
            ->selectable()
            ->paginated([10, 25, 50, 100,])
            ->filters([

            ])
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReportesProblemas::route('/'),
            'view' => Pages\ViewReporteProblema::route('/{record}/view')
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
