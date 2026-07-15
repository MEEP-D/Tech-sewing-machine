<?php

namespace App\Filament\Resources\FlashSales\Pages;

use App\Filament\Resources\FlashSales\FlashSaleResource;
use App\Filament\Support\VietnameseAction;
use App\Support\DiscountSettingGuard;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Arrayable;

class EditFlashSale extends EditRecord
{
    protected static string $resource = FlashSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            VietnameseAction::delete(DeleteAction::make(), 'chương trình khuyến mãi nhanh'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->guardDiscountSettings($data);

        return $data;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Đã cập nhật chương trình khuyến mãi nhanh thành công.';
    }

    private function guardDiscountSettings(array $data): void
    {
        foreach ($this->getFlashSaleItemsState($data) as $item) {
            $productId = (int) ($item['product_id'] ?? 0);
            $discountPercent = (int) ($item['discount_percent'] ?? 0);
            $isActive = filter_var($item['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN);

            if (! $isActive || $productId <= 0 || $discountPercent <= 0) {
                continue;
            }

            if (! DiscountSettingGuard::productHasProductDiscount($productId)) {
                continue;
            }

            Notification::make()
                ->title(DiscountSettingGuard::MESSAGE)
                ->danger()
                ->send();

            $this->halt(shouldRollbackDatabaseTransaction: true);
        }
    }

    private function getFlashSaleItemsState(array $data): array
    {
        if (array_key_exists('items', $data)) {
            return (array) $data['items'];
        }

        $rawState = $this->form->getRawState();

        if ($rawState instanceof Arrayable) {
            $rawState = $rawState->toArray();
        }

        return (array) data_get($rawState, 'items', []);
    }
}
