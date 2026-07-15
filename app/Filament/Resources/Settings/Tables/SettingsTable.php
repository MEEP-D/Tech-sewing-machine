<?php

namespace App\Filament\Resources\Settings\Tables;

use App\Filament\Support\VietnameseAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')->label('Khóa')->searchable(),
                TextColumn::make('label')->label('Nhãn')->searchable(),
                TextColumn::make('group')->label('Nhóm')->badge(),
                TextColumn::make('type')->label('Kiểu dữ liệu')->badge(),
                TextColumn::make('updated_at')->label('Cập nhật')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                VietnameseAction::edit(EditAction::make(), 'cấu hình'),
                VietnameseAction::delete(DeleteAction::make(), 'cấu hình'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    VietnameseAction::deleteBulk(DeleteBulkAction::make(), 'cấu hình'),
                ]),
            ]);
    }
}
