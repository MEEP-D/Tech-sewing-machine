<?php

namespace App\Filament\Resources\Partners\Pages;

use App\Filament\Resources\Partners\PartnerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPartner extends EditRecord
{
    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Xác nhận xóa đối tác')
                ->modalDescription('Bạn có chắc chắn muốn xóa đối tác này không?'),
        ];
    }
}
