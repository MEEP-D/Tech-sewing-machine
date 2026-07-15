<?php

namespace App\Filament\Resources\NewsletterSubscribers\Tables;

use App\Filament\Support\VietnameseAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NewsletterSubscribersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('status')->label('Trạng thái')->badge()->sortable(),
                TextColumn::make('confirmed_at')->label('Xác nhận lúc')->dateTime('d/m/Y H:i')->toggleable(),
                TextColumn::make('unsubscribed_at')->label('Hủy đăng ký lúc')->dateTime('d/m/Y H:i')->toggleable(),
                TextColumn::make('created_at')->label('Đăng ký lúc')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Trạng thái')->options([
                    'pending' => 'Chờ xác nhận',
                    'active' => 'Đang nhận',
                    'unsubscribed' => 'Đã hủy',
                ]),
            ])
            ->recordActions([
                VietnameseAction::edit(EditAction::make(), 'người nhận email'),
            ]);
    }
}
