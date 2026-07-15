<?php

namespace App\Filament\Resources\Partners\Schemas;

use App\Filament\Support\AdminFormValidation as V;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tên đối tác')
                    ->required()
                    ->rules(V::requiredText())
                    ->validationMessages(V::messages())
                    ->maxLength(255),
                FileUpload::make('logo')
                    ->label('Logo')
                    ->image()->imageEditor()->disk('public')
                    ->directory('partners')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(1024)
                    ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values($state)[0] ?? null : $state)
                    ->required(),
                TextInput::make('url')
                    ->label('Link website')
                    ->url()
                    ->rules(['nullable', 'url', 'max:500'])
                    ->validationMessages(V::messages())
                    ->maxLength(500),
                TextInput::make('sort_order')
                    ->label('Thứ tự hiển thị')
                    ->numeric()
                    ->rules(V::nonNegativeInteger())
                    ->validationMessages(V::messages())
                    ->minValue(0)
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Hoạt động')
                    ->default(true),
            ]);
    }
}
