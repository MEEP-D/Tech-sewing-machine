<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Filament\Support\VietnameseAction;
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
                TextColumn::make('title')->label('Tiêu đề')->searchable(),
                TextColumn::make('slug')->label('Slug')->searchable(),
                ImageColumn::make('thumbnail')->label('Ảnh đại diện')->disk('public')->size(56),
                TextColumn::make('category.name')->label('Danh mục')->searchable(),
                TextColumn::make('author.name')->label('Tác giả')->searchable(),
                TextColumn::make('status')->label('Trạng thái')->badge(),
                TextColumn::make('type')->label('Loại')->badge(),
                TextColumn::make('published_at')->label('Ngày đăng')->dateTime()->sortable(),
                TextColumn::make('event_date')->label('Ngày sự kiện')->searchable(),
                TextColumn::make('event_location')->label('Địa điểm')->searchable(),
                IconColumn::make('is_featured')->label('Nổi bật')->boolean(),
                TextColumn::make('view_count')->label('Lượt xem')->numeric()->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label('Đã xóa lúc')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                VietnameseAction::edit(EditAction::make(), 'bài viết'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    VietnameseAction::deleteBulk(DeleteBulkAction::make(), 'bài viết'),
                    VietnameseAction::forceDeleteBulk(ForceDeleteBulkAction::make(), 'bài viết'),
                    VietnameseAction::restoreBulk(RestoreBulkAction::make(), 'bài viết'),
                ]),
            ]);
    }
}

