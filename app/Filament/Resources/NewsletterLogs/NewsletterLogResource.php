<?php

namespace App\Filament\Resources\NewsletterLogs;

use App\Filament\Resources\NewsletterLogs\Pages\ListNewsletterLogs;
use App\Filament\Resources\NewsletterLogs\Tables\NewsletterLogsTable;
use App\Models\NewsletterLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class NewsletterLogResource extends Resource
{
    protected static ?string $model = NewsletterLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;
    protected static ?string $navigationLabel = 'Nhật ký gửi email';
    protected static ?string $modelLabel = 'Nhật ký gửi email';
    protected static ?string $pluralModelLabel = 'Nhật ký gửi email';
    protected static string|UnitEnum|null $navigationGroup = 'Tiếp thị';

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return NewsletterLogsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['campaign.post', 'subscriber']);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsletterLogs::route('/'),
        ];
    }
}
