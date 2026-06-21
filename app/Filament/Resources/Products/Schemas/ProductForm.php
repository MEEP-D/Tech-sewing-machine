<?php

namespace App\Filament\Resources\Products\Schemas;

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
                Tabs\Tab::make('Thong tin san pham')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Ten san pham')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ]),
                    Grid::make(4)->schema([
                        TextInput::make('code')
                            ->label('Ma san pham')
                            ->maxLength(100)
                            ->unique(ignoreRecord: true),
                        TextInput::make('sku')
                            ->label('SKU')
                            ->maxLength(100)
                            ->unique(ignoreRecord: true),
                        TextInput::make('video_id')->label('Youtube Video ID')->maxLength(100),
                        TextInput::make('price')
                            ->label('Gia')
                            ->placeholder('Lien he hoac 120.000.000')
                            ->maxLength(50),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('discount_percent')
                            ->label('Giam gia (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0)
                            ->suffix('%'),
                        TextInput::make('installment_percent')
                            ->label('Tra gop (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0)
                            ->suffix('%'),
                    ]),
                    Grid::make(3)->schema([
                        TextInput::make('brand')->label('Thuong hieu')->maxLength(100),
                        TextInput::make('origin')->label('Xuat xu')->maxLength(100),
                        Select::make('status')
                            ->label('Trang thai')
                            ->options([
                                'draft' => 'Nhap',
                                'published' => 'Cong khai',
                                'archived' => 'Luu tru',
                            ])
                            ->default('draft')
                            ->required(),
                    ]),
                    Select::make('category_id')
                        ->label('Danh muc')
                        ->relationship('category', 'name', fn ($query) => $query->where('type', 'product'))
                        ->searchable()
                        ->preload()
                        ->required(),
                    Textarea::make('short_description')->label('Mo ta ngan')->rows(3)->maxLength(500)->columnSpanFull(),
                    Textarea::make('long_description')->label('Mo ta dai')->rows(4)->maxLength(2000)->columnSpanFull(),
                    RichEditor::make('description')->label('Mo ta chi tiet')->columnSpanFull(),
                    Section::make('Noi dung bo sung trang chi tiet')->schema([
                        Textarea::make('support_prompt')
                            ->label('Dong ho tro')
                            ->rows(3)
                            ->placeholder('Ban can ho tro thong tin gi ve san pham nay?'),
                        Grid::make(2)->schema([
                            TextInput::make('cta_primary_label')
                                ->label('Nut 1 - Tieu de')
                                ->maxLength(120)
                                ->placeholder('Ban can ho tro thong tin gi ve san pham nay?'),
                            TextInput::make('cta_primary_url')
                                ->label('Nut 1 - Link')
                                ->maxLength(500)
                                ->rules(['nullable', 'regex:/^(\\/.*|https?:\\/\\/.+)$/i'])
                                ->validationMessages([
                                    'regex' => 'Link phai la URL day du (https://...) hoac duong dan noi bo bat dau bang "/".',
                                ])
                                ->placeholder('/lien-he'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('cta_secondary_label')
                                ->label('Nut 2 - Tieu de')
                                ->maxLength(120)
                                ->placeholder('Kham pha cac mau theu mien phi tai day'),
                            TextInput::make('cta_secondary_url')
                                ->label('Nut 2 - Link')
                                ->maxLength(500)
                                ->rules(['nullable', 'regex:/^(\\/.*|https?:\\/\\/.+)$/i'])
                                ->validationMessages([
                                    'regex' => 'Link phai la URL day du (https://...) hoac duong dan noi bo bat dau bang "/".',
                                ])
                                ->placeholder('/trang/mau-theu'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('overview_heading')
                                ->label('Dau muc 1')
                                ->maxLength(160)
                                ->placeholder('Tong quan ve san pham'),
                            TextInput::make('seo_heading')
                                ->label('Dau muc 2')
                                ->maxLength(160)
                                ->placeholder('Tim hieu ve may lam seo'),
                        ]),
                        RichEditor::make('overview_content')
                            ->label('Noi dung dau muc 1')
                            ->columnSpanFull(),
                        RichEditor::make('seo_content')
                            ->label('Noi dung dau muc 2')
                            ->columnSpanFull(),
                    ])->columnSpanFull(),
                ]),

                Tabs\Tab::make('Hinh anh')->schema([
                    Placeholder::make('current_image_preview')
                        ->label('Anh hien tai')
                        ->content(fn ($record) => $record?->display_image_url
                            ? new HtmlString('<img src="' . e($record->display_image_url) . '" alt="Product image" style="max-width:220px;border-radius:10px;border:1px solid #e2e8f0;padding:6px;background:#fff;" />')
                            : 'Chua co anh'
                        ),
                    FileUpload::make('thumbnail')
                        ->label('Anh dai dien')
                        ->image()
                        ->imageEditor()
                        ->disk('public')
                        ->directory('products/thumbnails')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(2048)
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    FileUpload::make('image')
                        ->label('Anh chinh')
                        ->image()
                        ->imageEditor()
                        ->disk('public')
                        ->directory('products/images')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(3072)
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    FileUpload::make('gallery')
                        ->label('Gallery')
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

                Tabs\Tab::make('Thong so ky thuat')->schema([
                    KeyValue::make('specifications')
                        ->label('Thong so chung')
                        ->keyLabel('Thong so')
                        ->valueLabel('Gia tri')
                        ->columnSpanFull(),
                    Repeater::make('specs')
                        ->label('Thong so san pham dot pha')
                        ->relationship()
                        ->orderColumn('sort_order')
                        ->schema([
                            TextInput::make('key')->label('Thong so')->required(),
                            TextInput::make('value')->label('Gia tri')->required(),
                            TextInput::make('sort_order')->label('Thu tu')->numeric()->minValue(0)->default(0),
                        ])
                        ->defaultItems(0)
                        ->columnSpanFull(),
                    FileUpload::make('specification_images')
                        ->label('Anh ben duoi thong so ky thuat')
                        ->helperText('Cac anh nay se hien thi ngay ben duoi bang thong so ky thuat o trang chi tiet san pham.')
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

                Tabs\Tab::make('Cai dat')->schema([
                    Grid::make(5)->schema([
                        Toggle::make('is_featured')->label('Noi bat'),
                        Toggle::make('is_new')->label('Moi'),
                        Toggle::make('is_hot')->label('Hot'),
                        Toggle::make('is_exclusive')->label('San pham dot pha'),
                        Toggle::make('show_in_banner_switcher')->label('Hien thi banner switcher'),
                    ]),
                    TextInput::make('sort_order')->label('Thu tu')->numeric()->default(0)->minValue(0),
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
