<?php

namespace App\Filament\Resources\Menus\Pages;

use App\Filament\Resources\Menus\MenuResource;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Đã cập nhật thông tin menu thành công.';
    }
}
