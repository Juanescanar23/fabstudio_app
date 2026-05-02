<?php

namespace App\Filament\Resources\QuoteVersions\Tables;

use App\Support\FabStudioOptions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuoteVersionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('quote.title')
                    ->label('Cotización')
                    ->searchable(),
                TextColumn::make('template.name')
                    ->label('Plantilla')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('createdBy.name')
                    ->label('Creada por')
                    ->searchable(),
                TextColumn::make('reviewedBy.name')
                    ->label('Revisada por')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('approvedBy.name')
                    ->label('Aprobada por')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('version_number')
                    ->label('Número de versión')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => FabStudioOptions::QUOTE_STATUSES[$state] ?? '-')
                    ->color(fn (?string $state): string => FabStudioOptions::statusColor($state))
                    ->searchable(),
                TextColumn::make('ai_model')
                    ->label('Modelo IA')
                    ->searchable(),
                TextColumn::make('ai_prompt_hash')
                    ->label('Hash del prompt IA')
                    ->searchable(),
                TextColumn::make('pdf_path')
                    ->label('PDF')
                    ->searchable(),
                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tax')
                    ->label('Impuestos')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('discount')
                    ->label('Descuento')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->label('Total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reviewed_at')
                    ->label('Revisada el')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('approved_at')
                    ->label('Aprobada el')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('exported_at')
                    ->label('Exportada el')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('quote')
                    ->label('Cotización')
                    ->relationship('quote', 'title')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(FabStudioOptions::QUOTE_STATUSES),
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
