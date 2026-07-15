<?php

namespace App\Filament\Resources\Sections\Pages;

use App\Filament\Resources\Sections\SectionResource;
use Filament\Resources\Pages\EditRecord;

class EditSection extends EditRecord
{
    protected static string $resource = SectionResource::class;

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Đã cập nhật khối nội dung thành công.';
    }
}
