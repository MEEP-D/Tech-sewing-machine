<?php

namespace App\Filament\Resources\Settings\Schemas;

use App\Filament\Support\AdminFormValidation as V;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                TextInput::make('key')->label('Khóa')->required()->alphaDash()->rules(['required', 'alpha_dash', 'max:100'])->validationMessages(V::messages())->maxLength(100)->unique(ignoreRecord: true),
                TextInput::make('label')->label('Nhãn')->required()->rules(V::requiredText())->validationMessages(V::messages())->maxLength(255),
                Select::make('group')->options([
                    'general' => 'Tổng quan',
                    'contact' => 'Liên hệ',
                    'seo' => 'SEO',
                    'branding' => 'Thương hiệu',
                    'homepage' => 'Trang chủ',
                    'pages' => 'Trang tĩnh',
                    'theme' => 'Giao diện',
                    'navigation' => 'Điều hướng',
                    'menu' => 'Menu',
                    'mail' => 'Email',
                ])->required()->rules(['required', 'in:general,contact,seo,branding,homepage,pages,theme,navigation,menu,mail'])->validationMessages(V::messages()),
                Select::make('type')->options([
                    'text' => 'Văn bản ngắn',
                    'textarea' => 'Văn bản dài',
                    'image' => 'Hình ảnh',
                    'boolean' => 'Bật / tắt',
                    'json' => 'JSON',
                    'color' => 'Màu sắc',
                    'number' => 'Số',
                ])->required()->rules(['required', 'in:text,textarea,image,boolean,json,color,number'])->validationMessages(V::messages()),
                Textarea::make('value')
                    ->label('Giá trị')
                    ->json(fn (callable $get): bool => $get('type') === 'json')
                    ->rules(fn (callable $get): array => match ($get('type')) {
                        'json' => ['nullable', 'json', 'max:20000'],
                        'number' => ['nullable', 'numeric'],
                        'color' => V::hexColor(),
                        default => ['nullable', 'string', 'max:20000'],
                    })
                    ->validationMessages(V::messages())
                    ->maxLength(20000)
                    ->columnSpanFull(),
            ]),
        ]);
    }
}
