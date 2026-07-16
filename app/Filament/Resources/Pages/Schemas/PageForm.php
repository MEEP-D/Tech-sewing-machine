<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Filament\Support\AdminFormValidation as V;
use App\Filament\Support\AdminRichEditor;
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
                                    ->rules(V::requiredText())
                                    ->validationMessages(V::messages())
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                                TextInput::make('slug')
                                    ->label('Slug (URL)')
                                    ->required()
                                    ->rules(V::slug())
                                    ->validationMessages(V::slugMessages())
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
                                    ->required()
                                    ->rules(['required', 'in:default,full_width,blank'])
                                    ->validationMessages(V::messages()),
                                Select::make('layout_mode')
                                    ->label('Kiểu layout')
                                    ->options([
                                        'content' => 'Nội dung truyền thống',
                                        'builder' => 'Builder linh hoạt',
                                    ])
                                    ->default('content')
                                    ->live()
                                    ->helperText('Nội dung truyền thống: hiển thị chuẩn. Builder linh hoạt: dùng cấu hình giao diện nâng cao.')
                                    ->required()
                                    ->rules(['required', 'in:content,builder'])
                                    ->validationMessages(V::messages()),
                                TextInput::make('cache_ttl')
                                    ->label('Cache TTL (giây)')
                                    ->numeric()
                                    ->rules(['nullable', 'integer', 'min:60', 'max:86400'])
                                    ->validationMessages(V::messages())
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
                                ->rules(V::text(500))
                                ->validationMessages(V::messages())
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
                            AdminRichEditor::configure(
                                RichEditor::make('content')
                                    ->label('Nội dung chi tiết')
                                    ->rules(V::richContent())
                                    ->validationMessages(V::messages()),
                                'pages/content',
                            ),
                            Textarea::make('style_config')
                                ->label('Cấu hình style (JSON)')
                                ->placeholder('{"max_width":"1100px","padding":"24px","background":"#fff","color":"#0f172a"}')
                                ->visible(fn (callable $get) => $get('layout_mode') === 'builder')
                                ->helperText('Chỉ áp dụng khi chọn Kiểu layout = Builder.')
                                ->json()
                                ->rules(['nullable', 'json'])
                                ->validationMessages(V::messages())
                                ->rows(4)
                                ->columnSpanFull(),
                            Grid::make(2)->schema([
                                TextInput::make('container_class')
                                    ->label('Lớp CSS khung nội dung')
                                    ->rules(V::text())
                                    ->validationMessages(V::messages())
                                    ->maxLength(255),
                                TextInput::make('bg_color')
                                    ->label('Màu nền')
                                    ->rules(V::hexColor())
                                    ->validationMessages(V::hexColorMessages())
                                    ->maxLength(20),
                                TextInput::make('text_color')
                                    ->label('Màu chữ')
                                    ->rules(V::hexColor())
                                    ->validationMessages(V::hexColorMessages())
                                    ->maxLength(20),
                                TextInput::make('spacing_top')
                                    ->label('Khoảng cách phía trên')
                                    ->rules(V::text(20))
                                    ->validationMessages(V::messages())
                                    ->maxLength(20),
                                TextInput::make('spacing_bottom')
                                    ->label('Khoảng cách phía dưới')
                                    ->rules(V::text(20))
                                    ->validationMessages(V::messages())
                                    ->maxLength(20),
                            ])->columnSpanFull(),
                        ]),

                    Tabs\Tab::make('SEO')
                        ->schema([
                            Section::make('Thẻ SEO')
                                ->relationship('seoMeta')
                                ->schema([
                                    TextInput::make('meta_title')
                                        ->label('Tiêu đề SEO')
                                        ->rules(V::text(70))
                                        ->validationMessages(V::messages())
                                        ->maxLength(70)
                                        ->helperText('Tối ưu: 50-60 ký tự'),
                                    Textarea::make('meta_description')
                                        ->label('Mô tả SEO')
                                        ->rows(3)
                                        ->rules(V::text(160))
                                        ->validationMessages(V::messages())
                                        ->maxLength(160)
                                        ->helperText('Tối ưu: 120-155 ký tự'),
                                    TextInput::make('focus_keyword')
                                        ->label('Từ khóa trọng tâm')
                                        ->rules(V::text(100))
                                        ->validationMessages(V::messages())
                                        ->maxLength(100),
                                    Grid::make(2)->schema([
                                        TextInput::make('og_title')
                                            ->label('Tiêu đề chia sẻ Facebook/Zalo')
                                            ->rules(V::text(95))
                                            ->validationMessages(V::messages())
                                            ->maxLength(95),
                                        FileUpload::make('og_image')
                                            ->label('Ảnh chia sẻ')
                                            ->image()->imageEditor()->directory('seo/og-images')
                                            ->disk('public')
                                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state)
                                            ->maxSize(1024)
                                            ->helperText('Khuyến nghị: 1200x630px'),
                                    ]),
                                    Textarea::make('og_description')
                                        ->label('Mô tả chia sẻ')
                                        ->rows(2)
                                        ->rules(V::text(200))
                                        ->validationMessages(V::messages())
                                        ->maxLength(200),
                                    TextInput::make('canonical_url')
                                        ->label('Đường dẫn chuẩn')
                                        ->url()
                                        ->rules(['nullable', 'url', 'max:500'])
                                        ->validationMessages(V::messages())
                                        ->maxLength(500),
                                    Grid::make(2)->schema([
                                        Toggle::make('no_index')
                                            ->label('Không cho công cụ tìm kiếm lập chỉ mục'),
                                        Toggle::make('no_follow')
                                            ->label('Không theo dõi liên kết trên trang'),
                                    ]),
                                ]),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }
}
