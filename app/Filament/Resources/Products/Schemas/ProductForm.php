<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Thông Tin Sản Phẩm')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('name')
                                        ->label('Tên sản phẩm')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($state, callable $set) =>
                                            $set('slug', \Str::slug($state))
                                        ),
                                    TextInput::make('slug')
                                        ->label('Slug (URL)')
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(255),
                                ]),
                                Grid::make(3)->schema([
                                    TextInput::make('sku')
                                        ->label('Mã SKU')
                                        ->maxLength(100),
                                    TextInput::make('price')
                                        ->label('Giá (VNĐ)')
                                        ->maxLength(50),
                                    Select::make('status')
                                        ->label('Trạng thái')
                                        ->options([
                                            'draft'     => '📝 Nháp',
                                            'published' => '✅ Công khai',
                                            'archived'  => '📦 Lưu trữ',
                                        ])
                                        ->default('draft')
                                        ->required(),
                                ]),
                                Grid::make(2)->schema([
                                    TextInput::make('brand')
                                        ->label('Thương hiệu')
                                        ->maxLength(100),
                                    TextInput::make('origin')
                                        ->label('Xuất xứ')
                                        ->maxLength(100),
                                ]),
                                Select::make('category_id')
                                    ->label('Danh mục')
                                    ->relationship('category', 'name', fn ($query) => $query->where('type', 'product'))
                                    ->searchable()
                                    ->preload(),
                                Textarea::make('short_description')
                                    ->label('Mô tả ngắn')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                RichEditor::make('description')
                                    ->label('Mô tả chi tiết')
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline', 'strike',
                                        'h2', 'h3', 'bulletList', 'orderedList',
                                        'link', 'blockquote', 'undo', 'redo',
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('Hình Ảnh')
                            ->schema([
                                FileUpload::make('thumbnail')
                                    ->label('Ảnh đại diện')
                                    ->image()
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('4:3')
                                    ->imageResizeTargetWidth('800')
                                    ->imageResizeTargetHeight('600')
                                    ->directory('products/thumbnails')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(2048)
                                    ->helperText('Kích thước tối ưu: 800x600px, tối đa 2MB'),
                                FileUpload::make('gallery')
                                    ->label('Bộ sưu tập ảnh')
                                    ->image()
                                    ->multiple()
                                    ->reorderable()
                                    ->directory('products/gallery')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(2048)
                                    ->maxFiles(10)
                                    ->helperText('Tối đa 10 ảnh, mỗi ảnh tối đa 2MB'),
                            ]),

                        Tabs\Tab::make('Thông Số Kỹ Thuật')
                            ->schema([
                                KeyValue::make('specifications')
                                    ->label('Thông số kỹ thuật')
                                    ->keyLabel('Thông số')
                                    ->valueLabel('Giá trị')
                                    ->addButtonLabel('+ Thêm thông số')
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('Cài Đặt')
                            ->schema([
                                Grid::make(3)->schema([
                                    Toggle::make('is_featured')
                                        ->label('Sản phẩm nổi bật')
                                        ->helperText('Hiển thị trên trang chủ'),
                                    Toggle::make('is_new')
                                        ->label('Sản phẩm mới')
                                        ->helperText('Đánh dấu badge "Mới"'),
                                ]),
                                TextInput::make('sort_order')
                                    ->label('Thứ tự sắp xếp')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),
                            ]),

                        Tabs\Tab::make('SEO')
                            ->schema([
                                Section::make('Meta Tags')
                                    ->relationship('seoMeta')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->maxLength(70)
                                            ->helperText('Tối ưu: 50-60 ký tự')
                                            ->live(debounce: 500)
                                            ->suffixAction(
                                                \Filament\Actions\Action::make('count')
                                                    ->icon('heroicon-o-calculator')
                                                    ->label(fn ($state) => strlen($state ?? '') . '/70')
                                                    ->disabled()
                                            ),
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
                                                ->directory('seo/og-images')
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
                                                ->label('No Index')
                                                ->helperText('Không cho phép Google lập chỉ mục'),
                                            Toggle::make('no_follow')
                                                ->label('No Follow')
                                                ->helperText('Không theo dõi các liên kết'),
                                        ]),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
