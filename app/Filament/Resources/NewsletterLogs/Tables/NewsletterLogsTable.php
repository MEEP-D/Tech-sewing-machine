<?php

namespace App\Filament\Resources\NewsletterLogs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NewsletterLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('campaign.id')->label('Chiến dịch')->sortable(),
                TextColumn::make('campaign.post.title')->label('Bài viết')->limit(45),
                TextColumn::make('subscriber.email')->label('Người nhận')->searchable(),
                TextColumn::make('status')->label('Trạng thái')->badge()->sortable(),
                TextColumn::make('error_message')->label('Lỗi')->limit(70)->toggleable(),
                TextColumn::make('sent_at')->label('Gửi lúc')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('created_at')->label('Tạo lúc')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Trạng thái')->options([
                    'queued' => 'Đang chờ',
                    'sent' => 'Đã gửi',
                    'failed' => 'Thất bại',
                ]),
            ]);
    }
}
