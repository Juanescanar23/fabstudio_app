<?php

namespace App\Filament\Resources\QuoteVersions\Schemas;

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
                TextEntry::make('createdBy.name')
                    ->label('Creada por')
                    ->placeholder('-'),
                TextEntry::make('version_number')
                    ->label('Número de versión')
                    ->numeric(),
                TextEntry::make('status')
                    ->label('Estado'),
                TextEntry::make('content')
                    ->label('Contenido')
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
