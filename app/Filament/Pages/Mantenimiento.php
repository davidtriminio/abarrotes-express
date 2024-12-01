<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Mantenimiento extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?string $activeNavigationIcon = 'heroicon-s-circle-stack';
    protected static ?string $navigationGroup = 'Ajustes';
    protected static ?string $slug = 'datos';
    protected static ?string $title = 'Gestión de Datos';
    protected ?string $heading = '';
    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.pages.mantenimiento';
}
