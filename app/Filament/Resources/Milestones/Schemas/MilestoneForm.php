<?php

namespace App\Filament\Resources\Milestones\Schemas;

use App\Support\FabStudioOptions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MilestoneForm
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
                Select::make('project_phase_id')
                    ->relationship('phase', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('title')
                    ->required(),
                Select::make('status')
                    ->options(FabStudioOptions::PHASE_STATUSES)
                    ->required()
                    ->default('pending'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                DatePicker::make('due_at'),
                DateTimePicker::make('completed_at'),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
