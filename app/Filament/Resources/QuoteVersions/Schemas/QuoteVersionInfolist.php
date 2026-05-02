<?php

namespace App\Filament\Resources\QuoteVersions\Schemas;

use App\Support\FabStudioOptions;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class QuoteVersionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('quote.title')
                    ->label('Cotización'),
                TextEntry::make('template.name')
                    ->label('Plantilla')
                    ->placeholder('-'),
                TextEntry::make('createdBy.name')
                    ->label('Creada por')
                    ->placeholder('-'),
                TextEntry::make('reviewedBy.name')
                    ->label('Revisada por')
                    ->placeholder('-'),
                TextEntry::make('approvedBy.name')
                    ->label('Aprobada por')
                    ->placeholder('-'),
                TextEntry::make('version_number')
                    ->label('Número de versión')
                    ->numeric(),
                TextEntry::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => FabStudioOptions::QUOTE_STATUSES[$state] ?? '-')
                    ->color(fn (?string $state): string => FabStudioOptions::statusColor($state)),
                TextEntry::make('content')
                    ->label('Contenido')
                    ->formatStateUsing(fn ($state): string => is_array($state)
                        ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                        : (string) $state)
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('ai_model')
                    ->label('Modelo IA')
                    ->placeholder('-'),
                TextEntry::make('ai_prompt_hash')
                    ->label('Hash del prompt IA')
                    ->placeholder('-'),
                TextEntry::make('pdf_path')
                    ->label('PDF')
                    ->placeholder('-'),
                TextEntry::make('pdf_disk')
                    ->label('Disco PDF')
                    ->placeholder('-'),
                TextEntry::make('subtotal')
                    ->label('Subtotal')
                    ->numeric(),
                TextEntry::make('tax')
                    ->label('Impuestos')
                    ->numeric(),
                TextEntry::make('discount')
                    ->label('Descuento')
                    ->numeric(),
                TextEntry::make('total')
                    ->label('Total')
                    ->numeric(),
                TextEntry::make('reviewed_at')
                    ->label('Revisada el')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('approved_at')
                    ->label('Aprobada el')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('exported_at')
                    ->label('Exportada el')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label('Creada')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Actualizada')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
