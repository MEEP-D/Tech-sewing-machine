<?php

namespace App\Filament\Resources\Sections\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section as FormSection;
use Filament\Schemas\Schema;

class SectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            FormSection::make('Nội dung block')->schema([
                TextInput::make('key')->label('Mã block')->required()->alphaDash()->maxLength(100),
                TextInput::make('title')->label('Tiêu đề')->maxLength(255),
                TextInput::make('subtitle')->label('Tiêu đề phụ')->maxLength(255),
                Select::make('type')->options([
                    'content' => 'Content',
                    'hero' => 'Hero',
                    'feature' => 'Feature',
                    'cta' => 'CTA',
                    'banner' => 'Banner',
                    'grid' => 'Grid',
                    'carousel' => 'Carousel',
                ])->required(),
                Textarea::make('content')->label('Nội dung')->columnSpanFull(),
                FileUpload::make('image')->label('Hình ảnh')->image()->imageEditor()->disk('public')->directory('sections')
                    ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                TextInput::make('sort_order')->label('Thứ tự')->numeric()->minValue(0)->default(0),
                Checkbox::make('is_active')->label('Hiển thị')->default(true),
            ]),
            FormSection::make('Cấu hình giao diện')->schema([
                TextInput::make('container_class')->label('Container class')->maxLength(255),
                TextInput::make('bg_color')->label('Màu nền')->maxLength(20),
                TextInput::make('text_color')->label('Màu chữ')->maxLength(20),
                TextInput::make('spacing_top')->label('Spacing top')->maxLength(20),
                TextInput::make('spacing_bottom')->label('Spacing bottom')->maxLength(20),
            ]),
        ]);
    }
}
