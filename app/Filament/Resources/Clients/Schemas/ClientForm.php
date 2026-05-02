<?php

namespace App\Filament\Resources\Clients\Schemas;

use App\Support\FabStudioOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                Select::make('type')
                    ->label('Tipo')
                    ->options(FabStudioOptions::CLIENT_TYPES)
                    ->required()
                    ->default('individual'),
                TextInput::make('email')
                    ->label('Correo electrónico')
                    ->email(),
                TextInput::make('phone')
                    ->label('Teléfono')
                    ->tel(),
                TextInput::make('identification')
                    ->label('Identificación'),
                TextInput::make('city')
                    ->label('Ciudad'),
                TextInput::make('address')
                    ->label('Dirección'),
                Select::make('status')
                    ->label('Estado')
                    ->options(FabStudioOptions::CLIENT_STATUSES)
                    ->required()
                    ->default('active'),
                Textarea::make('notes')
                    ->label('Notas')
                    ->columnSpanFull(),
            ]);
    }
}
