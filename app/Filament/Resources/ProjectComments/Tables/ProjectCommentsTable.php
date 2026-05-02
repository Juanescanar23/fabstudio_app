<?php

namespace App\Filament\Resources\ProjectComments\Tables;

use App\Support\FabStudioOptions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjectCommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.name')
                    ->label('Proyecto')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable(),
                TextColumn::make('commentable_type')
                    ->label('Tipo de entregable')
                    ->searchable(),
                TextColumn::make('commentable_id')
                    ->label('ID del entregable')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (?string $state): string => FabStudioOptions::statusColor($state))
                    ->searchable(),
                TextColumn::make('visibility')
                    ->label('Visibilidad')
                    ->badge()
                    ->color(fn (?string $state): string => $state === 'client' ? 'info' : 'gray')
                    ->searchable(),
                TextColumn::make('decision')
                    ->label('Decisión')
                    ->badge()
                    ->color(fn (?string $state): string => FabStudioOptions::statusColor($state))
                    ->searchable(),
                TextColumn::make('decided_at')
                    ->label('Decidido el')
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
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options(FabStudioOptions::COMMENT_TYPES),
                SelectFilter::make('visibility')
                    ->label('Visibilidad')
                    ->options(FabStudioOptions::VISIBILITIES),
                SelectFilter::make('decision')
                    ->label('Decisión')
                    ->options(FabStudioOptions::DECISIONS),
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
