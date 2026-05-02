<?php

namespace App\Filament\Resources\QuoteTemplates\Tables;

use App\Support\FabStudioOptions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuoteTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => FabStudioOptions::QUOTE_TEMPLATE_TYPES[$state] ?? '-')
                    ->color('gray'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => FabStudioOptions::TEMPLATE_STATUSES[$state] ?? '-')
                    ->color(fn (?string $state): string => FabStudioOptions::statusColor($state)),
                TextColumn::make('currency')
                    ->label('Moneda'),
                TextColumn::make('default_valid_days')
                    ->label('Vigencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Actualizada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options(FabStudioOptions::QUOTE_TEMPLATE_TYPES),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(FabStudioOptions::TEMPLATE_STATUSES),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
