<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuejasySugerenciasResource\Pages;
use App\Filament\Resources\QuejasySugerenciasResource\RelationManagers;
use App\Models\QuejaSugerencia;
use App\Models\QuejasySugerencias;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuejasySugerenciasResource extends Resource
{
    protected static ?string $model = QuejaSugerencia::class;

    protected static ?string $slug = 'quejas_sugerencias';
    protected static ?string $modelLabel = 'Quejas y sugerencias';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $activeNavigationIcon = 'heroicon-s-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Quejas y sugerencias';
    protected static ?string $navigationGroup = 'Soporte';
    protected static ?int $navigationSort = 10;

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tipo')
                    ->searchable()
                    ->sortable(),
            ])
            ->paginated([10, 25, 50, 100,])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()
                    ->hiddenLabel(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuejasySugerencias::route('/'),
            'view' => Pages\ViewQuejasySugerencias::route('/{record}/view')
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
