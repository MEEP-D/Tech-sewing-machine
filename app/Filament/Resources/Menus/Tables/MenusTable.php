<?php

namespace App\Filament\Resources\Menus\Tables;

use App\Filament\Support\VietnameseAction;
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
            TextColumn::make('location')->label('Vị trí')->badge(),
            TextColumn::make('label')->label('Nhãn menu')->searchable(),
            TextColumn::make('parent_id')->label('Menu cha'),
            TextColumn::make('seo_title')->label('Tiêu đề SEO')->limit(30)->toggleable(),
            TextColumn::make('sort_order')->label('Thứ tự')->sortable(),
            IconColumn::make('is_active')->label('Hiển thị')->boolean(),
        ])->recordActions([
            VietnameseAction::edit(EditAction::make(), 'menu'),
            VietnameseAction::delete(DeleteAction::make(), 'menu'),
        ])->toolbarActions([
            BulkActionGroup::make([
                VietnameseAction::deleteBulk(DeleteBulkAction::make(), 'menu'),
            ]),
        ]);
    }
}
