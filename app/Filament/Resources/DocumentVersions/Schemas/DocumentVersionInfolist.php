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
                    ->label('Uploaded by')
                    ->placeholder('-'),
                TextEntry::make('version_number')
                    ->numeric(),
                TextEntry::make('original_name'),
                TextEntry::make('file_path'),
                TextEntry::make('disk'),
                TextEntry::make('mime_type')
                    ->placeholder('-'),
                TextEntry::make('size')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('checksum')
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_current')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
