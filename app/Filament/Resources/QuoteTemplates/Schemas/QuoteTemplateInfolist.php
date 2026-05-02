<?php

namespace App\Filament\Resources\QuoteTemplates\Schemas;

use App\Support\FabStudioOptions;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class QuoteTemplateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('name')
                ->label('Nombre'),
            TextEntry::make('type')
                ->label('Tipo')
                ->formatStateUsing(fn (?string $state): string => FabStudioOptions::QUOTE_TEMPLATE_TYPES[$state] ?? '-'),
            TextEntry::make('status')
                ->label('Estado')
                ->badge()
                ->color(fn (?string $state): string => FabStudioOptions::statusColor($state)),
            TextEntry::make('currency')
                ->label('Moneda'),
            TextEntry::make('default_valid_days')
                ->label('Días de vigencia')
                ->numeric(),
            TextEntry::make('createdBy.name')
                ->label('Creada por')
                ->placeholder('-'),
            TextEntry::make('description')
                ->label('Descripción interna')
                ->placeholder('-')
                ->columnSpanFull(),
            TextEntry::make('sections')
                ->label('Secciones')
                ->formatStateUsing(fn ($state): string => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: '-')
                ->placeholder('-')
                ->columnSpanFull(),
            TextEntry::make('line_items')
                ->label('Servicios y valores')
                ->formatStateUsing(fn ($state): string => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: '-')
                ->placeholder('-')
                ->columnSpanFull(),
            TextEntry::make('terms')
                ->label('Condiciones')
                ->placeholder('-')
                ->columnSpanFull(),
            TextEntry::make('ai_instructions')
                ->label('Instrucciones IA')
                ->placeholder('-')
                ->columnSpanFull(),
            TextEntry::make('updated_at')
                ->label('Actualizada')
                ->dateTime(),
        ]);
    }
}
