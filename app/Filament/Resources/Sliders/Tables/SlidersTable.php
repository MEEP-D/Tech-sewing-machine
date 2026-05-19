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
            ImageColumn::make('image')->label('Ảnh')->disk('public')->size(64),
            TextColumn::make('title')->label('Tiêu đề')->searchable(),
            TextColumn::make('subtitle')->label('Mô tả')->limit(40),
            TextColumn::make('sort_order')->label('Thứ tự')->sortable(),
            IconColumn::make('show_overlay')->label('Phu den')->boolean(),
            IconColumn::make('show_title')->label('Hiện tiêu đề')->boolean(),
            IconColumn::make('show_subtitle')->label('Hiện mô tả ngắn')->boolean(),
            IconColumn::make('show_button')->label('Hiện nút')->boolean(),
            IconColumn::make('is_active')->label('Hiển thị')->boolean(),
        ])->recordActions([
            EditAction::make(),
        ])->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
    }
}

