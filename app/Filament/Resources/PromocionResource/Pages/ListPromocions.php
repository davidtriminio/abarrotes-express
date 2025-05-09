<?php

namespace App\Filament\Resources\PromocionResource\Pages;

use App\Filament\Resources\PromocionResource;
use App\Traits\PermisoVer;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ListPromocions extends ListRecords
{
    protected static string $resource = PromocionResource::class;
    protected static string $view = 'filament.resources.custom.lista_personalizada';
    protected ?string $heading = '';
    protected static ?string $title = 'Promociones';
    protected static ?string $slug = 'promociones';
    use PermisoVer;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('Crear')
                ->visible(function () {
                    $slug = self::getResource()::getSlug();
                    $usuario = auth()->user();
                    if ($usuario->hasPermissionTo('crear:' . $slug)) {
                        return true;
                    }
                    return false;
                })
                ->label('Crear Promocion')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    public  function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('producto.nombre')->label('productos'),
                TextColumn::make('promocion')->numeric()
                ->suffix(' %')->label('promocion'),
            ])
            ->paginated([10, 25, 50, 100,])
            ->actions([
                ViewAction::make()
                    ->hiddenLabel(),
                RestoreAction::make()
                    ->label('Restaurar')
            ])

            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ])
            ]);
    }

    public  function tables(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('producto.nombre')->label('producto'),
                TextColumn::make('promocion')->label('promocion'),
            ])
            ->paginated([10, 25, 50, 100,])
            ->actions([
                ViewAction::make()
                    ->hiddenLabel(),
                RestoreAction::make()
                    ->label('Restaurar')
            ])

            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ])
            ]);
    }
}
