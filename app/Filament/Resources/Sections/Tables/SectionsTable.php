<?php

namespace App\Filament\Resources\Sections\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('key')->label('Khoa')->searchable(),
            TextColumn::make('title')->label('Tieu de')->searchable(),
            ImageColumn::make('image')->label('Anh')->disk('public')->size(56),
            TextColumn::make('type')->label('Loai')->badge(),
            TextColumn::make('sort_order')->label('Thu tu')->sortable(),
            IconColumn::make('is_active')->label('Hien thi')->boolean(),
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

