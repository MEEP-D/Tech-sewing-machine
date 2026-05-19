<?php

namespace App\Filament\Resources\NewsletterSubscribers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsletterSubscriberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Người đăng ký')->schema([
                Grid::make(2)->schema([
                    TextInput::make('email')->label('Email')->email()->required()->disabled(),
                    Select::make('status')->label('Trạng thái')->options([
                        'pending' => 'Chờ xác nhận',
                        'active' => 'Đang nhận tin',
                        'unsubscribed' => 'Đã hủy đăng ký',
                    ])->required(),
                    DateTimePicker::make('confirmed_at')->label('Thời gian xác nhận')->disabled(),
                    DateTimePicker::make('unsubscribed_at')->label('Thời gian hủy')->disabled(),
                ]),
            ]),
        ]);
    }
}
