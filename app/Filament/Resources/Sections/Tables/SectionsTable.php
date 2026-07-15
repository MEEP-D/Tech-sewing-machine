<?php

namespace App\Filament\Resources\Sections\Tables;

use App\Filament\Support\VietnameseAction;
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
            TextColumn::make('key')->label('Khóa')->searchable(),
            TextColumn::make('title')->label('Tiêu đề')->searchable(),
            ImageColumn::make('image')->label('Ảnh')->disk('public')->size(56),
            TextColumn::make('type')->label('Loại')->badge(),
            TextColumn::make('sort_order')->label('Thứ tự')->sortable(),
            IconColumn::make('is_active')->label('Hiển thị')->boolean(),
        ])->recordActions([
            VietnameseAction::edit(EditAction::make(), 'khối nội dung'),
            VietnameseAction::delete(DeleteAction::make(), 'khối nội dung'),
        ])->toolbarActions([
            BulkActionGroup::make([
                VietnameseAction::deleteBulk(DeleteBulkAction::make(), 'khối nội dung'),
            ]),
        ]);
    }
}

