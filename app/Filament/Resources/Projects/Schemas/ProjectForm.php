<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Support\FabStudioOptions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('client_id')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('lead_id')
                    ->relationship('lead', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('code'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('typology'),
                Select::make('status')
                    ->options(FabStudioOptions::PROJECT_STATUSES)
                    ->required()
                    ->default('planning'),
                TextInput::make('current_phase'),
                TextInput::make('location'),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('budget_estimate')
                    ->numeric(),
                DatePicker::make('starts_at'),
                DatePicker::make('ends_at'),
            ]);
    }
}
