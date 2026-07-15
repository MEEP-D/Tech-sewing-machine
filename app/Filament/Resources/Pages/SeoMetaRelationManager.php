<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Support\AdminFormValidation as V;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;

class SeoMetaRelationManager extends RelationManager
{
    protected static string $relationship = 'seoMeta';

    protected static ?string $title = 'SEO Meta';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('SEO Preview')->schema([
                TextInput::make('preview_title')->disabled()->dehydrated(false),
                TextInput::make('preview_url')->disabled()->dehydrated(false),
                Textarea::make('preview_description')->disabled()->dehydrated(false),
            ]),
            Section::make('Meta Tags')->schema([
                Grid::make(2)->schema([
                    TextInput::make('meta_title')->label('Meta title')->rules(V::text(70))->validationMessages(V::messages())->maxLength(70),
                    TextInput::make('canonical_url')->label('Canonical URL')->url()->rules(['nullable', 'url', 'max:500'])->validationMessages(V::messages()),
                    TextInput::make('og_title')->label('OG title')->rules(V::text(95))->validationMessages(V::messages())->maxLength(95),
                    FileUpload::make('og_image')->label('OG image')->image()->imageEditor()->disk('public')->directory('seo/og-images')
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
                    Textarea::make('meta_description')->label('Meta description')->rules(V::text(160))->validationMessages(V::messages())->maxLength(160),
                    Textarea::make('og_description')->label('OG description')->rules(V::text(200))->validationMessages(V::messages())->maxLength(200),
                    TextInput::make('focus_keyword')->label('Focus keyword')->rules(V::text(100))->validationMessages(V::messages())->maxLength(100),
                    Checkbox::make('no_index')->label('No index'),
                    Checkbox::make('no_follow')->label('No follow'),
                ]),
            ]),
        ]);
    }
}
