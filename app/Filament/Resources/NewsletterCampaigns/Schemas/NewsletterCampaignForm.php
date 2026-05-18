<?php

namespace App\Filament\Resources\NewsletterCampaigns\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsletterCampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Campaign')->schema([
                Grid::make(2)->schema([
                    TextInput::make('post.title')->label('Post')->disabled(),
                    TextInput::make('subject')->disabled(),
                    DateTimePicker::make('queued_at')->disabled(),
                    DateTimePicker::make('sent_at')->disabled(),
                    TextInput::make('logs_count')->label('Total jobs')->disabled(),
                    TextInput::make('sent_logs_count')->label('Sent')->disabled(),
                    TextInput::make('failed_logs_count')->label('Failed')->disabled(),
                ]),
            ]),
        ]);
    }
}
