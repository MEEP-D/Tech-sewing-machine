<?php

namespace App\Filament\Resources\NewsletterCampaigns\Schemas;

use App\Filament\Support\AdminFormValidation as V;
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
                    TextInput::make('post.title')->label('Bài viết')->rules(V::text())->validationMessages(V::messages())->disabled(),
                    TextInput::make('subject')->label('Tiêu đề email')->rules(V::text())->validationMessages(V::messages())->disabled(),
                    DateTimePicker::make('queued_at')->label('Xếp hàng lúc')->rules(['nullable', 'date'])->validationMessages(V::messages())->disabled(),
                    DateTimePicker::make('sent_at')->label('Gửi lúc')->rules(['nullable', 'date'])->validationMessages(V::messages())->disabled(),
                    TextInput::make('logs_count')->label('Tổng job')->rules(V::nonNegativeInteger())->validationMessages(V::messages())->disabled(),
                    TextInput::make('sent_logs_count')->label('Đã gửi')->rules(V::nonNegativeInteger())->validationMessages(V::messages())->disabled(),
                    TextInput::make('failed_logs_count')->label('Gửi lỗi')->rules(V::nonNegativeInteger())->validationMessages(V::messages())->disabled(),
                ]),
            ]),
        ]);
    }
}
