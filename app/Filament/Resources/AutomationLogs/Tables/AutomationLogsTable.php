<?php

namespace App\Filament\Resources\AutomationLogs\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AutomationLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('automation_key')
                    ->label('Automatización')
                    ->searchable()
                    ->badge(),
                TextColumn::make('severity')
                    ->label('Severidad')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'critical' => 'danger',
                        'warning' => 'warning',
                        default => 'info',
                    }),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'sent' => 'success',
                        'failed' => 'danger',
                        'skipped' => 'gray',
                        default => 'warning',
                    }),
                TextColumn::make('subject_type')
                    ->label('Entidad')
                    ->formatStateUsing(fn (?string $state): string => $state ? Str::afterLast($state, '\\') : '-')
                    ->toggleable(),
                TextColumn::make('recipient_email')
                    ->label('Destinatario')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('notified_at')
                    ->label('Notificado')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('automation_key')
                    ->label('Automatización')
                    ->options([
                        'lead.follow_up' => 'Seguimiento de prospectos',
                        'milestone.due_soon' => 'Hitos por vencer',
                        'milestone.overdue' => 'Hitos vencidos',
                        'document.published' => 'Documentos publicados',
                        'visual_asset.published' => 'Assets publicados',
                        'quote.validity_expiring' => 'Cotizaciones por vencer',
                    ]),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'sent' => 'Enviado',
                        'skipped' => 'Omitido',
                        'failed' => 'Fallido',
                    ]),
                SelectFilter::make('severity')
                    ->label('Severidad')
                    ->options([
                        'info' => 'Informativa',
                        'warning' => 'Advertencia',
                        'critical' => 'Crítica',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
