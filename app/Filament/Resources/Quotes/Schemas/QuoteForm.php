<?php

namespace App\Filament\Resources\Quotes\Schemas;

use App\Support\FabStudioOptions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QuoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('project_id')
                    ->label('Proyecto')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('client_id')
                    ->label('Cliente')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('created_by_id')
                    ->label('Creada por')
                    ->relationship('createdBy', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('quote_number')
                    ->label('Número de cotización'),
                TextInput::make('title')
                    ->label('Título')
                    ->required(),
                Select::make('status')
                    ->label('Estado')
                    ->options(FabStudioOptions::QUOTE_STATUSES)
                    ->required()
                    ->default('draft'),
                Select::make('currency')
                    ->label('Moneda')
                    ->options(FabStudioOptions::CURRENCIES)
                    ->required()
                    ->default('COP'),
                TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('tax')
                    ->label('Impuestos')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('discount')
                    ->label('Descuento')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total')
                    ->label('Total')
                    ->required()
                    ->numeric()
                    ->default(0),
                DatePicker::make('valid_until')
                    ->label('Válida hasta'),
                DateTimePicker::make('sent_at')
                    ->label('Enviada el'),
                DateTimePicker::make('approved_at')
                    ->label('Aprobada el'),
                Textarea::make('notes')
                    ->label('Notas')
                    ->columnSpanFull(),
                KeyValue::make('metadata')
                    ->label('Metadatos')
                    ->columnSpanFull()
                    ->keyLabel('Clave')
                    ->valueLabel('Valor'),
            ]);
    }
}
