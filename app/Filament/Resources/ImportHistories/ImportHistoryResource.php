<?php

namespace App\Filament\Resources\ImportHistories;

use App\Filament\Resources\ImportHistories\Pages\ListImportHistories;
use App\Filament\Resources\ImportHistories\Pages\ViewImportHistory;
use App\Filament\Resources\ImportHistories\RelationManagers\FailedImportRowsRelationManager;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\Imports\Models\Import as ImportModel;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema as SchemaFacade;
use Illuminate\Support\Facades\URL;

class ImportHistoryResource extends Resource
{
    protected static ?string $model = ImportModel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUpTray;

    protected static ?string $navigationLabel = 'Lịch sử import';

    protected static ?string $modelLabel = 'Lịch sử import';

    protected static ?string $pluralModelLabel = 'Lịch sử import';

    protected static string|\UnitEnum|null $navigationGroup = 'Hệ thống';

    protected static ?int $navigationSort = 90;

    protected static bool $shouldSkipAuthorization = true;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('user')
            ->withCount('failedRows');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('file_name')
                    ->label('File import')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('importer')
                    ->label('Loại dữ liệu')
                    ->formatStateUsing(fn (?string $state): string => static::formatImporter($state))
                    ->badge()
                    ->color('info'),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->state(fn (ImportModel $record): string => static::statusLabel($record))
                    ->badge()
                    ->color(fn (ImportModel $record): string => static::statusColor($record)),
                TextColumn::make('processed_rows')
                    ->label('Đã xử lý')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('successful_rows')
                    ->label('Thành công')
                    ->numeric()
                    ->color('success')
                    ->sortable(),
                TextColumn::make('failed_rows_count')
                    ->label('Lỗi')
                    ->state(fn (ImportModel $record): int => static::getDisplayFailedRowsCount($record))
                    ->numeric()
                    ->color(fn (int $state): string => $state > 0 ? 'danger' : 'success')
                    ->sortable(),
                TextColumn::make('total_rows')
                    ->label('Tổng dòng')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Người import')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'processing' => 'Đang xử lý',
                        'completed' => 'Hoàn tất',
                        'completed_with_errors' => 'Hoàn tất có lỗi',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value'] ?? null) {
                            'processing' => $query->whereNull('completed_at'),
                            'completed' => $query->whereNotNull('completed_at')->doesntHave('failedRows'),
                            'completed_with_errors' => $query->whereNotNull('completed_at')->has('failedRows'),
                            default => $query,
                        };
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Chi tiết')
                    ->modalHeading(fn (ImportModel $record): string => 'Chi tiết import #' . $record->getKey())
                    ->modalWidth(Width::SevenExtraLarge)
                    ->slideOver()
                    ->modalContent(fn (ImportModel $record) => view('filament.import-export-history.import-detail', [
                        'record' => $record->loadMissing(['failedRows', 'user']),
                        'failedRowsCount' => static::getDisplayFailedRowsCount($record),
                        'pendingBatchJobsCount' => static::getPendingBatchJobsCount($record),
                        'failedJobs' => static::getRelatedFailedJobs($record),
                        'queueDriver' => (string) config('queue.default'),
                    ])),
                Action::make('downloadFailedRows')
                    ->label('Tải lỗi CSV')
                    ->icon(Heroicon::ArrowDownTray)
                    ->color('danger')
                    ->visible(fn (ImportModel $record): bool => static::getDisplayFailedRowsCount($record) > 0)
                    ->url(fn (ImportModel $record): string => URL::signedRoute('filament.imports.failed-rows.download', [
                        'authGuard' => Filament::getAuthGuard(),
                        'import' => $record,
                    ], absolute: false), shouldOpenInNewTab: true),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tổng quan import')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('file_name')->label('File import'),
                        TextEntry::make('importer')->label('Loại dữ liệu')->formatStateUsing(fn (?string $state): string => static::formatImporter($state)),
                        TextEntry::make('created_at')->label('Bắt đầu')->dateTime('d/m/Y H:i'),
                        TextEntry::make('completed_at')->label('Hoàn tất')->dateTime('d/m/Y H:i')->placeholder('Chưa hoàn tất'),
                        TextEntry::make('processed_rows')->label('Đã xử lý')->numeric(),
                        TextEntry::make('successful_rows')->label('Thành công')->numeric(),
                        TextEntry::make('total_rows')->label('Tổng dòng')->numeric(),
                        TextEntry::make('failed_rows_count')->label('Dòng lỗi')->state(fn (ImportModel $record): int => static::getDisplayFailedRowsCount($record)),
                        TextEntry::make('user.email')->label('Người import')->placeholder('-'),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            FailedImportRowsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListImportHistories::route('/'),
            'view' => ViewImportHistory::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function formatImporter(?string $importer): string
    {
        return match ($importer) {
            \App\Filament\Imports\ProductImporter::class => 'Sản phẩm',
            \App\Filament\Imports\PostImporter::class => 'Bài viết',
            default => class_basename((string) $importer),
        };
    }

    public static function statusLabel(ImportModel $record): string
    {
        if (blank($record->completed_at)) {
            return $record->processed_rows > 0 ? 'Đang xử lý' : 'Đang chờ xử lý';
        }

        return static::getDisplayFailedRowsCount($record) > 0 ? 'Hoàn tất có lỗi' : 'Hoàn tất';
    }

    public static function statusColor(ImportModel $record): string
    {
        if (blank($record->completed_at)) {
            return $record->processed_rows > 0 ? 'warning' : 'gray';
        }

        return static::getDisplayFailedRowsCount($record) > 0 ? 'danger' : 'success';
    }

    public static function getDisplayFailedRowsCount(ImportModel $record): int
    {
        if (isset($record->failed_rows_count)) {
            return (int) $record->failed_rows_count;
        }

        return $record->failedRows()->count();
    }

    public static function getPendingBatchJobsCount(ImportModel $record): int
    {
        if (! SchemaFacade::hasTable('job_batches')) {
            return 0;
        }

        $needle = 's:2:"id";i:' . $record->getKey() . ';';

        return (int) DB::table('job_batches')
            ->whereNull('finished_at')
            ->where('options', 'like', "%{$needle}%")
            ->sum('pending_jobs');
    }

    public static function getRelatedFailedJobs(ImportModel $record): Collection
    {
        if (! SchemaFacade::hasTable('failed_jobs')) {
            return collect();
        }

        $tag = 'import' . $record->getKey();

        return DB::table('failed_jobs')
            ->where('payload', 'like', "%{$tag}%")
            ->orWhere('exception', 'like', "%{$tag}%")
            ->latest('id')
            ->limit(5)
            ->get();
    }
}
