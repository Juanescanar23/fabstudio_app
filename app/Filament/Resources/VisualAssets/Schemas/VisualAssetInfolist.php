<?php

namespace App\Filament\Resources\VisualAssets\Schemas;

use App\Models\VisualAsset;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VisualAssetInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('project.name')
                    ->label('Proyecto'),
                TextEntry::make('uploadedBy.name')
                    ->label('Subido por')
                    ->placeholder('-'),
                TextEntry::make('title')
                    ->label('Título'),
                TextEntry::make('type')
                    ->label('Tipo'),
                TextEntry::make('visibility')
                    ->label('Visibilidad'),
                TextEntry::make('status')
                    ->label('Estado'),
                TextEntry::make('file_path')
                    ->label('Archivo')
                    ->placeholder('-'),
                TextEntry::make('preview_path')
                    ->label('Vista previa')
                    ->placeholder('-'),
                TextEntry::make('external_url')
                    ->label('URL externa')
                    ->placeholder('-'),
                TextEntry::make('mime_type')
                    ->label('Tipo MIME')
                    ->placeholder('-'),
                TextEntry::make('size')
                    ->label('Tamaño')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('metadata')
                    ->label('Metadatos')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('sort_order')
                    ->label('Orden')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->label('Eliminado')
                    ->dateTime()
                    ->visible(fn (VisualAsset $record): bool => $record->trashed()),
            ]);
    }
}
