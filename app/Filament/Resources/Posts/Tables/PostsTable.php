<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Tieu de')->searchable(),
                TextColumn::make('slug')->label('Slug')->searchable(),
                ImageColumn::make('thumbnail')->label('Anh dai dien')->disk('public')->size(56),
                TextColumn::make('category.name')->label('Danh muc')->searchable(),
                TextColumn::make('author.name')->label('Tac gia')->searchable(),
                TextColumn::make('status')->label('Trang thai')->badge(),
                TextColumn::make('type')->label('Loai')->badge(),
                TextColumn::make('published_at')->label('Ngay dang')->dateTime()->sortable(),
                TextColumn::make('event_date')->label('Ngay su kien')->searchable(),
                TextColumn::make('event_location')->label('Dia diem')->searchable(),
                IconColumn::make('is_featured')->label('Noi bat')->boolean(),
                TextColumn::make('view_count')->label('Luot xem')->numeric()->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngay tao')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Cap nhat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label('Da xoa luc')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}

