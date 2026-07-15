<?php

namespace App\Filament\Resources\FlashSales\Pages;

use App\Filament\Resources\FlashSales\FlashSaleResource;
use App\Filament\Support\VietnameseAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFlashSales extends ListRecords
{
    protected static string $resource = FlashSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            VietnameseAction::create(CreateAction::make(), 'chương trình khuyến mãi nhanh'),
        ];
    }
}
