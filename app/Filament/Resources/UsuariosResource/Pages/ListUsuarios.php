<?php

namespace App\Filament\Resources\UsuariosResource\Pages;

use App\Filament\Resources\UsuarioResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
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

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Crear Usuario')
                ->icon('heroicon-o-plus-circle'),
        ];
    }


    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->alignCenter()
                    ->label('Nombre de Usuario'),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->alignCenter()
                    ->label('Correo Electrónico'),

                TextColumn::make('roles.name') ->sortable()->searchable()
                ->alignCenter(),

                TextColumn::make('email_verified_at')
                    ->label('Verificación de Correo')
                    ->alignCenter()
                    ->date(),
                TextColumn::make('deleted_at')
                ->date()
                ->label('Fecha de borrado')

            ])
            ->paginated([10, 25, 50, 100,])
            ->actions([
                ViewAction::make()
                    ->hiddenLabel(),
                RestoreAction::make()
                    ->label('Restaurar')
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
