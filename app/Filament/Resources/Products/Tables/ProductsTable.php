<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use App\Filament\Support\VietnameseAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('display_image')
                    ->label('Ảnh')
                    ->circular()
                    ->size(56)
                    ->disk('public')
                    ->defaultImageUrl(asset('assets/frontend/images/placeholder.jpg')),
                TextColumn::make('name')->label('Tên')->searchable(),
                TextColumn::make('code')->label('Mã')->searchable(),
                TextColumn::make('sku')->label('SKU')->searchable(),
                TextColumn::make('category.name')->label('Danh mục')->searchable(),
                TextColumn::make('price')->label('Giá'),
                TextColumn::make('availability_badge_label')
                    ->label('Thẻ')
                    ->badge()
                    ->color(fn (Product $record): string => match ($record->availability_badge) {
                        Product::AVAILABILITY_BADGE_READY => 'success',
                        Product::AVAILABILITY_BADGE_PREORDER => 'warning',
                        default => 'gray',
                    }),
                IconColumn::make('is_new')->label('Mới')->boolean(),
                IconColumn::make('is_hot')->label('Hot')->boolean(),
                IconColumn::make('is_exclusive')->label('Đột phá')->boolean(),
                IconColumn::make('show_in_banner_switcher')->label('Banner')->boolean(),
                TextColumn::make('status')->label('Trạng thái')->badge(),
                TextColumn::make('sort_order')->label('Thứ tự')->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('set_exclusive')
                    ->label('Đặt đột phá')
                    ->icon('heroicon-m-star')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Đặt sản phẩm đột phá')
                    ->modalDescription('Hệ thống sẽ chỉ giữ đúng 1 sản phẩm đột phá. Sản phẩm đột phá hiện tại sẽ bị thay thế.')
                    ->visible(fn (Product $record): bool => ! $record->is_exclusive)
                    ->action(function (Product $record): void {
                        $record->is_exclusive = true;
                        $record->save();

                        Notification::make()
                            ->title('Đã đặt sản phẩm đột phá')
                            ->success()
                            ->send();
                    }),
                VietnameseAction::edit(EditAction::make(), 'sản phẩm'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    VietnameseAction::deleteBulk(DeleteBulkAction::make(), 'sản phẩm'),
                    VietnameseAction::forceDeleteBulk(ForceDeleteBulkAction::make(), 'sản phẩm'),
                    VietnameseAction::restoreBulk(RestoreBulkAction::make(), 'sản phẩm'),
                ]),
            ]);
    }
}
