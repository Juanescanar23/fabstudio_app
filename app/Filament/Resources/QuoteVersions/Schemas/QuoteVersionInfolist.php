<?php

namespace App\Filament\Resources\QuoteVersions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class QuoteVersionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('quote.title')
                    ->label('Quote'),
                TextEntry::make('createdBy.name')
                    ->label('Created by')
                    ->placeholder('-'),
                TextEntry::make('version_number')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('content')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('ai_model')
                    ->placeholder('-'),
                TextEntry::make('ai_prompt_hash')
                    ->placeholder('-'),
                TextEntry::make('pdf_path')
                    ->placeholder('-'),
                TextEntry::make('subtotal')
                    ->numeric(),
                TextEntry::make('tax')
                    ->numeric(),
                TextEntry::make('discount')
                    ->numeric(),
                TextEntry::make('total')
                    ->numeric(),
                TextEntry::make('reviewed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('approved_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
