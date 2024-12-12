<?php

namespace App\Filament\Resources\QuejasySugerenciasResource\Pages;

use App\Filament\Resources\QuejasySugerenciasResource;
use App\Traits\PermisoVer;
use Filament\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\ViewRecord;

class ViewQuejasySugerencias extends ViewRecord
{
    protected static string $resource = QuejasySugerenciasResource::class;
    protected ?string $heading = '';
    protected static string $view = 'filament.resources.custom.ver-registro';
    protected static ?string $title = 'Detalles de la Queja/Sugerencia';
    use PermisoVer;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('titulo')
                        ->label('Título del Problema')
                        ->disabled(true)
                        ->columnSpan('full'),

                    Textarea::make('descripcion')
                        ->label('Descripción')
                        ->disabled(true)
                        ->rows(5)
                        ->columnSpan('full'),

                    TextInput::make('tipo')
                        ->label('Tipo')
                        ->disabled(true)
                        ->columnSpan('full'),
                ])->columnSpan('full')
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Regresar')
                ->label('Regresar')
                ->url($this->previousUrl ?? $this->getResource()::getUrl('index'))
                ->button()
                ->icon('heroicon-o-chevron-left')
                ->color('gray'),
            Actions\EditAction::make('Editar')
                ->visible(function () {
                    $slug = self::getResource()::getSlug();
                    $usuario = auth()->user();
                    if ($usuario->hasPermissionTo('editar:' . $slug)) {
                        return true;
                    }
                    return false;
                })
                ->icon('heroicon-o-pencil-square'),
            Actions\DeleteAction::make('Borrar')
                ->visible(function () {
                    $slug = self::getResource()::getSlug();
                    $usuario = auth()->user();
                    if ($usuario->hasPermissionTo('borrar:' . $slug)) {
                        return true;
                    }
                    return false;
                })
                ->icon('heroicon-o-trash'),
        ];
    }
}
