<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Filament\Support\VietnameseAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            VietnameseAction::delete(DeleteAction::make(), 'bài viết'),
            VietnameseAction::forceDelete(ForceDeleteAction::make(), 'bài viết'),
            VietnameseAction::restore(RestoreAction::make(), 'bài viết'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Đã cập nhật thông tin bài viết thành công.';
    }
}
