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
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('client_id')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('created_by_id')
                    ->relationship('createdBy', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('quote_number'),
                TextInput::make('title')
                    ->required(),
                Select::make('status')
                    ->options(FabStudioOptions::QUOTE_STATUSES)
                    ->required()
                    ->default('draft'),
                Select::make('currency')
                    ->options(FabStudioOptions::CURRENCIES)
                    ->required()
                    ->default('COP'),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('tax')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0),
                DatePicker::make('valid_until'),
                DateTimePicker::make('sent_at'),
                DateTimePicker::make('approved_at'),
                Textarea::make('notes')
                    ->columnSpanFull(),
                KeyValue::make('metadata')
                    ->columnSpanFull()
                    ->keyLabel('Clave')
                    ->valueLabel('Valor'),
            ]);
    }
}
