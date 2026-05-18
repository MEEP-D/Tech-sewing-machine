<?php

namespace App\Filament\Resources\Sliders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SlidersTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('image')->label('Anh')->disk('public')->size(64),
            TextColumn::make('title')->label('Tieu de')->searchable(),
            TextColumn::make('subtitle')->label('Mo ta')->limit(40),
            TextColumn::make('sort_order')->label('Thu tu')->sortable(),
            IconColumn::make('show_overlay')->label('Phu den')->boolean(),
            IconColumn::make('show_title')->label('Hien tieu de')->boolean(),
            IconColumn::make('show_subtitle')->label('Hien mo ta ngan')->boolean(),
            IconColumn::make('show_button')->label('Hien nut')->boolean(),
            IconColumn::make('is_active')->label('Hien thi')->boolean(),
        ])->recordActions([
            EditAction::make(),
        ])->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
    }
}

