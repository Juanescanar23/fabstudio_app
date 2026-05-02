<?php

namespace App\Filament\Resources\ProjectPhases\Tables;

use App\Support\FabStudioOptions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjectPhasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.name')
                    ->label('Proyecto')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (?string $state): string => FabStudioOptions::statusColor($state))
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->label('Inicio')
                    ->date()
                    ->sortable(),
                TextColumn::make('due_at')
                    ->label('Fecha límite')
                    ->date()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label('Completado el')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('project')
                    ->label('Proyecto')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(FabStudioOptions::PHASE_STATUSES),
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
