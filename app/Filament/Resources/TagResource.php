<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagResource\Pages;
use App\Models\Tag;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;
    protected static ?string $navigationLabel = 'Từ khóa (Tags)';
    protected static ?string $modelLabel = 'Từ khóa';
    protected static ?string $pluralModelLabel = 'Từ khóa';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->label('Tên từ khóa')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug (URL)')
                    ->required()
                    ->maxLength(255)
                    ->unique(Tag::class, 'slug', ignoreRecord: true),
                Forms\Components\Select::make('type')
                    ->label('Loại')
                    ->options([
                        'product' => 'Sản phẩm',
                        'news' => 'Tin tức',
                    ])
                    ->default('product')
                    ->required()
                    ->dehydrated(fn ($state) => filled($state)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Tên')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->label('Slug'),
                Tables\Columns\TextColumn::make('type')->label('Loại')->badge()->color(fn (string $state): string => match ($state) {
                    'product' => 'success',
                    'news' => 'info',
                    default => 'gray',
                }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'product' => 'Sản phẩm',
                        'news' => 'Tin tức',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Xác nhận xóa từ khóa')
                    ->modalDescription('Bạn có chắc chắn muốn xóa từ khóa này không?'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Xác nhận xóa các từ khóa')
                        ->modalDescription('Bạn có chắc chắn muốn xóa các từ khóa đã chọn không?'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTags::route('/'),
        ];
    }
}
