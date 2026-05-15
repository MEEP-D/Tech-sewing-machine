<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                TextInput::make('key')->required(),
                TextInput::make('title'),
                TextInput::make('subtitle'),
                FileUpload::make('image')
                    ->label('Ảnh banner')
                    ->image()
                    ->disk('public')
                    ->directory('banners')
                    ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state)
                    ->preserveFilenames()
                    ->previewable()
                    ->downloadable(),
                TextInput::make('link'),
                TextInput::make('button_text'),
                TextInput::make('size_label')->label('Kích thước chuẩn'),
                TextInput::make('recommended_size')->label('Kích thước khuyến nghị'),
                TextInput::make('sort_order')->numeric()->default(0),
                Checkbox::make('is_active')->default(true),
                Placeholder::make('banner_hint')
                    ->label('Lưu ý upload ảnh banner')
                    ->content('Kích thước chuẩn khuyến nghị: 1920 x 720 px, tỷ lệ 8:3. Ưu tiên ảnh JPG/WEBP sắc nét, không kéo giãn, không vỡ hình. Nếu cần hiển thị full-bleed, dùng ảnh đủ rộng tối thiểu 1920px.'),
            ]),
        ]);
    }
}
