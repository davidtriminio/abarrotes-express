<?php

namespace App\Filament\Resources\ReportesProblemasResource\Pages;

use App\Filament\Resources\ReportesProblemasResource;
use Filament\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Pages\ViewRecord;

class ViewReporteProblema extends ViewRecord
{
    protected static string $resource = ReportesProblemasResource::class;

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

                    TextInput::make('seccion')
                        ->label('Sección')
                        ->disabled(true)
                        ->columnSpan('full'),
                ])->columnSpan('full'),
            ]);

    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Regresar')
                ->label('Volver a la Lista')
                ->url($this->previousUrl ?? $this->getResource()::getUrl('index'))
                ->button()
                ->icon('heroicon-o-chevron-left')
                ->color('gray'),
        ];
    }
}
