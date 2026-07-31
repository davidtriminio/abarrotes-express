<?php

namespace App\Filament\Resources\CategoriaResource\Pages;

use App\Actions\CustomRestoreBulkAction;
use App\Filament\Resources\CategoriaResource;
use App\Traits\PermisoVer;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Testing\Constraints\SoftDeletedInDatabase;

class ListCategorias extends ListRecords
{
    protected static string $resource = CategoriaResource::class;
    protected static string $view = 'filament.resources.custom.lista_personalizada';
    protected ?string $heading = '';
    use PermisoVer;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('Crear')
                ->label('Crear Categoría')
                ->icon('heroicon-o-plus-circle')
                ->visible(function () {
                    $slug = self::getResource()::getSlug();
                    $usuario = auth()->user();
                    if ($usuario->hasPermissionTo('crear:' . $slug)) {
                        return true;
                    }
                    return false;
                }),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->label('Nombre'),
                IconColumn::make('disponible')->label('Disponible')
                    ->boolean()
                ->alignCenter(),
            ])
            ->paginated([10, 25, 50, 100,])
            ->actions([
                ViewAction::make()
                    ->hiddenLabel(),
                RestoreAction::make()
                ->label('Restaurar')
            ])
            ->filters([
                TrashedFilter::make()
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ])
            ]);
    }

    public static function filtro()
    {

    }
}
