<?php

namespace App\Filament\Resources\NewsletterCampaigns\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsletterCampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Chiến dịch email')->schema([
                Grid::make(2)->schema([
                    TextInput::make('post.title')->label('Bài viết')->disabled(),
                    TextInput::make('subject')->label('Tiêu đề email')->disabled(),
                    DateTimePicker::make('queued_at')->label('Xếp hàng lúc')->disabled(),
                    DateTimePicker::make('sent_at')->label('Gửi lúc')->disabled(),
                    TextInput::make('logs_count')->label('Tổng job')->disabled(),
                    TextInput::make('sent_logs_count')->label('Đã gửi')->disabled(),
                    TextInput::make('failed_logs_count')->label('Gửi lỗi')->disabled(),
                ]),
            ]),
        ]);
    }
}
