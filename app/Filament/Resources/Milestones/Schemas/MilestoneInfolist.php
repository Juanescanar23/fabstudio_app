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
                    ->label('Project'),
                TextEntry::make('phase.name')
                    ->label('Fase')
                    ->placeholder('-'),
                TextEntry::make('title'),
                TextEntry::make('status'),
                TextEntry::make('sort_order')
                    ->numeric(),
                TextEntry::make('due_at')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('completed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
