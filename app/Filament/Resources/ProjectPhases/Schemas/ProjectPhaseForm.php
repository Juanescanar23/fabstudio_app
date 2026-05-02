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
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('status')
                    ->options(FabStudioOptions::PHASE_STATUSES)
                    ->required()
                    ->default('pending'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                DatePicker::make('starts_at'),
                DatePicker::make('due_at'),
                DateTimePicker::make('completed_at'),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
