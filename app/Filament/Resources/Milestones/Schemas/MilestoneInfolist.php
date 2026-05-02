<?php

namespace App\Filament\Resources\Milestones\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MilestoneInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('project.name')
                    ->label('Proyecto'),
                TextEntry::make('phase.name')
                    ->label('Fase')
                    ->placeholder('-'),
                TextEntry::make('title')
                    ->label('Título'),
                TextEntry::make('status')
                    ->label('Estado'),
                TextEntry::make('sort_order')
                    ->label('Orden')
                    ->numeric(),
                TextEntry::make('due_at')
                    ->label('Fecha límite')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('completed_at')
                    ->label('Completado el')
                    ->dateTime()
                    ->placeholder('-'),
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
            ]);
    }
}
