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
                    ->label('Proyecto'),
                TextEntry::make('uploadedBy.name')
                    ->label('Subido por')
                    ->placeholder('-'),
                TextEntry::make('title')
                    ->label('Título'),
                TextEntry::make('category')
                    ->label('Categoría'),
                TextEntry::make('visibility')
                    ->label('Visibilidad'),
                TextEntry::make('status')
                    ->label('Estado'),
                TextEntry::make('description')
                    ->label('Descripción')
                    ->placeholder('-')
                    ->columnSpanFull(),
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
                    ->visible(fn (ProjectDocument $record): bool => $record->trashed()),
            ]);
    }
}
