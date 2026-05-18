<?php

namespace App\Filament\Resources\NewsletterLogs\Pages;

use App\Filament\Resources\NewsletterLogs\NewsletterLogResource;
use Filament\Resources\Pages\ListRecords;

class ListNewsletterLogs extends ListRecords
{
    protected static string $resource = NewsletterLogResource::class;
}
