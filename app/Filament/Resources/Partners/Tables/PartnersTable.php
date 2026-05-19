<?php

namespace App\Filament\Resources\Partners\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PartnersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->disk('public')
                    ->circular()
                    ->size(40),
                TextColumn::make('name')
                    ->label('Tên đối tác')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('url')
                    ->label('Lien ket')
                    ->limit(30),
                TextColumn::make('sort_order')
                    ->label('Thứ tự')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Hoat dong')
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
