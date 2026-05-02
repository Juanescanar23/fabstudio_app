<?php

namespace App\Filament\Resources\Leads\Schemas;

use App\Support\FabStudioOptions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('client_id')
                    ->label('Cliente')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('email')
                    ->label('Correo electrónico')
                    ->email(),
                TextInput::make('phone')
                    ->label('Teléfono')
                    ->tel(),
                TextInput::make('source')
                    ->label('Fuente'),
                Select::make('status')
                    ->label('Estado')
                    ->options(FabStudioOptions::LEAD_STATUSES)
                    ->required()
                    ->default('new'),
                TextInput::make('interest')
                    ->label('Interés'),
                Textarea::make('message')
                    ->label('Mensaje')
                    ->columnSpanFull(),
                KeyValue::make('metadata')
                    ->label('Metadatos')
                    ->columnSpanFull()
                    ->keyLabel('Clave')
                    ->valueLabel('Valor'),
                DateTimePicker::make('converted_at')
                    ->label('Convertido el'),
            ]);
    }
}
