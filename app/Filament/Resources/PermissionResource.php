<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $slug = 'permisos';

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $activeNavigationIcon = 'heroicon-s-key';
    protected static ?string $pluralModelLabel = 'Permisos';
    protected static ?string $navigationGroup = 'Usuarios';
    protected static ?int $navigationSort = 4;
    protected static ?string $modelLabel = 'Permiso';
    public static function canAccess(): bool
    {
        $slug = self::getSlug();
        $usuario = auth()->user();
        if ($usuario->hasPermissionTo('ver:' . $slug)) {
            return true;
        } else {
            return false;
        }
    }
    protected function getHeaderActions(): array
    {
        return [
            Action::make('Regresar')
                ->url($this->previousUrl ?? $this->getResource()::getUrl('index'))
                ->button()
                ->icon('heroicon-o-chevron-left')
                ->color('gray'),
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    TextInput::make('Nombre del permiso')
                        ->required()
                        ->unique(),
                    Placeholder::make('created_at')
                        ->label('Fecha de creación')
                        ->content(fn(?Permission $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                    Placeholder::make('updated_at')
                        ->label('Fecha de modificación')
                        ->content(fn(?Permission $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                ])->columns(3)
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
