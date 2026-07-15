<?php

namespace App\Filament\Resources\Menus\Schemas;

use App\Filament\Support\AdminFormValidation as V;
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
                    ->required()
                    ->rules(['required', 'in:header,footer'])
                    ->validationMessages(V::messages()),
                TextInput::make('label')->label('Nhãn hiển thị')->required()->rules(V::requiredText())->validationMessages(V::messages())->maxLength(255),
                TextInput::make('url')
                    ->label('URL')
                    ->placeholder('/duong-dan-noi-bo-hoac-https://example.com')
                    ->maxLength(500)
                    ->rules(V::internalOrAbsoluteUrl())
                    ->validationMessages(V::urlMessages()),
                TextInput::make('route_name')->label('Tên route')->rules(V::text())->validationMessages(V::messages())->maxLength(255),
                Select::make('target')->options([
                    '_self' => 'Mở cùng tab',
                    '_blank' => 'Mở tab mới',
                ])->default('_self')
                    ->rules(['nullable', 'in:_self,_blank'])
                    ->validationMessages(V::messages()),
                Select::make('parent_id')
                    ->label('Menu cha')
                    ->relationship('parent', 'label')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->rules(['nullable', 'exists:menus,id'])
                    ->validationMessages(V::messages()),
                TextInput::make('sort_order')->label('Thứ tự')->numeric()->rules(V::nonNegativeInteger())->validationMessages(V::messages())->minValue(0)->default(0),
                TextInput::make('icon')->label('Biểu tượng')->rules(V::text(100))->validationMessages(V::messages())->maxLength(100),
                TextInput::make('css_class')->label('Lớp CSS')->rules(V::text())->validationMessages(V::messages())->maxLength(255),
                Textarea::make('meta_config')
                    ->label('Cấu hình nâng cao dạng JSON')
                    ->helperText('Chỉ chỉnh khi cần cấu hình riêng cho menu. Dữ liệu phải đúng định dạng JSON.')
                    ->columnSpanFull()
                    ->json()
                    ->rules(['nullable', 'json'])
                    ->validationMessages(V::messages()),
                Checkbox::make('is_active')->label('Hiển thị')->default(true),
            ]),
        ]);
    }
}
