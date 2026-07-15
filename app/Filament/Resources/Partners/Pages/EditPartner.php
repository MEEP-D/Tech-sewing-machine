<?php

namespace App\Filament\Resources\Partners\Pages;

use App\Filament\Resources\Partners\PartnerResource;
use App\Filament\Support\VietnameseAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPartner extends EditRecord
{
    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            VietnameseAction::delete(DeleteAction::make(), 'đối tác'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Đã cập nhật thông tin đối tác thành công.';
    }
}
