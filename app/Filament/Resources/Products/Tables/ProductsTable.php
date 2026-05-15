<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
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
                ImageColumn::make('display_image_url')
                    ->label('Anh')
                    ->circular()
                    ->size(56)
                    ->defaultImageUrl(asset('assets/frontend/images/placeholder.jpg')),
                TextColumn::make('name')->label('Ten')->searchable(),
                TextColumn::make('code')->label('Ma')->searchable(),
                TextColumn::make('sku')->label('SKU')->searchable(),
                TextColumn::make('category.name')->label('Danh muc')->searchable(),
                TextColumn::make('price')->label('Gia'),
                IconColumn::make('is_new')->label('Moi')->boolean(),
                IconColumn::make('is_hot')->label('Hot')->boolean(),
                TextColumn::make('status')->label('Trang thai')->badge(),
                TextColumn::make('sort_order')->label('Thu tu')->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('set_exclusive')
                    ->label('Dat dot pha')
                    ->icon('heroicon-m-star')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Dat san pham dot pha')
                    ->modalDescription('He thong se chi giu dung 1 san pham dot pha. San pham dot pha hien tai se bi thay the.')
                    ->visible(fn (Product $record): bool => ! $record->is_exclusive)
                    ->action(function (Product $record): void {
                        $record->is_exclusive = true;
                        $record->save();

                        Notification::make()
                            ->title('Da dat san pham dot pha')
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
