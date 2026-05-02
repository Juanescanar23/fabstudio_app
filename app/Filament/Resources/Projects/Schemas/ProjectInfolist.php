<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Models\Project;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('client.name')
                    ->label('Client'),
                TextEntry::make('lead.name')
                    ->label('Lead')
                    ->placeholder('-'),
                TextEntry::make('code')
                    ->placeholder('-'),
                TextEntry::make('name'),
                TextEntry::make('typology')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('current_phase')
                    ->placeholder('-'),
                TextEntry::make('location')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('budget_estimate')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('starts_at')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('ends_at')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Project $record): bool => $record->trashed()),
            ]);
    }
}
