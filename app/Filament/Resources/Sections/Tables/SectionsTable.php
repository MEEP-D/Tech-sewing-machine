<?php

namespace App\Filament\Resources\Sections\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('key')->searchable(),
            TextColumn::make('title')->searchable(),
            TextColumn::make('type')->badge(),
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
