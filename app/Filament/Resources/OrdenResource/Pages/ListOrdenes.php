<?php

namespace App\Filament\Resources\OrdenResource\Pages;

use App\Exports\OrdenesDetalladoExport;
use App\Exports\OrdenesPlantillaExport;
use App\Filament\Resources\OrdenResource;
use App\Imports\OrdenesImport;
use App\Models\Orden;
use App\Traits\PermisoVer;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkAction;
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
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListOrdenes extends ListRecords
{
    protected static string $resource = OrdenResource::class;
    protected static string $view = 'filament.resources.custom.lista_personalizada';
    protected ?string $heading = '';
    use PermisoVer;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('plantilla')
                ->label('Plantilla de importación')
                ->icon('heroicon-o-document-arrow-down')
                ->color('gray')
                ->visible(fn () => auth()->user()->hasPermissionTo('crear:' . self::getResource()::getSlug()))
                ->action(fn () => Excel::download(new OrdenesPlantillaExport(), 'plantilla-ordenes.xlsx')),

            Action::make('importar')
                ->label('Importar')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->visible(fn () => auth()->user()->hasPermissionTo('crear:' . self::getResource()::getSlug()))
                ->form([
                    FileUpload::make('archivo')
                        ->label('Archivo Excel (.xlsx)')
                        ->disk('local')
                        ->directory('importaciones/ordenes')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                            'text/csv',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $import = new OrdenesImport();
                    Excel::import($import, Storage::disk('local')->path($data['archivo']));
                    Storage::disk('local')->delete($data['archivo']);

                    $resumen = sprintf(
                        '%d creada(s), %d actualizada(s), %d con error(es).',
                        count($import->creadas),
                        count($import->actualizadas),
                        count($import->errores)
                    );

                    if (count($import->errores) > 0) {
                        Notification::make()
                            ->title('Importación finalizada con errores')
                            ->body($resumen . "\n" . implode("\n", array_slice($import->errores, 0, 5)))
                            ->warning()
                            ->persistent()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Importación completada')
                            ->body($resumen)
                            ->success()
                            ->send();
                    }
                }),

            Action::make('exportar')
                ->label('Exportar todo')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => Excel::download(new OrdenesDetalladoExport(), 'ordenes-' . now()->format('Y-m-d') . '.xlsx')),

            CreateAction::make('Crear')
                ->label('Crear Orden')
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

    public function getTabs(): array
    {
        $orden = Orden::query();
        if ($orden->count()) {
            $tabs = [];
            if (Orden::query()->where('estado_entrega', 'nuevo')->exists()) {
                $tabs['nuevo'] = Tab::make('Nuevo')->query(fn($query) => $query->where('estado_entrega', 'nuevo'))
                    ->icon('heroicon-o-sparkles');
            }
            if (Orden::query()->where('estado_entrega', 'procesado')->exists()) {
                $tabs['procesado'] = Tab::make('En Proceso')->query(fn($query) => $query->where('estado_entrega', 'procesado'))
                    ->icon('heroicon-o-arrow-path');
            }
            if (Orden::query()->where('estado_entrega', 'enviado')->exists()) {
                $tabs['enviado'] = Tab::make('Enviado')->query(fn($query) => $query->where('estado_entrega', 'enviado'))
                    ->icon('heroicon-o-truck');
            }
            if (Orden::query()->where('estado_entrega', 'entregado')->exists()) {
                $tabs['entregado'] = Tab::make('Entregado')->query(fn($query) => $query->where('estado_entrega', 'entregado'))
                    ->icon('heroicon-o-archive-box');
            }
            if (Orden::query()->where('estado_entrega', 'cancelado')->exists()) {
                $tabs['cancelado'] = Tab::make('Cancelado')
                    ->query(fn($query) => $query->where('estado_entrega', 'cancelado'))
                    ->icon('heroicon-o-x-circle');
            }
            return [null => Tab::make('Todo')] + $tabs;
        }
        return [];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Comprador')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total_final')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('metodo_pago')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'par' => 'heroicon-o-banknotes',
                        'efectivo' => 'heroicon-o-currency-dollar',
                        'tarjeta' => 'heroicon-o-credit-card',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'par' => 'primary',
                        'efectivo' => 'success',
                        'tarjeta' => 'warning',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'par' => 'Pago al recibir',
                        'efectivo' => 'Efectivo',
                        'tarjeta' => 'Tarjeta',
                    })
                    ->sortable()
                    ->searchable(),

                TextColumn::make('estado_pago')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'pagado' => 'heroicon-m-check-circle',
                        'procesando' => 'heroicon-m-arrow-path',
                        'error' => 'heroicon-m-exclamation-circle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pagado' => 'success',
                        'procesando' => 'warning',
                        'error' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pagado' => 'Pagado',
                        'procesando' => 'Procesando',
                        'error' => 'Error',
                    })
                    ->sortable()
                    ->searchable(),

                TextColumn::make('estado_entrega')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'nuevo' => 'heroicon-m-sparkles',
                        'procesado' => 'heroicon-m-arrow-path',
                        'enviado' => 'heroicon-m-truck',
                        'entregado' => 'heroicon-m-archive-box',
                        'cancelado' => 'heroicon-m-x-circle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'nuevo' => 'primary',
                        'procesado' => 'warning',
                        'enviado' => 'success',
                        'entregado' => 'success',
                        'cancelado' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'nuevo' => 'Nuevo',
                        'procesado' => 'En Proceso',
                        'enviado' => 'Enviado',
                        'entregado' => 'Entregado',
                        'cancelado' => 'Cancelado',
                    })
                    ->sortable()
                    ->searchable(),
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
                    BulkAction::make('exportarSeleccionadas')
                        ->label('Exportar seleccionadas')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn ($records) => Excel::download(
                            new OrdenesDetalladoExport($records->pluck('id')->all()),
                            'ordenes-' . now()->format('Y-m-d') . '.xlsx'
                        ))
                        ->deselectRecordsAfterCompletion(),
                ])
            ]);
    }
}
