<?php

namespace App\Filament\Resources\QuoteVersions\Actions;

use App\Models\QuoteVersion;
use App\Services\Quotes\QuoteWorkflowService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class QuoteVersionWorkflowActions
{
    public static function markReviewed(): Action
    {
        return Action::make('markReviewed')
            ->label('Marcar revisada')
            ->color('gray')
            ->visible(fn (QuoteVersion $record): bool => ! in_array($record->status, ['reviewed', 'approved', 'exported'], true))
            ->requiresConfirmation()
            ->modalHeading('Confirmar revisión humana')
            ->modalDescription('Esta acción registra que el equipo revisó alcance, condiciones y valores de la versión.')
            ->modalSubmitActionLabel('Marcar revisada')
            ->action(function (QuoteVersion $record): void {
                app(QuoteWorkflowService::class)->markReviewed($record, auth()->user());

                Notification::make()
                    ->title('Versión revisada')
                    ->success()
                    ->send();
            });
    }

    public static function approve(): Action
    {
        return Action::make('approveQuoteVersion')
            ->label('Aprobar')
            ->color('success')
            ->visible(fn (QuoteVersion $record): bool => ! in_array($record->status, ['approved', 'exported'], true))
            ->requiresConfirmation()
            ->modalHeading('Aprobar versión de cotización')
            ->modalDescription('La versión quedará aprobada por el usuario actual y podrá exportarse a PDF.')
            ->modalSubmitActionLabel('Aprobar')
            ->action(function (QuoteVersion $record): void {
                app(QuoteWorkflowService::class)->approve($record, auth()->user());

                Notification::make()
                    ->title('Versión aprobada')
                    ->success()
                    ->send();
            });
    }

    public static function exportPdf(): Action
    {
        return Action::make('exportPdf')
            ->label('Exportar PDF')
            ->color('warning')
            ->visible(fn (QuoteVersion $record): bool => $record->status === 'approved')
            ->requiresConfirmation()
            ->modalHeading('Exportar PDF aprobado')
            ->modalDescription('Solo las versiones aprobadas pueden exportarse. Se generará el PDF y se notificará al cliente si tiene correo registrado.')
            ->modalSubmitActionLabel('Exportar PDF')
            ->action(function (QuoteVersion $record): void {
                app(QuoteWorkflowService::class)->exportPdf($record);

                Notification::make()
                    ->title('PDF exportado')
                    ->body('La versión quedó marcada como exportada.')
                    ->success()
                    ->send();
            });
    }

    public static function downloadPdf(): Action
    {
        return Action::make('downloadPdf')
            ->label('Descargar PDF')
            ->color('gray')
            ->url(fn (QuoteVersion $record): string => route('admin.quote-versions.pdf', $record))
            ->openUrlInNewTab()
            ->visible(fn (QuoteVersion $record): bool => filled($record->pdf_path));
    }
}
