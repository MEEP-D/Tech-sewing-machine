<?php

namespace App\Filament\Resources\Menus\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('location')->badge(),
            TextColumn::make('label')->searchable(),
            TextColumn::make('parent_id')->label('Menu cha'),
            TextColumn::make('seo_title')->limit(30)->toggleable(),
            TextColumn::make('sort_order')->sortable(),
            IconColumn::make('is_active')->boolean(),
        ])->recordActions([
            EditAction::make(),
            DeleteAction::make(),
        ])->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
    }
}
