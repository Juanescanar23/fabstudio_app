<?php

namespace App\Filament\Resources\ProjectComments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjectCommentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('project.name')
                    ->label('Proyecto'),
                TextEntry::make('user.name')
                    ->label('Usuario')
                    ->placeholder('-'),
                TextEntry::make('commentable_type')
                    ->label('Tipo de entregable')
                    ->placeholder('-'),
                TextEntry::make('commentable_id')
                    ->label('ID del entregable')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('type')
                    ->label('Tipo'),
                TextEntry::make('visibility')
                    ->label('Visibilidad'),
                TextEntry::make('body')
                    ->label('Comentario')
                    ->columnSpanFull(),
                TextEntry::make('decision')
                    ->label('Decisión')
                    ->placeholder('-'),
                TextEntry::make('decided_at')
                    ->label('Decidido el')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('metadata')
                    ->label('Metadatos')
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
            ]);
    }
}
