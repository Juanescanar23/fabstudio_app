<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LeadInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('client.name')
                    ->label('Cliente')
                    ->placeholder('-'),
                TextEntry::make('name')
                    ->label('Nombre'),
                TextEntry::make('email')
                    ->label('Correo electrónico')
                    ->placeholder('-'),
                TextEntry::make('phone')
                    ->label('Teléfono')
                    ->placeholder('-'),
                TextEntry::make('source')
                    ->label('Fuente')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->label('Estado'),
                TextEntry::make('interest')
                    ->label('Interés')
                    ->placeholder('-'),
                TextEntry::make('message')
                    ->label('Mensaje')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('metadata')
                    ->label('Metadatos')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('converted_at')
                    ->label('Convertido el')
                    ->dateTime()
                    ->placeholder('-'),
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
