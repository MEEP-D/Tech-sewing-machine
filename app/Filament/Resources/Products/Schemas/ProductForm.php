<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

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
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Str::slug($state))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ]),
                    Grid::make(4)->schema([
                        TextInput::make('code')->label('Mã sản phẩm')->maxLength(100),
                        TextInput::make('sku')->label('SKU')->maxLength(100),
                        TextInput::make('video_id')->label('Youtube Video ID')->maxLength(100),
                        TextInput::make('price')->label('Giá')->maxLength(50),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('discount_percent')
                            ->label('Giảm giá (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0)
                            ->suffix('%'),
                        TextInput::make('installment_percent')
                            ->label('Trả góp (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0)
                            ->suffix('%'),
                    ]),
                    Grid::make(3)->schema([
                        TextInput::make('brand')->label('Thương hiệu')->maxLength(100),
                        TextInput::make('origin')->label('Xuất xứ')->maxLength(100),
                        Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'draft' => 'Nháp',
                                'published' => 'Công khai',
                                'archived' => 'Lưu trữ',
                            ])
                            ->default('draft')
                            ->required(),
                    ]),
                    Select::make('category_id')
                        ->label('Danh mục')
                        ->relationship('category', 'name', fn ($query) => $query->where('type', 'product'))
                        ->searchable()
                        ->preload(),
                    Textarea::make('short_description')->label('Mô tả ngắn')->rows(3)->columnSpanFull(),
                    Textarea::make('long_description')->label('Mô tả dài')->rows(4)->columnSpanFull(),
                    RichEditor::make('description')->label('Mô tả chi tiết')->columnSpanFull(),
                    Section::make('Nội dung bổ sung trang chi tiết')->schema([
                        Textarea::make('support_prompt')
                            ->label('Dòng hỗ trợ')
                            ->rows(3)
                            ->placeholder('Bạn cần hỗ trợ thông tin gì về sản phẩm này?'),
                        Grid::make(2)->schema([
                            TextInput::make('cta_primary_label')
                                ->label('Nút 1 - Tiêu đề')
                                ->placeholder('Bạn cần hỗ trợ thông tin gì về sản phẩm này?'),
                            TextInput::make('cta_primary_url')
                                ->label('Nút 1 - Link')
                                ->placeholder('/lien-he'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('cta_secondary_label')
                                ->label('Nút 2 - Tiêu đề')
                                ->placeholder('Khám phá các mẫu thêu miễn phí tại đây'),
                            TextInput::make('cta_secondary_url')
                                ->label('Nút 2 - Link')
                                ->placeholder('/trang/mau-theu'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('overview_heading')
                                ->label('Đầu mục 1')
                                ->placeholder('Tổng quan về sản phẩm'),
                            TextInput::make('seo_heading')
                                ->label('Đầu mục 2')
                                ->placeholder('Tìm hiểu về máy làm seo'),
                        ]),
                        RichEditor::make('overview_content')
                            ->label('Nội dung đầu mục 1')
                            ->columnSpanFull(),
                        RichEditor::make('seo_content')
                            ->label('Nội dung đầu mục 2')
                            ->columnSpanFull(),
                    ])->columnSpanFull(),
                ]),

                Tabs\Tab::make('Hình ảnh')->schema([
                    Placeholder::make('current_image_preview')
                        ->label('Ảnh hiện tại')
                        ->content(fn ($record) => $record?->display_image_url
                            ? new \Illuminate\Support\HtmlString('<img src="' . e($record->display_image_url) . '" alt="Product image" style="max-width:220px;border-radius:10px;border:1px solid #e2e8f0;padding:6px;background:#fff;" />')
                            : 'Chưa có ảnh'
                        ),
                    FileUpload::make('thumbnail')->label('Ảnh đại diện')->image()->imageEditor()->disk('public')->directory('products/thumbnails')
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    FileUpload::make('image')->label('Ảnh chính')->image()->imageEditor()->disk('public')->directory('products/images')
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    FileUpload::make('gallery')->label('Gallery')->image()->imageEditor()->multiple()->disk('public')->directory('products/gallery')
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state) : []),
                ]),

                Tabs\Tab::make('Thông số kỹ thuật')->schema([
                    KeyValue::make('specifications')
                        ->label('Thông số chung')
                        ->keyLabel('Thông số')
                        ->valueLabel('Giá trị')
                        ->columnSpanFull(),
                    Repeater::make('specs')
                        ->label('Thông số sản phẩm đột phá')
                        ->relationship()
                        ->orderColumn('sort_order')
                        ->schema([
                            TextInput::make('key')->label('Thông số')->required(),
                            TextInput::make('value')->label('Giá trị')->required(),
                            TextInput::make('sort_order')->label('Thứ tự')->numeric()->default(0),
                        ])
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ]),

                Tabs\Tab::make('Cài đặt')->schema([
                    Grid::make(4)->schema([
                        Toggle::make('is_featured')->label('Nổi bật'),
                        Toggle::make('is_new')->label('Mới'),
                        Toggle::make('is_hot')->label('Hot'),
                    ]),
                    TextInput::make('sort_order')->label('Thứ tự')->numeric()->default(0)->minValue(0),
                ]),

                Tabs\Tab::make('SEO')->schema([
                    Section::make('Meta')->relationship('seoMeta')->schema([
                        TextInput::make('meta_title')->label('Meta Title')->maxLength(70),
                        Textarea::make('meta_description')->label('Meta Description')->rows(3)->maxLength(160),
                        TextInput::make('focus_keyword')->label('Focus Keyword')->maxLength(100),
                    ]),
                ]),
            ])->columnSpanFull(),
        ]);
    }
}
