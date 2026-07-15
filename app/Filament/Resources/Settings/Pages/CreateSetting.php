<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSetting extends CreateRecord
{
    protected static string $resource = SettingResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Đã thêm cấu hình mới thành công.';
    }
}
