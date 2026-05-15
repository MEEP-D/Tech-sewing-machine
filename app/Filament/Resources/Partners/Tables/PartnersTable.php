<?php

namespace App\Filament\Resources\Partners\Tables;

use App\Models\Partner;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class PartnersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->getStateUsing(fn (Partner $record): ?string => $record->logo_url)
                    ->circular()
                    ->size(40),
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('Tên đối tác')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('url')
                    ->label('Link')
                    ->limit(30),
                \Filament\Tables\Columns\TextColumn::make('sort_order')
                    ->label('Thứ tự')
                    ->sortable(),
                \Filament\Tables\Columns\IconColumn::make('is_active')
                    ->label('Hoạt động')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
