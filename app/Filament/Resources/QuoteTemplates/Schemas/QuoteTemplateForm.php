<?php

namespace App\Filament\Resources\QuoteTemplates\Schemas;

use App\Support\FabStudioOptions;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QuoteTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->maxLength(255),
            Select::make('type')
                ->label('Tipo')
                ->options(FabStudioOptions::QUOTE_TEMPLATE_TYPES)
                ->required()
                ->default('design'),
            Select::make('status')
                ->label('Estado')
                ->options(FabStudioOptions::TEMPLATE_STATUSES)
                ->required()
                ->default('active'),
            Select::make('currency')
                ->label('Moneda')
                ->options(FabStudioOptions::CURRENCIES)
                ->required()
                ->default('COP'),
            TextInput::make('default_valid_days')
                ->label('Días de vigencia')
                ->required()
                ->numeric()
                ->minValue(1)
                ->default(30),
            Select::make('created_by_id')
                ->label('Creada por')
                ->relationship('createdBy', 'name')
                ->searchable()
                ->preload(),
            Textarea::make('description')
                ->label('Descripción interna')
                ->columnSpanFull(),
            Repeater::make('sections')
                ->label('Secciones de la propuesta')
                ->schema([
                    TextInput::make('heading')
                        ->label('Título')
                        ->required(),
                    TextInput::make('sort_order')
                        ->label('Orden')
                        ->numeric()
                        ->default(1),
                    Textarea::make('body')
                        ->label('Contenido')
                        ->required()
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->defaultItems(2)
                ->columnSpanFull(),
            Repeater::make('line_items')
                ->label('Servicios y valores')
                ->schema([
                    TextInput::make('name')
                        ->label('Servicio')
                        ->required(),
                    TextInput::make('quantity')
                        ->label('Cantidad')
                        ->numeric()
                        ->default(1)
                        ->required(),
                    TextInput::make('unit_price')
                        ->label('Valor unitario')
                        ->numeric()
                        ->default(0)
                        ->required(),
                    Textarea::make('description')
                        ->label('Descripción')
                        ->columnSpanFull(),
                ])
                ->columns(3)
                ->defaultItems(1)
                ->columnSpanFull(),
            Textarea::make('terms')
                ->label('Condiciones comerciales')
                ->columnSpanFull(),
            Textarea::make('ai_instructions')
                ->label('Instrucciones para asistencia IA')
                ->columnSpanFull(),
            KeyValue::make('metadata')
                ->label('Metadatos')
                ->columnSpanFull()
                ->keyLabel('Clave')
                ->valueLabel('Valor'),
        ]);
    }
}
