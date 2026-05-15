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
                Tabs\Tab::make('Thong tin san pham')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Ten san pham')
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
                        TextInput::make('code')->label('Ma san pham')->maxLength(100),
                        TextInput::make('sku')->label('SKU')->maxLength(100),
                        TextInput::make('video_id')->label('Youtube Video ID')->maxLength(100),
                        TextInput::make('price')->label('Gia')->maxLength(50),
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
                        ->preload(),
                    Textarea::make('short_description')->label('Mo ta ngan')->rows(3)->columnSpanFull(),
                    Textarea::make('long_description')->label('Mo ta dai')->rows(4)->columnSpanFull(),
                    RichEditor::make('description')->label('Mo ta chi tiet')->columnSpanFull(),
                    Section::make('Noi dung bo sung trang chi tiet')->schema([
                        Textarea::make('support_prompt')
                            ->label('Dong ho tro')
                            ->rows(3)
                            ->placeholder('Ban can ho tro thong tin gi ve san pham nay?'),
                        Grid::make(2)->schema([
                            TextInput::make('cta_primary_label')
                                ->label('Nut 1 - Tieu de')
                                ->placeholder('Ban can ho tro thong tin gi ve san pham nay?'),
                            TextInput::make('cta_primary_url')
                                ->label('Nut 1 - Link')
                                ->placeholder('/lien-he'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('cta_secondary_label')
                                ->label('Nut 2 - Tieu de')
                                ->placeholder('Kham pha cac mau theu mien phi tai day'),
                            TextInput::make('cta_secondary_url')
                                ->label('Nut 2 - Link')
                                ->placeholder('/trang/mau-theu'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('overview_heading')
                                ->label('Dau muc 1')
                                ->placeholder('Tong quan ve san pham'),
                            TextInput::make('seo_heading')
                                ->label('Dau muc 2')
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
                            ? new \Illuminate\Support\HtmlString('<img src="' . e($record->display_image_url) . '" alt="Product image" style="max-width:220px;border-radius:10px;border:1px solid #e2e8f0;padding:6px;background:#fff;" />')
                            : 'Chua co anh'
                        ),
                    FileUpload::make('thumbnail')->label('Anh dai dien')->image()->disk('public')->directory('products/thumbnails')
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    FileUpload::make('image')->label('Anh chinh')->image()->disk('public')->directory('products/images')
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    FileUpload::make('gallery')->label('Gallery')->image()->multiple()->disk('public')->directory('products/gallery')
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
                            TextInput::make('sort_order')->label('Thu tu')->numeric()->default(0),
                        ])
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ]),

                Tabs\Tab::make('Cai dat')->schema([
                    Grid::make(4)->schema([
                        Toggle::make('is_featured')->label('Noi bat'),
                        Toggle::make('is_new')->label('Moi'),
                        Toggle::make('is_hot')->label('Hot'),
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
