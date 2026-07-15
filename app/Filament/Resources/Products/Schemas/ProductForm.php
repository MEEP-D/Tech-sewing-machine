<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Filament\Support\AdminFormValidation as V;
use App\Models\Product;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('tabs')->tabs([
                Tabs\Tab::make('Thông tin sản phẩm')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Tên sản phẩm')
                            ->required()
                            ->rules(V::requiredText())
                            ->validationMessages(V::messages())
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->rules(V::slug())
                            ->validationMessages(V::slugMessages())
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ]),
                    Grid::make(4)->schema([
                        TextInput::make('code')
                            ->label('Mã sản phẩm')
                            ->rules(V::text(100))
                            ->validationMessages(V::messages())
                            ->maxLength(100)
                            ->unique(ignoreRecord: true),
                        TextInput::make('sku')
                            ->label('SKU')
                            ->rules(V::text(100))
                            ->validationMessages(V::messages())
                            ->maxLength(100)
                            ->unique(ignoreRecord: true),
                        TextInput::make('video_id')
                            ->label('Video sản phẩm (YouTube)')
                            ->helperText('Dán link YouTube đầy đủ hoặc video ID, hệ thống sẽ tự chuẩn hóa.')
                            ->placeholder('https://www.youtube.com/watch?v=...')
                            ->rules(V::text(100))
                            ->validationMessages(V::messages())
                            ->maxLength(100)
                            ->dehydrateStateUsing(fn ($state) => Product::extractYoutubeId($state)),
                        TextInput::make('price')
                            ->label('Giá')
                            ->placeholder('Liên hệ hoặc 120.000.000')
                            ->rules(V::text(50))
                            ->validationMessages(V::messages())
                            ->maxLength(50),
                    ]),
                    Grid::make(3)->schema([
                        TextInput::make('discount_percent')
                            ->label('Giảm giá (%)')
                            ->numeric()
                            ->rules(V::percentage())
                            ->validationMessages(V::messages())
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0)
                            ->suffix('%'),
                        Toggle::make('installment_percent')
                            ->label('Khuyến mãi')
                            ->helperText('Bật để hiển thị badge Khuyến mãi trên sản phẩm.')
                            ->live()
                            ->default(false),
                        Select::make('availability_badge')
                            ->label('Thẻ nổi bật')
                            ->options(Product::availabilityBadgeOptions())
                            ->placeholder('Không gắn thẻ')
                            ->native(false)
                            ->helperText('Hiển thị thêm nhãn Giao ngay hoặc Đặt trước dưới giá ở trang chi tiết sản phẩm.'),
                    ]),
                    Grid::make(3)->schema([
                        TextInput::make('brand')->label('Thương hiệu')->rules(V::text(100))->validationMessages(V::messages())->maxLength(100),
                        TextInput::make('origin')->label('Xuất xứ')->rules(V::text(100))->validationMessages(V::messages())->maxLength(100),
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
                    ]),
                    Select::make('category_id')
                        ->label('Danh mục')
                        ->relationship('category', 'name', fn ($query) => $query->where('type', 'product'))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->rules(['required', 'exists:categories,id'])
                        ->validationMessages(V::messages()),
                    Textarea::make('short_description')->label('Mô tả ngắn')->rows(3)->rules(V::text(500))->validationMessages(V::messages())->maxLength(500)->columnSpanFull(),
                    Textarea::make('long_description')->label('Mô tả dài')->rows(4)->rules(V::text(2000))->validationMessages(V::messages())->maxLength(2000)->columnSpanFull(),
                    RichEditor::make('description')->label('Mô tả chi tiết')->rules(['nullable', 'string', 'max:50000'])->validationMessages(V::messages())->columnSpanFull(),
                    Section::make('Khuyến mãi theo sản phẩm')
                        ->description('Nhập nội dung quà tặng hoặc ưu đãi sẽ hiển thị khi khách bấm hoặc di chuột vào badge Khuyến mãi.')
                        ->visible(fn (callable $get): bool => (bool) $get('installment_percent'))
                        ->schema([
                            TextInput::make('promotion_title')
                                ->label('Tiêu đề khuyến mãi')
                                ->rules(V::text())
                                ->validationMessages(V::messages())
                                ->maxLength(255)
                                ->placeholder('Quà tặng kèm'),
                            TextInput::make('promotion_gift_name')
                                ->label('Tên quà tặng')
                                ->rules(V::text())
                                ->validationMessages(V::messages())
                                ->maxLength(255)
                                ->placeholder('1 iPhone'),
                            Textarea::make('promotion_description')
                                ->label('Nội dung khuyến mãi')
                                ->rules(V::text(1000))
                                ->validationMessages(V::messages())
                                ->rows(3)
                                ->placeholder('Mua máy lấy dấu tự động - tặng 1 iPhone'),
                            FileUpload::make('promotion_gift_image')
                                ->label('Ảnh quà tặng')
                                ->image()
                                ->imageEditor()
                                ->disk('public')
                                ->directory('products/promotions')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->maxSize(3072)
                                ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                            Grid::make(2)->schema([
                                DateTimePicker::make('promotion_starts_at')
                                    ->label('Bắt đầu khuyến mãi')
                                    ->rules(['nullable', 'date'])
                                    ->validationMessages(V::messages())
                                    ->seconds(false)
                                    ->native(false),
                                DateTimePicker::make('promotion_ends_at')
                                    ->label('Kết thúc khuyến mãi')
                                    ->rules(['nullable', 'date'])
                                    ->validationMessages(V::messages())
                                    ->seconds(false)
                                    ->native(false)
                                    ->helperText('Để trống nếu không giới hạn thời gian kết thúc.'),
                            ]),
                        ])
                        ->columns(2)
                        ->columnSpanFull(),
                    Section::make('Nội dung bổ sung trang chi tiết')->schema([
                        Textarea::make('support_prompt')
                            ->label('Dòng hỗ trợ')
                            ->rules(V::text(1000))
                            ->validationMessages(V::messages())
                            ->rows(3)
                            ->placeholder('Bạn cần hỗ trợ thông tin gì về sản phẩm này?'),
                        Grid::make(2)->schema([
                            TextInput::make('cta_primary_label')
                                ->label('Nút 1 - Tiêu đề')
                                ->rules(V::text(120))
                                ->validationMessages(V::messages())
                                ->maxLength(120)
                                ->placeholder('Bạn cần hỗ trợ thông tin gì về sản phẩm này?'),
                            TextInput::make('cta_primary_url')
                                ->label('Nút 1 - Link')
                                ->rules(V::internalOrAbsoluteUrl())
                                ->validationMessages(V::urlMessages())
                                ->maxLength(500)
                                ->placeholder('/lien-he'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('cta_secondary_label')
                                ->label('Nút 2 - Tiêu đề')
                                ->rules(V::text(120))
                                ->validationMessages(V::messages())
                                ->maxLength(120)
                                ->placeholder('Khám phá các mẫu thêu miễn phí tại đây'),
                            TextInput::make('cta_secondary_url')
                                ->label('Nút 2 - Link')
                                ->rules(V::internalOrAbsoluteUrl())
                                ->validationMessages(V::urlMessages())
                                ->maxLength(500)
                                ->placeholder('/trang/mau-theu'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('overview_heading')
                                ->label('Đầu mục 1')
                                ->rules(V::text(160))
                                ->validationMessages(V::messages())
                                ->maxLength(160)
                                ->placeholder('Tổng quan về sản phẩm'),
                            TextInput::make('seo_heading')
                                ->label('Đầu mục 2')
                                ->rules(V::text(160))
                                ->validationMessages(V::messages())
                                ->maxLength(160)
                                ->placeholder('Tìm hiểu về máy làm seo'),
                        ]),
                        RichEditor::make('overview_content')
                            ->label('Nội dung đầu mục 1')
                            ->rules(['nullable', 'string', 'max:50000'])
                            ->validationMessages(V::messages())
                            ->columnSpanFull(),
                        RichEditor::make('seo_content')
                            ->label('Nội dung đầu mục 2')
                            ->rules(['nullable', 'string', 'max:50000'])
                            ->validationMessages(V::messages())
                            ->columnSpanFull(),
                        Section::make('Hướng dẫn sử dụng')
                            ->description('Dùng cho tab Hướng dẫn sử dụng ở trang chi tiết sản phẩm.')
                            ->schema([
                                RichEditor::make('usage_guide_content')
                                    ->label('Nội dung hướng dẫn sử dụng')
                                    ->rules(['nullable', 'string', 'max:50000'])
                                    ->validationMessages(V::messages())
                                    ->columnSpanFull(),
                                TextInput::make('usage_guide_video_id')
                                    ->label('Video hướng dẫn (YouTube)')
                                    ->rules(V::text(100))
                                    ->validationMessages(V::messages())
                                    ->helperText('Dán link YouTube đầy đủ hoặc video ID.')
                                    ->placeholder('https://www.youtube.com/watch?v=...')
                                    ->maxLength(100)
                                    ->dehydrateStateUsing(fn ($state) => Product::extractYoutubeId($state)),
                                FileUpload::make('usage_guide_attachment')
                                    ->label('Tài liệu hướng dẫn')
                                    ->helperText('Tải lên file PDF, XLS, XLSX hoặc CSV để hiển thị hoặc cho tải xuống ở tab hướng dẫn.')
                                    ->disk('public')
                                    ->directory('products/guides')
                                    ->acceptedFileTypes([
                                        'application/pdf',
                                        'application/vnd.ms-excel',
                                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                        'text/csv',
                                        'text/plain',
                                    ])
                                    ->maxSize(10240)
                                    ->downloadable()
                                    ->openable()
                                    ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                            ])
                            ->columns(1)
                            ->columnSpanFull(),
                    ])->columnSpanFull(),
                ]),

                Tabs\Tab::make('Hình ảnh')->schema([
                    Placeholder::make('current_image_preview')
                        ->label('Ảnh hiện tại')
                        ->content(fn ($record) => $record?->display_image_url
                            ? new HtmlString('<img src="' . e($record->display_image_url) . '" alt="Product image" style="max-width:220px;border-radius:10px;border:1px solid #e2e8f0;padding:6px;background:#fff;" />')
                            : 'Chưa có ảnh'
                        ),
                    FileUpload::make('thumbnail')
                        ->label('Ảnh đại diện')
                        ->image()
                        ->imageEditor()
                        ->disk('public')
                        ->directory('products/thumbnails')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(2048)
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    FileUpload::make('image')
                        ->label('Ảnh chính')
                        ->image()
                        ->imageEditor()
                        ->disk('public')
                        ->directory('products/images')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(3072)
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    FileUpload::make('gallery')
                        ->label('Bộ sưu tập ảnh')
                        ->image()
                        ->imageEditor()
                        ->multiple()
                        ->disk('public')
                        ->directory('products/gallery')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxFiles(10)
                        ->maxSize(3072)
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state) : []),
                ]),

                Tabs\Tab::make('Thông số kỹ thuật')->schema([
                    KeyValue::make('specifications')
                        ->label('Thông số chung')
                        ->keyLabel('Thông số')
                        ->valueLabel('Giá trị')
                        ->rules(['nullable', 'array'])
                        ->validationMessages(V::messages())
                        ->columnSpanFull(),
                    Repeater::make('specs')
                        ->label('Thông số sản phẩm đột phá')
                        ->relationship()
                        ->orderColumn('sort_order')
                        ->schema([
                            TextInput::make('key')->label('Thông số')->required()->rules(V::requiredText())->validationMessages(V::messages()),
                            TextInput::make('value')->label('Giá trị')->required()->rules(V::requiredText())->validationMessages(V::messages()),
                            TextInput::make('sort_order')->label('Thứ tự')->numeric()->rules(V::nonNegativeInteger())->validationMessages(V::messages())->minValue(0)->default(0),
                        ])
                        ->defaultItems(0)
                        ->columnSpanFull(),
                    FileUpload::make('specification_images')
                        ->label('Ảnh bên dưới thông số kỹ thuật')
                        ->helperText('Các ảnh này sẽ hiển thị ngay bên dưới bảng thông số kỹ thuật ở trang chi tiết sản phẩm.')
                        ->image()
                        ->imageEditor()
                        ->multiple()
                        ->disk('public')
                        ->directory('products/specification-images')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxFiles(8)
                        ->maxSize(3072)
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state) : [])
                        ->columnSpanFull(),
                ]),

                Tabs\Tab::make('Cài đặt')->schema([
                    Grid::make(5)->schema([
                        Toggle::make('is_featured')->label('Nổi bật'),
                        Toggle::make('is_new')->label('Mới'),
                        Toggle::make('is_hot')->label('Hot'),
                        Toggle::make('is_exclusive')->label('Sản phẩm đột phá'),
                        Toggle::make('show_in_banner_switcher')->label('Hiển thị banner switcher'),
                    ]),
                    TextInput::make('sort_order')->label('Thứ tự')->numeric()->rules(V::nonNegativeInteger())->validationMessages(V::messages())->default(0)->minValue(0),
                ]),

                Tabs\Tab::make('SEO')->schema([
                    Section::make('Thẻ SEO')->relationship('seoMeta')->schema([
                        TextInput::make('meta_title')->label('Tiêu đề SEO')->rules(V::text(70))->validationMessages(V::messages())->maxLength(70),
                        Textarea::make('meta_description')->label('Mô tả SEO')->rows(3)->rules(V::text(160))->validationMessages(V::messages())->maxLength(160),
                        TextInput::make('focus_keyword')->label('Từ khóa trọng tâm')->rules(V::text(100))->validationMessages(V::messages())->maxLength(100),
                    ]),
                ]),
            ])->columnSpanFull(),
        ]);
    }
}
