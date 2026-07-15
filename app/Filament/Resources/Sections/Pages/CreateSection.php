<?php

namespace App\Filament\Resources\Sections\Pages;

use App\Filament\Resources\Sections\SectionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSection extends CreateRecord
{
    protected static string $resource = SectionResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Đã thêm khối nội dung mới thành công.';
    }
}
