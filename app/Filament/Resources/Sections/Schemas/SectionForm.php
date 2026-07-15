<?php

namespace App\Filament\Resources\Sections\Schemas;

use App\Filament\Support\AdminFormValidation as V;
use App\Models\Product;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section as FormSection;
use Filament\Schemas\Schema;

class SectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            FormSection::make('Nội dung block')->schema([
                TextInput::make('key')->label('Mã block')->required()->alphaDash()->rules(['required', 'alpha_dash', 'max:100'])->validationMessages(V::messages())->maxLength(100)->unique(ignoreRecord: true),
                TextInput::make('title')->label('Tiêu đề')->rules(V::text())->validationMessages(V::messages())->maxLength(255),
                TextInput::make('subtitle')->label('Tiêu đề phụ')->rules(V::text())->validationMessages(V::messages())->maxLength(255),
                Select::make('type')->options([
                    'content' => 'Nội dung thường',
                    'hero' => 'Hero',
                    'feature' => 'Điểm nổi bật',
                    'cta' => 'CTA',
                    'banner' => 'Banner',
                    'grid' => 'Lưới nội dung',
                    'carousel' => 'Carousel',
                    'product_row' => 'Hàng danh mục và sản phẩm',
                ])->required()->rules(['required', 'in:content,hero,feature,cta,banner,grid,carousel,product_row'])->validationMessages(V::messages())->live(),
                Textarea::make('content')->label('Nội dung')->rules(V::text(5000))->validationMessages(V::messages())->columnSpanFull(),
                FileUpload::make('image')->label('Hình ảnh')->image()->imageEditor()->disk('public')->directory('sections')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(2048)
                    ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                TextInput::make('sort_order')->label('Thứ tự')->numeric()->rules(V::nonNegativeInteger())->validationMessages(V::messages())->minValue(0)->default(0),
                Checkbox::make('is_active')->label('Hiển thị')->default(true),
            ]),
            FormSection::make('Cấu hình giao diện')->schema([
                TextInput::make('container_class')->label('Lớp CSS khung nội dung')->rules(V::text())->validationMessages(V::messages())->maxLength(255),
                TextInput::make('bg_color')->label('Màu nền')->rules(V::hexColor())->validationMessages(V::hexColorMessages())->maxLength(20),
                TextInput::make('text_color')->label('Màu chữ')->rules(V::hexColor())->validationMessages(V::hexColorMessages())->maxLength(20),
                TextInput::make('spacing_top')->label('Khoảng cách phía trên')->rules(V::text(20))->validationMessages(V::messages())->maxLength(20),
                TextInput::make('spacing_bottom')->label('Khoảng cách phía dưới')->rules(V::text(20))->validationMessages(V::messages())->maxLength(20),
            ]),
            FormSection::make('Cấu hình row danh mục + sản phẩm')->schema([
                Select::make('style_config.product_ids')
                    ->label('Sản phẩm hiển thị')
                    ->options(fn () => Product::query()
                        ->where('status', 'published')
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all())
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->maxItems(8)
                    ->rules(['nullable', 'array', 'max:8'])
                    ->validationMessages(V::messages())
                    ->helperText('Chọn tối đa 8 sản phẩm. Thứ tự chọn sẽ được giữ khi hiển thị trên trang chủ.'),
                Grid::make(3)->schema([
                    TextInput::make('style_config.button_text')
                        ->label('Chữ nút')
                        ->default('Xem thêm')
                        ->rules(V::text(80))
                        ->validationMessages(V::messages())
                        ->maxLength(80),
                    TextInput::make('style_config.button_url')
                        ->label('Link nút')
                        ->placeholder('/san-pham')
                        ->rules(V::internalOrAbsoluteUrl())
                        ->validationMessages(V::urlMessages())
                        ->maxLength(500),
                    Toggle::make('style_config.show_button')
                        ->label('Hiển thị nút')
                        ->default(true),
                ]),
            ])
                ->visible(fn ($get): bool => $get('type') === 'product_row')
                ->columnSpanFull(),
        ]);
    }
}
