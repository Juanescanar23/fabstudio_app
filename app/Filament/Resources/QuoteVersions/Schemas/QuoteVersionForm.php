<?php

namespace App\Filament\Resources\QuoteVersions\Schemas;

use App\Support\FabStudioOptions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                Select::make('quote_template_id')
                    ->label('Plantilla')
                    ->relationship('template', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('created_by_id')
                    ->label('Creada por')
                    ->relationship('createdBy', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('reviewed_by_id')
                    ->label('Revisada por')
                    ->relationship('reviewedBy', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('approved_by_id')
                    ->label('Aprobada por')
                    ->relationship('approvedBy', 'name')
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
                Textarea::make('content')
                    ->label('Contenido')
                    ->columnSpanFull()
                    ->rows(12)
                    ->formatStateUsing(fn ($state): ?string => is_array($state)
                        ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                        : $state)
                    ->dehydrateStateUsing(fn ($state): ?array => blank($state) ? null : json_decode((string) $state, true))
                    ->rules(['nullable', 'json']),
                TextInput::make('ai_model')
                    ->label('Modelo IA'),
                TextInput::make('ai_prompt_hash')
                    ->label('Hash del prompt IA'),
                TextInput::make('pdf_path')
                    ->label('Ruta PDF'),
                TextInput::make('pdf_disk')
                    ->label('Disco PDF')
                    ->default('local'),
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
                DateTimePicker::make('exported_at')
                    ->label('Exportada el'),
            ]);
    }
}
