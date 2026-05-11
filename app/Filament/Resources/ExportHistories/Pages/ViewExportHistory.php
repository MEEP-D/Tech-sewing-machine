<?php

namespace App\Filament\Resources\ExportHistories\Pages;

use App\Filament\Resources\ExportHistories\ExportHistoryResource;
use Filament\Actions\Action;
use Filament\Actions\Exports\Models\Export as ExportModel;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewExportHistory extends ViewRecord
{
    protected static string $resource = ExportHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadXlsx')
                ->label('Tải Excel')
                ->icon(Heroicon::ArrowDownTray)
                ->color('success')
                ->visible(fn (ExportModel $record): bool => ExportHistoryResource::hasDownloadableFile($record))
                ->url(fn (ExportModel $record): string => ExportHistoryResource::downloadUrl($record), shouldOpenInNewTab: true),
        ];
    }
}
