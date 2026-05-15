<?php

namespace App\Filament\Resources\Sliders\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SliderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Thông tin slider')
                ->schema([
                    Grid::make(2)->schema([
                        FileUpload::make('image')
                            ->label('Ảnh slider')
                            ->image()
                            ->required()
                            ->disk('public')
                            ->directory('sliders')
                            ->preserveFilenames()
                            ->previewable()
                            ->downloadable()
                            ->helperText('Khuyến nghị: upload ảnh theo kích thước 1672x941px (tỷ lệ widescreen) để hiển thị đủ ảnh và đồng bộ.'),

                        TextInput::make('sort_order')
                            ->label('Thứ tự')
                            ->numeric()
                            ->default(0),

                        TextInput::make('title')
                            ->label('Tiêu đề')
                            ->maxLength(255),

                        TextInput::make('subtitle')
                            ->label('Mô tả ngắn')
                            ->maxLength(255),

                        TextInput::make('link')
                            ->label('Liên kết')
                            ->url()
                            ->maxLength(255),
                    ]),
                ]),

            Section::make('Hiển thị')
                ->schema([
                    Grid::make(2)->schema([
                        Toggle::make('is_active')
                            ->label('Hiển thị')
                            ->default(true),

                        Toggle::make('show_overlay')
                            ->label('Hiện lớp phủ đen')
                            ->default(false),

                        Toggle::make('show_title')
                            ->label('Hiện tiêu đề')
                            ->default(true),

                        Toggle::make('show_subtitle')
                            ->label('Hiện mô tả ngắn')
                            ->default(true),

                        Toggle::make('show_button')
                            ->label('Hiện nút bấm')
                            ->default(true),
                    ]),

                    Placeholder::make('slider_upload_hint')
                        ->label('Lưu ý upload ảnh')
                        ->content('Khuyến nghị dùng ảnh 1672x941px để hiển thị full khung widescreen và không bị cắt. Nếu upload báo lỗi, hãy kiểm tra giới hạn PHP (upload_max_filesize / post_max_size).'),
                ]),
        ]);
    }
}