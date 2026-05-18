<?php

namespace App\Filament\Resources\NewsletterSubscribers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsletterSubscriberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Subscriber')->schema([
                Grid::make(2)->schema([
                    TextInput::make('email')->disabled(),
                    Select::make('status')->options([
                        'pending' => 'Pending',
                        'active' => 'Active',
                        'unsubscribed' => 'Unsubscribed',
                    ])->required(),
                    DateTimePicker::make('confirmed_at')->disabled(),
                    DateTimePicker::make('unsubscribed_at')->disabled(),
                ]),
            ]),
        ]);
    }
}
