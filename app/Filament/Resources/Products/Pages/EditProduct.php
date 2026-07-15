<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Filament\Support\VietnameseAction;
use App\Support\DiscountSettingGuard;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            VietnameseAction::delete(DeleteAction::make(), 'sản phẩm'),
            VietnameseAction::forceDelete(ForceDeleteAction::make(), 'sản phẩm'),
            VietnameseAction::restore(RestoreAction::make(), 'sản phẩm'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Đã cập nhật thông tin sản phẩm thành công.';
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (
            ((int) ($data['discount_percent'] ?? 0)) > 0
            && DiscountSettingGuard::productHasFlashSaleDiscount((int) $this->record->getKey())
        ) {
            Notification::make()
                ->title(DiscountSettingGuard::MESSAGE)
                ->danger()
                ->send();

            $this->halt(shouldRollbackDatabaseTransaction: true);
        }

        return $data;
    }
}
