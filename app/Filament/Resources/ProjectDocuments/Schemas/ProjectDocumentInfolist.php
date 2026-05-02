<?php

namespace App\Filament\Resources\ProjectDocuments\Schemas;

use App\Models\ProjectDocument;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjectDocumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('project.name')
                    ->label('Project'),
                TextEntry::make('uploadedBy.name')
                    ->label('Uploaded by')
                    ->placeholder('-'),
                TextEntry::make('title'),
                TextEntry::make('category'),
                TextEntry::make('visibility'),
                TextEntry::make('status'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (ProjectDocument $record): bool => $record->trashed()),
            ]);
    }
}
