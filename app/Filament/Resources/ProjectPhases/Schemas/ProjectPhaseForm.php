<?php

namespace App\Filament\Resources\ProjectPhases\Schemas;

use App\Support\FabStudioOptions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProjectPhaseForm
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
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                Select::make('status')
                    ->label('Estado')
                    ->options(FabStudioOptions::PHASE_STATUSES)
                    ->required()
                    ->default('pending'),
                TextInput::make('sort_order')
                    ->label('Orden')
                    ->required()
                    ->numeric()
                    ->default(0),
                DatePicker::make('starts_at')
                    ->label('Fecha de inicio'),
                DatePicker::make('due_at')
                    ->label('Fecha límite'),
                DateTimePicker::make('completed_at')
                    ->label('Completado el'),
                Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
            ]);
    }
}
