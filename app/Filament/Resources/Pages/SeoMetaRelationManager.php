<?php

namespace App\Filament\Resources\Pages;

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
                    TextInput::make('meta_title')->label('Meta title')->maxLength(70),
                    TextInput::make('canonical_url')->label('Canonical URL')->url(),
                    TextInput::make('og_title')->label('OG title')->maxLength(95),
                    FileUpload::make('og_image')->label('OG image')->image()->directory('seo/og-images'),
                    Textarea::make('meta_description')->label('Meta description')->maxLength(160),
                    Textarea::make('og_description')->label('OG description')->maxLength(200),
                    TextInput::make('focus_keyword')->label('Focus keyword')->maxLength(100),
                    Checkbox::make('no_index')->label('No index'),
                    Checkbox::make('no_follow')->label('No follow'),
                ]),
            ]),
        ]);
    }
}
