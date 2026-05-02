<?php

namespace App\Filament\Resources\Quotes\Schemas;

use App\Models\Quote;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class QuoteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('project.name')
                    ->label('Proyecto'),
                TextEntry::make('client.name')
                    ->label('Cliente'),
                TextEntry::make('createdBy.name')
                    ->label('Creada por')
                    ->placeholder('-'),
                TextEntry::make('quote_number')
                    ->label('Número de cotización')
                    ->placeholder('-'),
                TextEntry::make('title')
                    ->label('Título'),
                TextEntry::make('status')
                    ->label('Estado'),
                TextEntry::make('currency')
                    ->label('Moneda'),
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
                TextEntry::make('valid_until')
                    ->label('Válida hasta')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('sent_at')
                    ->label('Enviada el')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('approved_at')
                    ->label('Aprobada el')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->label('Notas')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('metadata')
                    ->label('Metadatos')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->label('Creada')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Actualizada')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->label('Eliminada')
                    ->dateTime()
                    ->visible(fn (Quote $record): bool => $record->trashed()),
            ]);
    }
}
