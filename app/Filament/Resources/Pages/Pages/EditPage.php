<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Pages\PageResource;
use App\Filament\Support\VietnameseAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            VietnameseAction::delete(DeleteAction::make(), 'trang'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Đã cập nhật thông tin trang thành công.';
    }
}
