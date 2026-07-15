<?php

namespace App\Filament\Resources\FlashSales\Tables;

use App\Filament\Support\VietnameseAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FlashSalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->withCount('items'))
            ->columns([
                TextColumn::make('title')->label('Tiêu đề')->searchable(),
                TextColumn::make('items_count')
                    ->label('Số sản phẩm')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('starts_at')->label('Bắt đầu')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('ends_at')->label('Kết thúc')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('sort_order')->label('Thứ tự')->sortable(),
                IconColumn::make('show_countdown')->label('Đếm ngược')->boolean(),
                IconColumn::make('is_active')->label('Hiển thị')->boolean(),
            ])
            ->recordActions([
                VietnameseAction::edit(EditAction::make(), 'chương trình khuyến mãi nhanh'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    VietnameseAction::deleteBulk(DeleteBulkAction::make(), 'chương trình khuyến mãi nhanh'),
                ]),
            ]);
    }
}
