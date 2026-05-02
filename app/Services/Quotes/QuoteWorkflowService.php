<?php

namespace App\Services\Quotes;

use App\Models\Quote;
use App\Models\QuoteTemplate;
use App\Models\QuoteVersion;
use App\Models\User;
use App\Notifications\QuoteVersionExportedNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class QuoteWorkflowService
{
    public const ASSISTANT_MODEL = 'fabstudio-assistant-local-v1';

    public function __construct(private readonly QuotePdfGenerator $pdfGenerator)
    {
        //
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    public function createVersionFromTemplate(
        Quote $quote,
        QuoteTemplate $template,
        ?User $user = null,
        array $overrides = [],
    ): QuoteVersion {
        return DB::transaction(function () use ($quote, $template, $user, $overrides): QuoteVersion {
            $quote->loadMissing(['project', 'client']);
            $template->refresh();

            $lineItems = $this->normalizeLineItems($template->line_items ?: []);
            $subtotal = $this->sumLineItems($lineItems);
            $tax = (float) ($overrides['tax'] ?? $quote->tax ?? 0);
            $discount = (float) ($overrides['discount'] ?? $quote->discount ?? 0);
            $total = max(0, $subtotal + $tax - $discount);
            $validUntil = $quote->valid_until ?: now()->addDays($template->default_valid_days ?: 30)->toDateString();

            $content = [
                'summary' => [
                    'project_name' => $quote->project?->name,
                    'client_name' => $quote->client?->name,
                    'quote_number' => $quote->quote_number,
                    'assistant_note' => trim((string) ($overrides['assistant_note'] ?? 'Borrador generado desde plantilla. Requiere revisión humana antes de aprobación o envío.')),
                    'human_review_required' => true,
                ],
                'sections' => $this->renderSections($quote, $template),
                'line_items' => $lineItems,
                'terms' => $this->renderText($template->terms ?: '', $quote),
                'ai_instructions' => $template->ai_instructions,
            ];

            $version = QuoteVersion::query()->create([
                'quote_id' => $quote->id,
                'quote_template_id' => $template->id,
                'created_by_id' => $user?->id,
                'version_number' => $this->nextVersionNumber($quote),
                'status' => 'review',
                'content' => $content,
                'ai_model' => self::ASSISTANT_MODEL,
                'ai_prompt_hash' => hash('sha256', json_encode([
                    'quote_id' => $quote->id,
                    'template_id' => $template->id,
                    'project' => $quote->project?->only(['name', 'typology', 'location']),
                    'client' => $quote->client?->only(['name', 'type', 'city']),
                    'instructions' => $template->ai_instructions,
                ], JSON_THROW_ON_ERROR)),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
            ]);

            $quote->forceFill([
                'status' => 'review',
                'currency' => $template->currency,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'valid_until' => $validUntil,
            ])->save();

            return $version->refresh();
        });
    }

    public function markReviewed(QuoteVersion $version, ?User $user = null): QuoteVersion
    {
        return DB::transaction(function () use ($version, $user): QuoteVersion {
            $version->forceFill([
                'status' => 'reviewed',
                'reviewed_by_id' => $user?->id,
                'reviewed_at' => now(),
            ])->save();

            $version->quote()->update(['status' => 'reviewed']);

            return $version->refresh();
        });
    }

    public function approve(QuoteVersion $version, ?User $user = null): QuoteVersion
    {
        return DB::transaction(function () use ($version, $user): QuoteVersion {
            if (! $version->reviewed_at) {
                $version = $this->markReviewed($version, $user);
            }

            $version->forceFill([
                'status' => 'approved',
                'approved_by_id' => $user?->id,
                'approved_at' => now(),
            ])->save();

            $version->quote()->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);

            return $version->refresh();
        });
    }

    /**
     * @throws ValidationException
     */
    public function exportPdf(QuoteVersion $version, string $disk = 'local'): QuoteVersion
    {
        if ($version->status !== 'approved' || ! $version->approved_at) {
            throw ValidationException::withMessages([
                'quote_version' => 'La cotización debe estar aprobada por una persona del equipo antes de exportar el PDF.',
            ]);
        }

        return DB::transaction(function () use ($version, $disk): QuoteVersion {
            $path = $this->pdfGenerator->generate($version, $disk);

            $version->forceFill([
                'status' => 'exported',
                'pdf_disk' => $disk,
                'pdf_path' => $path,
                'exported_at' => now(),
            ])->save();

            $version->quote()->update([
                'status' => 'exported',
                'exported_at' => now(),
            ]);

            $version = $version->refresh();
            $this->notifyClientAboutExport($version);

            return $version;
        });
    }

    private function nextVersionNumber(Quote $quote): int
    {
        return (int) $quote->versions()->max('version_number') + 1;
    }

    /**
     * @param  array<int, array<string, mixed>>  $sections
     * @return array<int, array<string, mixed>>
     */
    private function renderSections(Quote $quote, QuoteTemplate $template): array
    {
        return collect($template->sections ?: [])
            ->sortBy(fn (array $section): int => (int) ($section['sort_order'] ?? 0))
            ->values()
            ->map(fn (array $section): array => [
                'heading' => (string) ($section['heading'] ?? 'Sección'),
                'body' => $this->renderText((string) ($section['body'] ?? ''), $quote),
                'sort_order' => (int) ($section['sort_order'] ?? 0),
            ])
            ->all();
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @return array<int, array<string, mixed>>
     */
    private function normalizeLineItems(array $items): array
    {
        return collect($items)
            ->map(function (array $item): array {
                $quantity = (float) ($item['quantity'] ?? 1);
                $unitPrice = (float) ($item['unit_price'] ?? 0);

                return [
                    'name' => (string) ($item['name'] ?? 'Servicio'),
                    'description' => (string) ($item['description'] ?? ''),
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $quantity * $unitPrice,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private function sumLineItems(array $items): float
    {
        return (float) collect($items)->sum(fn (array $item): float => (float) Arr::get($item, 'total', 0));
    }

    private function renderText(string $text, Quote $quote): string
    {
        $quote->loadMissing(['project', 'client']);

        return strtr($text, [
            '{{client_name}}' => $quote->client?->name ?: '',
            '{{project_name}}' => $quote->project?->name ?: '',
            '{{project_location}}' => $quote->project?->location ?: '',
            '{{project_typology}}' => $quote->project?->typology ?: '',
            '{{quote_number}}' => $quote->quote_number ?: '',
            '{{quote_title}}' => $quote->title ?: '',
        ]);
    }

    private function notifyClientAboutExport(QuoteVersion $version): void
    {
        $version->loadMissing(['quote.client']);
        $email = $version->quote?->client?->email;

        if (! $email) {
            return;
        }

        try {
            Notification::route('mail', $email)
                ->notify(new QuoteVersionExportedNotification($version));
        } catch (Throwable $exception) {
            Log::warning('No se pudo enviar la notificación transaccional de cotización exportada.', [
                'quote_version_id' => $version->id,
                'message' => Str::limit($exception->getMessage(), 160),
            ]);
        }
    }
}
