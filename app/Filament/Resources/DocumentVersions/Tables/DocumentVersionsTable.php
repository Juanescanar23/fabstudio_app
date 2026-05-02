<?php

namespace App\Filament\Resources\DocumentVersions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DocumentVersionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('document.title')
                    ->label('Documento')
                    ->searchable(),
                TextColumn::make('uploadedBy.name')
                    ->label('Subido por')
                    ->searchable(),
                TextColumn::make('version_number')
                    ->label('Número de versión')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('original_name')
                    ->label('Nombre original')
                    ->searchable(),
                TextColumn::make('file_path')
                    ->label('Archivo')
                    ->searchable(),
                TextColumn::make('disk')
                    ->label('Disco')
                    ->searchable(),
                TextColumn::make('mime_type')
                    ->label('Tipo MIME')
                    ->searchable(),
                TextColumn::make('size')
                    ->label('Tamaño')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('checksum')
                    ->label('Checksum')
                    ->searchable(),
                IconColumn::make('is_current')
                    ->label('Vigente')
                    ->boolean(),
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
                SelectFilter::make('document')
                    ->label('Documento')
                    ->relationship('document', 'title')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('is_current')
                    ->label('Versión vigente'),
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
