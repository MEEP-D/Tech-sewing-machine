<?php

namespace App\Filament\Resources\Partners\Schemas;

use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('name')
                    ->label('Tên đối tác')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->disk('public')
                    ->directory('partners')
                    ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state)
                    ->required(),
                \Filament\Forms\Components\TextInput::make('url')
                    ->label('Link website')
                    ->url()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('sort_order')
                    ->label('Thứ tự hiện thị')
                    ->numeric()
                    ->default(0),
                \Filament\Forms\Components\Toggle::make('is_active')
                    ->label('Hoạt động')
                    ->default(true),
            ]);
    }
}
