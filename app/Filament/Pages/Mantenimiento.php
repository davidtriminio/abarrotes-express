<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Mantenimiento extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?string $activeNavigationIcon = 'heroicon-s-circle-stack';
    protected static ?string $navigationGroup = 'Ajustes';
    protected static ?string $slug = 'copias-seguridad';
    protected static ?string $title = 'Gestión de Datos';
    protected ?string $heading = '';
    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.pages.mantenimiento';

    public static function canAccess(): bool
    {
        if(auth()->user()->hasPermissionTo('ver:copias-seguridad')){
            return true;
        }
        else{
            return false;
        }
    }
}
