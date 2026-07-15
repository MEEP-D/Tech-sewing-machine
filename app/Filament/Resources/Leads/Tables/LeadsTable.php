<?php

namespace App\Filament\Resources\Leads\Tables;

use App\Filament\Support\VietnameseAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Họ tên')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Điện thoại')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('company')
                    ->label('Công ty')
                    ->toggleable(),
                TextColumn::make('interest')
                    ->label('Nhu cầu')
                    ->limit(28),
                BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'new' => 'Mới',
                        'contacted' => 'Đã liên hệ',
                        'qualified' => 'Đủ điều kiện',
                        'closed' => 'Đã đóng',
                        default => $state,
                    })
                    ->colors([
                        'danger' => 'new',
                        'warning' => 'contacted',
                        'success' => 'qualified',
                        'gray' => 'closed',
                    ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'new' => 'Mới',
                        'contacted' => 'Đã liên hệ',
                        'qualified' => 'Đủ điều kiện',
                        'closed' => 'Đã đóng',
                    ]),
            ])
            ->recordActions([
                VietnameseAction::edit(EditAction::make(), 'liên hệ'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    VietnameseAction::deleteBulk(DeleteBulkAction::make(), 'liên hệ'),
                ]),
            ]);
    }
}
