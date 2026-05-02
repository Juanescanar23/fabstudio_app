<?php

namespace App\Filament\Resources\ProjectDocuments\Tables;

use App\Support\FabStudioOptions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProjectDocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.name')
                    ->label('Proyecto')
                    ->searchable(),
                TextColumn::make('uploadedBy.name')
                    ->label('Subido por')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                TextColumn::make('category')
                    ->label('Categoría')
                    ->searchable(),
                TextColumn::make('visibility')
                    ->label('Visibilidad')
                    ->badge()
                    ->color(fn (?string $state): string => $state === 'client' ? 'info' : 'gray')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (?string $state): string => FabStudioOptions::statusColor($state))
                    ->searchable(),
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
                TextColumn::make('deleted_at')
                    ->label('Eliminado')
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
                SelectFilter::make('category')
                    ->label('Categoría')
                    ->options(FabStudioOptions::DOCUMENT_CATEGORIES),
                SelectFilter::make('visibility')
                    ->label('Visibilidad')
                    ->options(FabStudioOptions::VISIBILITIES),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(FabStudioOptions::PUBLISH_STATUSES),
                TrashedFilter::make()
                    ->label('Registros eliminados'),
            ])
            ->recordActions([
                ViewAction::make(),
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
