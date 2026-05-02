<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Support\FabStudioOptions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('client_id')
                    ->label('Cliente')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('lead_id')
                    ->label('Prospecto')
                    ->relationship('lead', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('code')
                    ->label('Código'),
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('typology')
                    ->label('Tipología'),
                Select::make('status')
                    ->label('Estado')
                    ->options(FabStudioOptions::PROJECT_STATUSES)
                    ->required()
                    ->default('planning'),
                TextInput::make('current_phase')
                    ->label('Fase actual'),
                TextInput::make('location')
                    ->label('Ubicación'),
                Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                TextInput::make('budget_estimate')
                    ->label('Presupuesto estimado')
                    ->numeric(),
                DatePicker::make('starts_at')
                    ->label('Fecha de inicio'),
                DatePicker::make('ends_at')
                    ->label('Fecha de cierre'),
                Toggle::make('is_public')
                    ->label('Publicado en sitio público')
                    ->default(false),
                Toggle::make('is_featured')
                    ->label('Destacado')
                    ->default(false),
                TextInput::make('public_slug')
                    ->label('Slug público')
                    ->unique(ignoreRecord: true),
                TextInput::make('public_cover_path')
                    ->label('Portada pública (URL o ruta)'),
                Textarea::make('public_summary')
                    ->label('Resumen público')
                    ->columnSpanFull(),
                DateTimePicker::make('public_published_at')
                    ->label('Publicado el'),
                TextInput::make('seo_title')
                    ->label('Título SEO'),
                Textarea::make('seo_description')
                    ->label('Descripción SEO')
                    ->columnSpanFull(),
            ]);
    }
}
