<?php

namespace App\Filament\Resources\Quotes\Actions;

use App\Models\Quote;
use App\Models\QuoteTemplate;
use App\Services\Quotes\QuoteWorkflowService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

class CreateQuoteVersionFromTemplateAction
{
    public static function make(): Action
    {
        return Action::make('createVersionFromTemplate')
            ->label('Crear versión desde plantilla')
            ->schema([
                Select::make('quote_template_id')
                    ->label('Plantilla')
                    ->options(fn (): array => QuoteTemplate::query()
                        ->where('status', 'active')
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->required(),
                Textarea::make('assistant_note')
                    ->label('Nota para revisión humana')
                    ->default('Borrador generado con asistencia local. Revisar alcance, valores y condiciones antes de aprobar.'),
            ])
            ->modalHeading('Crear versión asistida')
            ->modalSubmitActionLabel('Crear versión')
            ->action(function (array $data, Quote $record): void {
                $template = QuoteTemplate::query()->findOrFail($data['quote_template_id']);

                app(QuoteWorkflowService::class)->createVersionFromTemplate(
                    quote: $record,
                    template: $template,
                    user: auth()->user(),
                    overrides: [
                        'assistant_note' => $data['assistant_note'] ?? null,
                    ],
                );

                Notification::make()
                    ->title('Versión creada')
                    ->body('La cotización quedó en revisión y requiere validación humana antes de aprobarse.')
                    ->success()
                    ->send();
            });
    }
}
