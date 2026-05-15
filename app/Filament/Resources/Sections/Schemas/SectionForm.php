<?php

namespace App\Filament\Resources\Sections\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section as FormSection;
use Filament\Schemas\Schema;

class SectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            FormSection::make('Nội dung block')->schema([
                TextInput::make('key')->required(),
                TextInput::make('title'),
                TextInput::make('subtitle'),
                Select::make('type')->options([
                    'content' => 'Content',
                    'hero' => 'Hero',
                    'feature' => 'Feature',
                    'cta' => 'CTA',
                    'banner' => 'Banner',
                    'grid' => 'Grid',
                    'carousel' => 'Carousel',
                ])->required(),
                Textarea::make('content')->columnSpanFull(),
                FileUpload::make('image')->image()->disk('public')->directory('sections')
                    ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                TextInput::make('sort_order')->numeric()->default(0),
                Checkbox::make('is_active')->default(true),
            ]),
            FormSection::make('Style layout')->schema([
                TextInput::make('container_class')->label('Container class'),
                TextInput::make('bg_color')->label('Màu nền'),
                TextInput::make('text_color')->label('Màu chữ'),
                TextInput::make('spacing_top')->label('Spacing top'),
                TextInput::make('spacing_bottom')->label('Spacing bottom'),
            ]),
        ]);
    }
}

