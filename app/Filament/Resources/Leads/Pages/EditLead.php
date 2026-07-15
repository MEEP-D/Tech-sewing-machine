<?php

namespace App\Filament\Resources\Leads\Pages;

use App\Filament\Resources\Leads\LeadResource;
use App\Filament\Support\VietnameseAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLead extends EditRecord
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            VietnameseAction::delete(DeleteAction::make(), 'liên hệ'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Đã cập nhật thông tin liên hệ thành công.';
    }
}
