<?php

namespace App\Filament\Resources\Clients\Schemas;

use App\Models\Client;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ClientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nombre'),
                TextEntry::make('type')
                    ->label('Tipo'),
                TextEntry::make('email')
                    ->label('Correo electrónico')
                    ->placeholder('-'),
                TextEntry::make('phone')
                    ->label('Teléfono')
                    ->placeholder('-'),
                TextEntry::make('identification')
                    ->label('Identificación')
                    ->placeholder('-'),
                TextEntry::make('city')
                    ->label('Ciudad')
                    ->placeholder('-'),
                TextEntry::make('address')
                    ->label('Dirección')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->label('Estado'),
                TextEntry::make('notes')
                    ->label('Notas')
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
                    ->visible(fn (Client $record): bool => $record->trashed()),
            ]);
    }
}
