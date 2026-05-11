<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Thông tin khách hàng')->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')->label('Họ và tên')->required()->disabled(),
                    TextInput::make('phone')->label('Số điện thoại')->required()->disabled(),
                    TextInput::make('email')->label('Email')->disabled(),
                    TextInput::make('company')->label('Công ty')->disabled(),
                    TextInput::make('interest')->label('Nhu cầu quan tâm')->disabled(),
                    TextInput::make('source')->label('Nguồn')->disabled(),
                ]),
                Textarea::make('message')->label('Nội dung liên hệ')->rows(4)->disabled(),
            ]),
            Section::make('Xử lý liên hệ')->schema([
                Grid::make(2)->schema([
                    Select::make('status')
                        ->label('Trạng thái')
                        ->options([
                            'new' => 'Mới',
                            'contacted' => 'Đã liên hệ',
                            'qualified' => 'Đủ điều kiện',
                            'closed' => 'Đã đóng',
                        ])
                        ->required(),
                ]),
                Textarea::make('notes')->label('Ghi chú nội bộ')->rows(5),
            ]),
        ]);
    }
}
