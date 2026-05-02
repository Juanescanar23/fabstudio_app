<?php

namespace App\Filament\Resources\Clients\Schemas;

use App\Support\FabStudioOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('type')
                    ->options(FabStudioOptions::CLIENT_TYPES)
                    ->required()
                    ->default('individual'),
                TextInput::make('email')
                    ->label('Email')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('identification'),
                TextInput::make('city'),
                TextInput::make('address'),
                Select::make('status')
                    ->options(FabStudioOptions::CLIENT_STATUSES)
                    ->required()
                    ->default('active'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
