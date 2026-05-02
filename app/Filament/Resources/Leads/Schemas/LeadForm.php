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
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('source'),
                Select::make('status')
                    ->options(FabStudioOptions::LEAD_STATUSES)
                    ->required()
                    ->default('new'),
                TextInput::make('interest'),
                Textarea::make('message')
                    ->columnSpanFull(),
                KeyValue::make('metadata')
                    ->columnSpanFull()
                    ->keyLabel('Clave')
                    ->valueLabel('Valor'),
                DateTimePicker::make('converted_at'),
            ]);
    }
}
