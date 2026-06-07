<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Tabs')
                ->tabs([
                    Tabs\Tab::make('Nội dung')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('title')
                                    ->label('Tiêu đề trang')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                                TextInput::make('slug')
                                    ->label('Slug (URL)')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                Select::make('layout')
                                    ->label('Mẫu giao diện')
                                    ->options([
                                        'default' => 'Mặc định (có ảnh bìa)',
                                        'full_width' => 'Tràn viền (full width)',
                                        'blank' => 'Trang trống (chỉ nội dung)',
                                    ])
                                    ->default('default')
                                    ->live()
                                    ->required(),
                                Select::make('layout_mode')
                                    ->label('Kiểu layout')
                                    ->options([
                                        'content' => 'Nội dung truyền thống',
                                        'builder' => 'Builder linh hoạt',
                                    ])
                                    ->default('content')
                                    ->live()
                                    ->helperText('Content: hiển thị chuẩn. Builder: dùng bố cục linh hoạt + style_config.')
                                    ->required(),
                                TextInput::make('cache_ttl')
                                    ->label('Cache TTL (giây)')
                                    ->numeric()
                                    ->minValue(60)
                                    ->maxValue(86400)
                                    ->default(3600),
                                Toggle::make('cache_enabled')
                                    ->label('Bật cache')
                                    ->default(true),
                                Toggle::make('is_active')
                                    ->label('Công khai')
                                    ->default(true),
                            ]),
                            TextInput::make('excerpt')
                                ->label('Mô tả ngắn')
                                ->maxLength(500)
                                ->columnSpanFull(),
                            FileUpload::make('image')
                                ->label('Hình ảnh đại diện')
                                ->image()->imageEditor()->disk('public')
                                ->directory('pages')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->maxSize(2048)
                                ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state)
                                ->columnSpanFull(),
                            RichEditor::make('content')
                                ->label('Nội dung chi tiết')
                                ->columnSpanFull(),
                            Textarea::make('style_config')
                                ->label('Cấu hình style (JSON)')
                                ->placeholder('{"max_width":"1100px","padding":"24px","background":"#fff","color":"#0f172a"}')
                                ->visible(fn (callable $get) => $get('layout_mode') === 'builder')
                                ->helperText('Chỉ áp dụng khi chọn Kiểu layout = Builder.')
                                ->json()
                                ->rows(4)
                                ->columnSpanFull(),
                            Grid::make(2)->schema([
                                TextInput::make('container_class')
                                    ->label('Container class')
                                    ->maxLength(255),
                                TextInput::make('bg_color')
                                    ->label('Màu nền')
                                    ->maxLength(20)
                                    ->rules(['nullable', 'regex:/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/'])
                                    ->validationMessages(['regex' => 'Màu nền phải là mã HEX hợp lệ, ví dụ #ffffff.']),
                                TextInput::make('text_color')
                                    ->label('Màu chữ')
                                    ->maxLength(20)
                                    ->rules(['nullable', 'regex:/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/'])
                                    ->validationMessages(['regex' => 'Màu chữ phải là mã HEX hợp lệ, ví dụ #0f172a.']),
                                TextInput::make('spacing_top')
                                    ->label('Spacing top')
                                    ->maxLength(20),
                                TextInput::make('spacing_bottom')
                                    ->label('Spacing bottom')
                                    ->maxLength(20),
                            ])->columnSpanFull(),
                        ]),

                    Tabs\Tab::make('SEO')
                        ->schema([
                            Section::make('Thẻ SEO')
                                ->relationship('seoMeta')
                                ->schema([
                                    TextInput::make('meta_title')
                                        ->label('Meta Title')
                                        ->maxLength(70)
                                        ->helperText('Tối ưu: 50-60 ký tự'),
                                    Textarea::make('meta_description')
                                        ->label('Meta Description')
                                        ->rows(3)
                                        ->maxLength(160)
                                        ->helperText('Tối ưu: 120-155 ký tự'),
                                    TextInput::make('focus_keyword')
                                        ->label('Từ khóa trọng tâm')
                                        ->maxLength(100),
                                    Grid::make(2)->schema([
                                        TextInput::make('og_title')
                                            ->label('OG Title (Facebook/Zalo)')
                                            ->maxLength(95),
                                        FileUpload::make('og_image')
                                            ->label('OG Image')
                                            ->image()->imageEditor()->directory('seo/og-images')
                                            ->disk('public')
                                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state)
                                            ->maxSize(1024)
                                            ->helperText('Khuyến nghị: 1200x630px'),
                                    ]),
                                    Textarea::make('og_description')
                                        ->label('OG Description')
                                        ->rows(2)
                                        ->maxLength(200),
                                    TextInput::make('canonical_url')
                                        ->label('Canonical URL')
                                        ->url()
                                        ->maxLength(500),
                                    Grid::make(2)->schema([
                                        Toggle::make('no_index')
                                            ->label('No Index'),
                                        Toggle::make('no_follow')
                                            ->label('No Follow'),
                                    ]),
                                ]),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }
}
