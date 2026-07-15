<?php

namespace App\Filament\Resources\Leads\Schemas;

use App\Filament\Support\AdminFormValidation as V;
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
                    TextInput::make('name')->label('Họ và tên')->required()->rules(V::requiredText())->validationMessages(V::messages())->disabled(),
                    TextInput::make('phone')->label('Số điện thoại')->required()->rules(['required', 'string', 'max:50', 'regex:/^[0-9+\s().\/-]{9,50}$/'])->validationMessages(V::phoneMessages())->disabled(),
                    TextInput::make('email')->label('Email')->email()->rules(['nullable', 'email', 'max:255'])->validationMessages(V::messages())->disabled(),
                    TextInput::make('company')->label('Công ty')->rules(V::text())->validationMessages(V::messages())->disabled(),
                    TextInput::make('interest')->label('Nhu cầu quan tâm')->rules(V::text())->validationMessages(V::messages())->disabled(),
                    TextInput::make('source')->label('Nguồn')->rules(V::text(100))->validationMessages(V::messages())->disabled(),
                ]),
                Textarea::make('message')->label('Nội dung liên hệ')->rows(4)->rules(V::text(5000))->validationMessages(V::messages())->disabled(),
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
                        ->required()
                        ->rules(['required', 'in:new,contacted,qualified,closed'])
                        ->validationMessages(V::messages()),
                ]),
                Textarea::make('notes')->label('Ghi chú nội bộ')->rows(5)->rules(V::text(5000))->validationMessages(V::messages()),
            ]),
        ]);
    }
}
