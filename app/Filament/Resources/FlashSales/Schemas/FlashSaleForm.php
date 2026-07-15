<?php

namespace App\Filament\Resources\FlashSales\Schemas;

use App\Filament\Support\AdminFormValidation as V;
use App\Models\Product;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FlashSaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Thông tin chương trình')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('title')
                            ->label('Tiêu đề')
                            ->required()
                            ->default('Flash Sale')
                            ->rules(V::requiredText())
                            ->validationMessages(V::messages())
                            ->maxLength(255),
                        TextInput::make('subtitle')
                            ->label('Mô tả ngắn')
                            ->rules(V::text())
                            ->validationMessages(V::messages())
                            ->maxLength(255),
                    ]),
                    Grid::make(2)->schema([
                        DateTimePicker::make('starts_at')
                            ->label('Bắt đầu')
                            ->rules(['nullable', 'date'])
                            ->validationMessages(V::messages())
                            ->seconds(false)
                            ->native(false),
                        DateTimePicker::make('ends_at')
                            ->label('Kết thúc')
                            ->rules(['nullable', 'date'])
                            ->validationMessages(V::messages())
                            ->seconds(false)
                            ->native(false),
                    ]),
                    Grid::make(4)->schema([
                        TextInput::make('view_all_url')
                            ->label('Link xem tất cả')
                            ->placeholder('/san-pham')
                            ->rules(V::internalOrAbsoluteUrl())
                            ->validationMessages(V::urlMessages())
                            ->maxLength(500)
                            ->columnSpan(2),
                        TextInput::make('sort_order')
                            ->label('Thứ tự')
                            ->numeric()
                            ->rules(V::nonNegativeInteger())
                            ->validationMessages(V::messages())
                            ->minValue(0)
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Hiển thị')
                            ->default(true),
                    ]),
                    Toggle::make('show_countdown')
                        ->label('Hiển thị đồng hồ đếm ngược')
                        ->default(true),
                ]),

            Section::make('Sản phẩm flash sale')
                ->schema([
                    Repeater::make('items')
                        ->label('Danh sách sản phẩm')
                        ->relationship()
                        ->orderColumn('sort_order')
                        ->schema([
                            Select::make('product_id')
                                ->label('Sản phẩm')
                                ->relationship(
                                    'product',
                                    'name',
                                    fn ($query) => $query
                                        ->where('status', 'published')
                                        ->orderBy('name'),
                                )
                                ->searchable(['name', 'sku', 'code'])
                                ->getOptionLabelFromRecordUsing(fn (Product $record): string => trim(implode(' - ', array_filter([
                                    $record->name,
                                    $record->sku ?: $record->code,
                                ]))))
                                ->helperText('Gõ tên, SKU hoặc mã sản phẩm để tìm nhanh. Hệ thống chỉ hiển thị sản phẩm đang công khai.')
                                ->required()
                                ->rules(['required', 'exists:products,id'])
                                ->validationMessages(V::messages()),
                            Grid::make(3)->schema([
                                TextInput::make('sale_price')
                                    ->label('Giá flash sale')
                                    ->placeholder('12.000.000 đ')
                                    ->rules(V::text(100))
                                    ->validationMessages(V::messages())
                                    ->maxLength(100),
                                TextInput::make('discount_percent')
                                    ->label('Giảm (%)')
                                    ->numeric()
                                    ->rules(V::percentage())
                                    ->validationMessages(V::messages())
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->default(0)
                                    ->suffix('%'),
                                TextInput::make('badge_label')
                                    ->label('Badge góc ảnh')
                                    ->placeholder('Yêu thích+')
                                    ->rules(V::text(100))
                                    ->validationMessages(V::messages())
                                    ->maxLength(100),
                            ]),
                            Grid::make(4)->schema([
                                TextInput::make('status_label')
                                    ->label('Nhãn trạng thái')
                                    ->default('ĐANG BÁN CHẠY')
                                    ->rules(V::text(100))
                                    ->validationMessages(V::messages())
                                    ->maxLength(100),
                                TextInput::make('sort_order')
                                    ->label('Thứ tự')
                                    ->numeric()
                                    ->rules(V::nonNegativeInteger())
                                    ->validationMessages(V::messages())
                                    ->minValue(0)
                                    ->default(0),
                                Toggle::make('is_blinking')
                                    ->label('Tên nhấp nháy')
                                    ->default(true),
                                Toggle::make('is_active')
                                    ->label('Hiển thị')
                                    ->default(true),
                            ]),
                        ])
                        ->columns(1)
                        ->defaultItems(0)
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => isset($state['product_id'])
                            ? 'Sản phẩm #' . $state['product_id']
                            : 'Sản phẩm khuyến mãi nhanh')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
