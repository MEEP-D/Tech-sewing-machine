<?php

namespace App\Filament\Resources\Menus\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                Select::make('location')
                    ->label('Vị trí menu')
                    ->options([
                        'header' => 'Đầu trang',
                        'footer' => 'Chân trang',
                    ])
                    ->required(),
                TextInput::make('label')->label('Nhãn hiển thị')->required()->maxLength(255),
                TextInput::make('url')
                    ->label('URL')
                    ->placeholder('/duong-dan-noi-bo-hoac-https://example.com')
                    ->maxLength(500)
                    ->rules([
                        'nullable',
                        'regex:/^(\\/.*|https?:\\/\\/.+)$/i',
                    ])
                    ->validationMessages([
                        'regex' => 'URL phải là liên kết đầy đủ (https://...) hoặc đường dẫn nội bộ bắt đầu bằng "/".',
                    ]),
                TextInput::make('route_name')->label('Tên route')->maxLength(255),
                Select::make('target')->options([
                    '_self' => 'Mở cùng tab',
                    '_blank' => 'Mở tab mới',
                ])->default('_self'),
                Select::make('parent_id')
                    ->label('Menu cha')
                    ->relationship('parent', 'label')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                TextInput::make('sort_order')->label('Thứ tự')->numeric()->minValue(0)->default(0),
                TextInput::make('icon')->label('Biểu tượng')->maxLength(100),
                TextInput::make('css_class')->label('Lớp CSS')->maxLength(255),
                Textarea::make('meta_config')->label('Meta JSON')->columnSpanFull()->json(),
                Checkbox::make('is_active')->label('Hiển thị')->default(true),
            ]),
        ]);
    }
}
