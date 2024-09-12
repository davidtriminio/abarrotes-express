<?php

namespace App\Filament\Resources\UsuariosResource\Pages;

use App\Filament\Resources\UsuarioResource;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Table;

class ViewUsuario extends ViewRecord
{
    protected static string $resource = UsuarioResource::class;
    protected static ?string $title = 'Detalles de Usuario';
    protected ?string $heading = 'Detalles de Usuario';

    protected function getHeaderActions(): array
    {
        return [
            /*Botón para regresar a la lista de usuarios*/
            Actions\Action::make('Regresar')
                ->url(UsuarioResource::getUrl())
                ->button()
                ->icon('heroicon-o-chevron-left')
                ->color('gray'),

            Actions\EditAction::make()
                ->visible(function () {
                    $usuarioActual = auth()->user();
                    $usuarioSeleccionado = $this->record;
                    if ($usuarioActual->hasPermissionTo('actualizar:usuario')) {
                        if ($usuarioActual->hasRole('SuperAdmin')) {
                            if ($usuarioSeleccionado->hasRole('SuperAdmin') === true) {
                                return false;
                            } else
                                return true;
                        } else {
                            if ($usuarioActual->id === $usuarioSeleccionado->id || $usuarioSeleccionado->hasRole('Administrador') || $usuarioSeleccionado->hasRole('SuperAdmin')) {
                                return false;
                            }
                            return true;
                        }
                    }
                    return false;
                })
                ->icon('heroicon-o-pencil-square'),

            Actions\DeleteAction::make()
                ->visible(function () {
                    $usuarioActual = auth()->user();
                    $usuarioSeleccionado = $this->record;
                    if (auth()->user()->hasRole('SuperAdmin')) {
                        if ($usuarioSeleccionado->hasRole('SuperAdmin') === true) {
                            return false;
                        } else
                            return true;
                    } else {
                        if ($usuarioActual->id === $usuarioSeleccionado->id || $usuarioSeleccionado->hasRole('Administrador') || $usuarioSeleccionado->hasRole('SuperAdmin')) {
                            return false;
                        }
                        return true;
                    }
                })
                ->icon('heroicon-o-trash'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Nombre de Usuario')
                    ->maxLength(100)
                    ->regex('/^[A-Za-zÀ-ÿñÑ ]+$/')
                    ->validationMessages([
                        'required' => 'Debe introducir un nombre de usuario.',
                        'max' => 'El nombre no debe contener más de 100 carácteres.',
                        'regex' => 'El nombre de usuario no debe contener símbolos',
                    ]),

                TextInput::make('email')
                    ->required()
                    ->rules(['regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/'])->email()
                    ->unique(ignoreRecord: true)
                    ->maxLength(100)->label('Correo Electrónico')
                    ->validationMessages([
                        'required' => 'Debe introducir un correo electrónico.',
                        'email' => 'Debe introducir un correo electrónico válido.',
                        'unique' => 'El correo ingresado se encuentra en uso, introduzca uno nuevo.',
                        'max' => 'El correo debe contener menos de 100 carácteres.',
                        'regex' => 'Debe introducir un correo electrónico válido.'
                    ]),

                DateTimePicker::make('email_verified_at')
                    ->label('Fecha de verificación de Correo'),

                TextInput::make('password')
                    ->label('Contraseña')
                    ->revealable()
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->maxLength(18)
                    ->hint(''),


                Placeholder::make('created_at')
                    ->label('Fecha de creación')
                    ->content(fn(?User $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Ultima modificación')
                    ->content(fn(?User $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public function handle(): void
    {
        redirect(ListUsuarios::getUrl());
    }
}
