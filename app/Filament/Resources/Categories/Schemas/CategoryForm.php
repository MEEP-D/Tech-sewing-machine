<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Filament\Support\AdminFormValidation as V;
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
                                        ->rules(V::requiredText())
                                        ->validationMessages(V::messages())
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                    TextInput::make('slug')
                                        ->label('Slug (URL)')
                                        ->required()
                                        ->rules(V::slug())
                                        ->validationMessages(V::slugMessages())
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
                                        ->required()
                                        ->rules(['required', 'in:product,news'])
                                        ->validationMessages(V::messages()),
                                    Select::make('parent_id')
                                        ->label('Danh mục cha')
                                        ->relationship('parent', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->rules(['nullable', 'exists:categories,id'])
                                        ->validationMessages(V::messages()),
                                ]),
                                Textarea::make('description')
                                    ->label('Mô tả')
                                    ->rows(3)
                                    ->rules(V::text(1000))
                                    ->validationMessages(V::messages())
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
                                        ->rules(V::nonNegativeInteger())
                                        ->validationMessages(V::messages())
                                        ->minValue(0)
                                        ->default(0),
                                ]),
                                Toggle::make('highlight_mega_label')
                                    ->label('Làm nổi bật ở mega menu')
                                    ->helperText('Bật để danh mục cha hiển thị kiểu nút xanh chữ trắng trong mega menu Sản phẩm.')
                                    ->default(false),
                                Toggle::make('highlight_mega_blink')
                                    ->label('Nhấp nháy nổi bật ở mega menu')
                                    ->helperText('Dùng cùng màu với nút làm nổi bật ở mega menu nhưng thêm hiệu ứng nhấp nháy.')
                                    ->default(false),
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
                                                ->maxSize(1024),
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
