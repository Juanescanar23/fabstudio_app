<?php

namespace App\Filament\Resources\DocumentVersions\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DocumentVersionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('document.title')
                    ->label('Documento'),
                TextEntry::make('uploadedBy.name')
                    ->label('Subido por')
                    ->placeholder('-'),
                TextEntry::make('version_number')
                    ->label('Número de versión')
                    ->numeric(),
                TextEntry::make('original_name')
                    ->label('Nombre original'),
                TextEntry::make('file_path')
                    ->label('Archivo'),
                TextEntry::make('disk')
                    ->label('Disco'),
                TextEntry::make('mime_type')
                    ->label('Tipo MIME')
                    ->placeholder('-'),
                TextEntry::make('size')
                    ->label('Tamaño')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('checksum')
                    ->label('Checksum')
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->label('Notas')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_current')
                    ->label('Versión vigente')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
