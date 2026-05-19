<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                TextInput::make('key')->label('Khóa')->required()->alphaDash()->maxLength(100),
                TextInput::make('label')->label('Nhãn')->required()->maxLength(255),
                Select::make('group')->options([
                    'general' => 'General',
                    'seo' => 'SEO',
                    'branding' => 'Branding',
                    'homepage' => 'Homepage',
                    'theme' => 'Theme',
                    'navigation' => 'Navigation',
                ])->required(),
                Select::make('type')->options([
                    'text' => 'Text',
                    'textarea' => 'Textarea',
                    'image' => 'Image',
                    'boolean' => 'Boolean',
                    'json' => 'JSON',
                    'color' => 'Color',
                    'number' => 'Number',
                ])->required(),
                Textarea::make('value')->columnSpanFull(),
            ]),
        ]);
    }
}
