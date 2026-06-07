<?php

namespace App\Filament\Resources\ImportHistories\Pages;

use App\Filament\Resources\ImportHistories\ImportHistoryResource;
use Filament\Actions\Action;
use Filament\Actions\Imports\Models\Import as ImportModel;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\URL;

class ViewImportHistory extends ViewRecord
{
    protected static string $resource = ImportHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadFailedRows')
                ->label('Tải lỗi CSV')
                ->icon(Heroicon::ArrowDownTray)
                ->color('danger')
                ->visible(fn (ImportModel $record): bool => ImportHistoryResource::getDisplayFailedRowsCount($record) > 0)
                ->url(fn (ImportModel $record): string => URL::signedRoute('filament.imports.failed-rows.download', [
                    'authGuard' => Filament::getAuthGuard(),
                    'import' => $record,
                ], absolute: false), shouldOpenInNewTab: true),
        ];
    }
}
