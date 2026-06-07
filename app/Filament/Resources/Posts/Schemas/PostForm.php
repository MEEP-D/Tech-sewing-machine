<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Nội dung')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('title')
                                        ->label('Tiêu đề')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Str::slug($state))),
                                    TextInput::make('slug')
                                        ->label('Slug (URL)')
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(255),
                                ]),
                                Grid::make(3)->schema([
                                    Select::make('type')
                                        ->label('Loại bài viết')
                                        ->options([
                                            'news' => 'Tin tức',
                                            'event' => 'Sự kiện',
                                            'fair' => 'Hội chợ',
                                            'seminar' => 'Hội thảo',
                                        ])
                                        ->default('news')
                                        ->required()
                                        ->live(),
                                    Select::make('status')
                                        ->label('Trạng thái')
                                        ->options([
                                            'draft' => 'Nháp',
                                            'published' => 'Công khai',
                                            'archived' => 'Lưu trữ',
                                        ])
                                        ->default('draft')
                                        ->required(),
                                    Select::make('author_id')
                                        ->label('Tác giả')
                                        ->relationship('author', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required(),
                                ]),
                                Grid::make(2)->schema([
                                    Select::make('category_id')
                                        ->label('Danh mục')
                                        ->relationship('category', 'name', fn ($query) => $query->where('type', 'news'))
                                        ->searchable()
                                        ->preload()
                                        ->required(),
                                    DateTimePicker::make('published_at')
                                        ->label('Ngày xuất bản')
                                        ->helperText('Nếu để trống khi công khai, hệ thống sẽ dùng thời điểm lưu.')
                                        ->native(false)
                                        ->displayFormat('d/m/Y H:i'),
                                ]),
                                Textarea::make('excerpt')
                                    ->label('Mô tả tóm tắt')
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->helperText('Hiển thị ở danh sách bài viết và SEO')
                                    ->columnSpanFull(),
                                RichEditor::make('content')
                                    ->label('Nội dung chi tiết')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'underline',
                                        'strike',
                                        'h2',
                                        'h3',
                                        'bulletList',
                                        'orderedList',
                                        'link',
                                        'blockquote',
                                        'undo',
                                        'redo',
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('Sự kiện')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('event_date')
                                        ->label('Ngày diễn ra')
                                        ->type('date')
                                        ->rules(['nullable', 'date'])
                                        ->helperText('Chỉ điền nếu là Hội chợ / Hội thảo'),
                                    TextInput::make('event_location')
                                        ->label('Địa điểm')
                                        ->required(fn (callable $get): bool => in_array($get('type'), ['event', 'fair', 'seminar'], true))
                                        ->maxLength(255)
                                        ->helperText('Ví dụ: TP. Hồ Chí Minh, Hà Nội, Barcelona...'),
                                ]),
                            ]),

                        Tabs\Tab::make('Hình ảnh')
                            ->schema([
                                FileUpload::make('thumbnail')
                                    ->label('Ảnh bìa bài viết')
                                    ->image()
                                    ->imageEditor()
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('16:9')
                                    ->imageResizeTargetWidth('1200')
                                    ->imageResizeTargetHeight('675')
                                    ->disk('public')
                                    ->directory('posts/thumbnails')
                                    ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(2048)
                                    ->helperText('Kích thước tối ưu: 1200x675px (16:9), tối đa 2MB'),
                            ]),

                        Tabs\Tab::make('Cài đặt')
                            ->schema([
                                Toggle::make('is_featured')
                                    ->label('Bài viết nổi bật')
                                    ->helperText('Hiển thị ưu tiên trên trang chủ và danh sách'),
                            ]),

                        Tabs\Tab::make('SEO')
                            ->schema([
                                Section::make('Meta Tags')
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
                                            Toggle::make('no_index')->label('No Index'),
                                            Toggle::make('no_follow')->label('No Follow'),
                                        ]),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
