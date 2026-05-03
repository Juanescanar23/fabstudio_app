<?php

namespace App\Filament\Resources\AutomationLogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AutomationLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Título'),
                TextEntry::make('automation_key')
                    ->label('Automatización'),
                TextEntry::make('category')
                    ->label('Categoría'),
                TextEntry::make('severity')
                    ->label('Severidad')
                    ->badge(),
                TextEntry::make('status')
                    ->label('Estado')
                    ->badge(),
                TextEntry::make('subject_type')
                    ->label('Entidad')
                    ->formatStateUsing(fn (?string $state): string => $state ? Str::afterLast($state, '\\') : '-')
                    ->placeholder('-'),
                TextEntry::make('subject_id')
                    ->label('ID entidad')
                    ->placeholder('-'),
                TextEntry::make('recipient_email')
                    ->label('Correo destinatario')
                    ->placeholder('-'),
                TextEntry::make('channel')
                    ->label('Canal'),
                TextEntry::make('summary')
                    ->label('Resumen')
                    ->columnSpanFull()
                    ->placeholder('-'),
                TextEntry::make('payload')
                    ->label('Payload')
                    ->formatStateUsing(fn ($state): string => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '-')
                    ->columnSpanFull()
                    ->placeholder('-'),
                TextEntry::make('processed_at')
                    ->label('Procesado')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('notified_at')
                    ->label('Notificado')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
