<?php

namespace App\Filament\Resources\ImportHistories\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FailedImportRowsRelationManager extends RelationManager
{
    protected static string $relationship = 'failedRows';

    protected static ?string $title = 'Dòng import lỗi';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('id')
            ->columns([
                TextColumn::make('id')
                    ->label('Dòng lỗi #')
                    ->sortable(),
                TextColumn::make('validation_error')
                    ->label('Lỗi cụ thể')
                    ->wrap()
                    ->searchable()
                    ->placeholder('Lỗi hệ thống không có thông báo validation.'),
                TextColumn::make('data')
                    ->label('Dữ liệu dòng import')
                    ->state(fn ($record): string => json_encode($record->data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT))
                    ->wrap()
                    ->fontFamily('mono')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ]);
    }
}
