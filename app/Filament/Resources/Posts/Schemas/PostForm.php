<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Support\AdminFormValidation as V;
use App\Filament\Support\AdminRichEditor;
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
                                        ->rules(V::requiredText())
                                        ->validationMessages(V::messages())
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Str::slug($state))),
                                    TextInput::make('slug')
                                        ->label('Slug (URL)')
                                        ->required()
                                        ->rules(V::slug())
                                        ->validationMessages(V::slugMessages())
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
                                        ->rules(['required', 'in:news,event,fair,seminar'])
                                        ->validationMessages(V::messages())
                                        ->live(),
                                    Select::make('status')
                                        ->label('Trạng thái')
                                        ->options([
                                            'draft' => 'Nháp',
                                            'published' => 'Công khai',
                                            'archived' => 'Lưu trữ',
                                        ])
                                        ->default('draft')
                                        ->required()
                                        ->rules(['required', 'in:draft,published,archived'])
                                        ->validationMessages(V::messages()),
                                    Select::make('author_id')
                                        ->label('Tác giả')
                                        ->relationship('author', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->rules(['required', 'exists:users,id'])
                                        ->validationMessages(V::messages()),
                                ]),
                                Grid::make(2)->schema([
                                    Select::make('category_id')
                                        ->label('Danh mục')
                                        ->relationship('category', 'name', fn ($query) => $query->where('type', 'news'))
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->rules(['required', 'exists:categories,id'])
                                        ->validationMessages(V::messages()),
                                    DateTimePicker::make('published_at')
                                        ->label('Ngày xuất bản')
                                        ->rules(['nullable', 'date'])
                                        ->validationMessages(V::messages())
                                        ->helperText('Nếu để trống khi công khai, hệ thống sẽ dùng thời điểm lưu.')
                                        ->native(false)
                                        ->displayFormat('d/m/Y H:i'),
                                ]),
                                Textarea::make('excerpt')
                                    ->label('Mô tả tóm tắt')
                                    ->rows(3)
                                    ->rules(V::text(500))
                                    ->validationMessages(V::messages())
                                    ->maxLength(500)
                                    ->helperText('Hiển thị ở danh sách bài viết và SEO')
                                    ->columnSpanFull(),
                                AdminRichEditor::configure(
                                    RichEditor::make('content')
                                        ->label('Nội dung chi tiết')
                                        ->rules(V::richContent())
                                        ->validationMessages(V::messages()),
                                    'posts/content',
                                ),
                            ]),

                        Tabs\Tab::make('Sự kiện')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('event_date')
                                        ->label('Ngày diễn ra')
                                        ->type('date')
                                        ->rules(['nullable', 'date'])
                                        ->validationMessages(V::messages())
                                        ->helperText('Chỉ điền nếu là Hội chợ / Hội thảo'),
                                    TextInput::make('event_location')
                                        ->label('Địa điểm')
                                        ->required(fn (callable $get): bool => in_array($get('type'), ['event', 'fair', 'seminar'], true))
                                        ->rules(V::text())
                                        ->validationMessages(V::messages())
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
                                                ->image()
                                                ->imageEditor()
                                                ->directory('seo/og-images')
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
                                            Toggle::make('no_index')->label('Không cho công cụ tìm kiếm lập chỉ mục'),
                                            Toggle::make('no_follow')->label('Không theo dõi liên kết trên trang'),
                                        ]),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
