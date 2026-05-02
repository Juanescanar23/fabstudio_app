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
                    ->label('Proyecto')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('project_phase_id')
                    ->label('Fase')
                    ->relationship('phase', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('title')
                    ->label('Título')
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
