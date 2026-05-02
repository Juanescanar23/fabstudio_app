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
                    ->label('Cliente'),
                TextEntry::make('lead.name')
                    ->label('Prospecto')
                    ->placeholder('-'),
                TextEntry::make('code')
                    ->label('Código')
                    ->placeholder('-'),
                TextEntry::make('name')
                    ->label('Nombre'),
                TextEntry::make('typology')
                    ->label('Tipología')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->label('Estado'),
                TextEntry::make('current_phase')
                    ->label('Fase actual')
                    ->placeholder('-'),
                TextEntry::make('location')
                    ->label('Ubicación')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->label('Descripción')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('budget_estimate')
                    ->label('Presupuesto estimado')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('starts_at')
                    ->label('Fecha de inicio')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('ends_at')
                    ->label('Fecha de cierre')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('is_public')
                    ->label('Publicado en sitio público')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Sí' : 'No'),
                TextEntry::make('is_featured')
                    ->label('Destacado')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Sí' : 'No'),
                TextEntry::make('public_slug')
                    ->label('Slug público')
                    ->placeholder('-'),
                TextEntry::make('public_summary')
                    ->label('Resumen público')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('public_published_at')
                    ->label('Publicado el')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('seo_title')
                    ->label('Título SEO')
                    ->placeholder('-'),
                TextEntry::make('seo_description')
                    ->label('Descripción SEO')
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
                    ->visible(fn (Project $record): bool => $record->trashed()),
            ]);
    }
}
