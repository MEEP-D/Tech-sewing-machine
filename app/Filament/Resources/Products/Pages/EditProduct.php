<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Xác nhận xóa sản phẩm')
                ->modalDescription('Bạn có chắc chắn muốn xóa sản phẩm này không?'),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
