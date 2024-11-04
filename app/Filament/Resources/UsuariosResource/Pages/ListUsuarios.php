<?php

namespace App\Filament\Resources\UsuariosResource\Pages;

use App\Filament\Resources\UsuarioResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ListUsuarios extends ListRecords
{
    protected static string $resource = UsuarioResource::class;
    protected ?string $heading = '';
    protected static string $view = 'filament.resources.custom.lista_personalizada';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make('Crear')
                ->label('Crear Usuario')
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
                TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->alignLeft()
                    ->label('Nombre de Usuario'),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label('Correo Electrónico')
                    ->alignLeft(),

                TextColumn::make('roles.name') ->sortable()->searchable()
                    ->alignLeft()
                    ->label('Rol'),
            ])
            ->paginated([10, 25, 50, 100,])
            ->actions([
                RestoreAction::make()
                    ->label('Restaurar'),
                ViewAction::make()
                    ->hiddenLabel(),
            ])
            ->selectable(false)
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
}
