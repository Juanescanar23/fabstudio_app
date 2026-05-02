<?php

namespace App\Filament\Resources\VisualAssets\Tables;

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

class VisualAssetsTable
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
                TextColumn::make('type')
                    ->label('Tipo')
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
                TextColumn::make('file_path')
                    ->label('Archivo')
                    ->searchable(),
                TextColumn::make('preview_path')
                    ->label('Vista previa')
                    ->searchable(),
                TextColumn::make('external_url')
                    ->label('URL externa')
                    ->searchable(),
                TextColumn::make('mime_type')
                    ->label('Tipo MIME')
                    ->searchable(),
                TextColumn::make('size')
                    ->label('Tamaño')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->numeric()
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
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options(FabStudioOptions::VISUAL_ASSET_TYPES),
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
