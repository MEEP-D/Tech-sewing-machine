<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Thông tin danh mục')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('name')
                                        ->label('Tên danh mục')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                    TextInput::make('slug')
                                        ->label('Slug (URL)')
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(255),
                                ]),
                                Grid::make(2)->schema([
                                    Select::make('type')
                                        ->label('Loại danh mục')
                                        ->options([
                                            'product' => 'Sản phẩm',
                                            'news' => 'Tin tức',
                                        ])
                                        ->default('product')
                                        ->required(),
                                    Select::make('parent_id')
                                        ->label('Danh mục cha')
                                        ->relationship('parent', 'name')
                                        ->searchable()
                                        ->preload(),
                                ]),
                                Textarea::make('description')
                                    ->label('Mô tả')
                                    ->rows(3)
                                    ->maxLength(1000)
                                    ->columnSpanFull(),
                                FileUpload::make('image')
                                    ->label('Hình ảnh đại diện')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('categories')
                                    ->disk('public')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state)
                                    ->maxSize(1024),
                                Grid::make(2)->schema([
                                    Toggle::make('is_active')
                                        ->label('Trạng thái kích hoạt')
                                        ->default(true),
                                    TextInput::make('sort_order')
                                        ->label('Thứ tự sắp xếp')
                                        ->numeric()
                                        ->minValue(0)
                                        ->default(0),
                                ]),
                                Toggle::make('highlight_mega_label')
                                    ->label('Làm nổi bật ở mega menu')
                                    ->helperText('Bật để danh mục cha hiển thị kiểu nút xanh chữ trắng trong mega menu Sản phẩm.')
                                    ->default(false),
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
                                                ->image()
                                                ->imageEditor()
                                                ->directory('seo/og-images')
                                                ->disk('public')
                                                ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state)
                                                ->maxSize(1024),
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
