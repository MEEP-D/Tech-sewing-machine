<?php

namespace App\Filament\Resources\NewsletterCampaigns\Tables;

use App\Filament\Support\VietnameseAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NewsletterCampaignsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('post.title')->label('Bài viết')->searchable()->limit(60),
                TextColumn::make('subject')->label('Tiêu đề email')->limit(70),
                TextColumn::make('logs_count')->label('Tổng gửi')->numeric()->sortable(),
                TextColumn::make('sent_logs_count')->label('Gửi thành công')->numeric()->sortable(),
                TextColumn::make('failed_logs_count')->label('Gửi lỗi')->numeric()->sortable(),
                TextColumn::make('queued_at')->label('Xếp hàng lúc')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('sent_at')->label('Gửi lúc')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->recordActions([
                VietnameseAction::edit(EditAction::make(), 'chiến dịch email'),
            ]);
    }
}
