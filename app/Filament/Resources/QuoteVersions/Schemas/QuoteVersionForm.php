<?php

namespace App\Filament\Resources\QuoteVersions\Schemas;

use App\Support\FabStudioOptions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QuoteVersionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('quote_id')
                    ->label('Cotización')
                    ->relationship('quote', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('created_by_id')
                    ->label('Creada por')
                    ->relationship('createdBy', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('version_number')
                    ->label('Número de versión')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->label('Estado')
                    ->options(FabStudioOptions::QUOTE_STATUSES)
                    ->required()
                    ->default('draft'),
                KeyValue::make('content')
                    ->label('Contenido')
                    ->columnSpanFull()
                    ->keyLabel('Sección')
                    ->valueLabel('Contenido'),
                TextInput::make('ai_model')
                    ->label('Modelo IA'),
                TextInput::make('ai_prompt_hash')
                    ->label('Hash del prompt IA'),
                FileUpload::make('pdf_path')
                    ->label('PDF')
                    ->disk('local')
                    ->directory('quotes'),
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
                DateTimePicker::make('reviewed_at')
                    ->label('Revisada el'),
                DateTimePicker::make('approved_at')
                    ->label('Aprobada el'),
            ]);
    }
}
