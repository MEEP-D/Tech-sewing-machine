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
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                                TextInput::make('slug')
                                    ->label('Slug (URL)')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                Select::make('layout')
                                    ->label('Mẫu giao diện')
                                    ->options([
                                        'default' => 'Mặc định (Bài viết có ảnh bìa)',
                                        'full_width' => 'Tràn viền (Full Width)',
                                        'blank' => 'Trang trống (Chỉ hiển thị nội dung)',
                                    ])
                                    ->default('default')
                                    ->required(),
                                Select::make('layout_mode')
                                    ->label('Kiểu layout')
                                    ->options([
                                        'content' => 'Nội dung truyền thống',
                                        'builder' => 'Trình dựng trực quan',
                                    ])
                                    ->default('content')
                                    ->required(),
                                TextInput::make('cache_ttl')
                                    ->label('Cache TTL (giây)')
                                    ->numeric()
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
                                ->columnSpanFull(),
                            FileUpload::make('image')
                                ->label('Hình ảnh đại diện')
                                ->image()->imageEditor()->disk('public')
                                ->directory('pages')
                                ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state)
                                ->columnSpanFull(),
                            RichEditor::make('content')
                                ->label('Nội dung chi tiết')
                                ->columnSpanFull(),
                            Textarea::make('style_config')
                                ->label('Cấu hình style (JSON)')
                                ->columnSpanFull(),
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
