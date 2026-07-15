<?php

namespace App\Filament\Resources\FlashSales\Pages;

use App\Filament\Resources\FlashSales\FlashSaleResource;
use App\Support\DiscountSettingGuard;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Arrayable;

class CreateFlashSale extends CreateRecord
{
    protected static string $resource = FlashSaleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->guardDiscountSettings($data);

        return $data;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Đã thêm chương trình khuyến mãi nhanh thành công.';
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
