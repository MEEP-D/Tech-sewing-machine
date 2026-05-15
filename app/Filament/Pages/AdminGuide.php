<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;

class AdminGuide extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Hướng dẫn Admin';

    protected static ?string $title = 'Hướng dẫn sử dụng Admin';

    protected static string|\UnitEnum|null $navigationGroup = 'Hệ thống';

    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.pages.admin-guide';
}
