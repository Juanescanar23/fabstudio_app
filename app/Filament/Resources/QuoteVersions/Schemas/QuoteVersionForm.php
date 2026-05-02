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
                    ->relationship('quote', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('created_by_id')
                    ->relationship('createdBy', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('version_number')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(FabStudioOptions::QUOTE_STATUSES)
                    ->required()
                    ->default('draft'),
                KeyValue::make('content')
                    ->columnSpanFull()
                    ->keyLabel('Seccion')
                    ->valueLabel('Contenido'),
                TextInput::make('ai_model'),
                TextInput::make('ai_prompt_hash'),
                FileUpload::make('pdf_path')
                    ->disk('local')
                    ->directory('quotes'),
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
                DateTimePicker::make('reviewed_at'),
                DateTimePicker::make('approved_at'),
            ]);
    }
}
