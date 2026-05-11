<?php

namespace App\Filament\Resources\Menus\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                Select::make('location')
                    ->options([
                        'header' => 'Header',
                        'footer' => 'Footer',
                    ])
                    ->required(),
                TextInput::make('label')->required(),
                TextInput::make('url')->label('URL'),
                TextInput::make('route_name')->label('Route name'),
                Select::make('target')->options([
                    '_self' => 'Mở cùng tab',
                    '_blank' => 'Mở tab mới',
                ])->default('_self'),
                Select::make('parent_id')
                    ->label('Menu cha')
                    ->relationship('parent', 'label')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                TextInput::make('sort_order')->label('Thứ tự')->numeric()->default(0),
                TextInput::make('icon')->label('Icon'),
                TextInput::make('css_class')->label('CSS class'),
                Textarea::make('meta_config')->label('Meta JSON')->columnSpanFull(),
                Checkbox::make('is_active')->label('Hiển thị')->default(true),
            ]),
        ]);
    }
}
