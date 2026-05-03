<?php

namespace App\Services\Automation;

use App\Models\AutomationLog;
use App\Models\Client;
use App\Models\Lead;
use App\Models\Milestone;
use App\Models\ProjectDocument;
use App\Models\Quote;
use App\Models\User;
use App\Models\VisualAsset;
use App\Notifications\OperationalAutomationNotification;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class OperationalAutomationService
{
    /**
     * @return array{
     *     evaluated: int,
     *     created: int,
     *     notified: int,
     *     skipped: int,
     *     dry_run: bool,
     *     warnings: array<int, string>
     * }
     */
    public function run(bool $dryRun = false, ?CarbonInterface $now = null): array
    {
        $now = $now ? CarbonImmutable::instance($now) : CarbonImmutable::now();

        if (! config('fabstudio.automations.enabled', true)) {
            return [
                'evaluated' => 0,
                'created' => 0,
                'notified' => 0,
                'skipped' => 0,
                'dry_run' => $dryRun,
                'warnings' => ['Las automatizaciones estan desactivadas por configuracion.'],
            ];
        }

        $summary = [
            'evaluated' => 0,
            'created' => 0,
            'notified' => 0,
            'skipped' => 0,
            'dry_run' => $dryRun,
            'warnings' => [],
        ];

        foreach ($this->candidates($now) as $candidate) {
            $summary['evaluated']++;

            if ($dryRun) {
                $summary['created']++;

                continue;
            }

            $result = $this->recordAndNotify($candidate, $now);

            if ($result === 'notified') {
                $summary['created']++;
                $summary['notified']++;

                continue;
            }

            $summary[$result]++;
        }

        return $summary;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function candidates(CarbonInterface $now): array
    {
        return [
            ...$this->newLeadFollowUps($now),
            ...$this->milestonesDueSoon($now),
            ...$this->overdueMilestones($now),
            ...$this->publishedDocuments(),
            ...$this->publishedVisualAssets(),
            ...$this->quotesNearExpiration($now),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function newLeadFollowUps(CarbonInterface $now): array
    {
        $threshold = $now->subMinutes((int) config('fabstudio.automations.lead_followup_minutes', 15));

        return Lead::query()
            ->where('status', 'new')
            ->whereNull('converted_at')
            ->where('created_at', '<=', $threshold)
            ->latest()
            ->limit(50)
            ->get()
            ->map(function (Lead $lead): array {
                return $this->candidate(
                    automationKey: 'lead.follow_up',
                    subject: $lead,
                    title: 'Nuevo prospecto pendiente de contacto',
                    summary: 'El prospecto '.$lead->name.' esta pendiente de seguimiento comercial.',
                    actionUrl: url('/admin/leads/'.$lead->id),
                    severity: 'warning',
                    recipients: $this->adminRecipients(),
                    deduplicationKey: 'lead.follow_up:'.$lead->id,
                    payload: [
                        'lead_id' => $lead->id,
                        'source' => $lead->source,
                        'interest' => $lead->interest,
                    ],
                );
            })
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function milestonesDueSoon(CarbonInterface $now): array
    {
        $until = $now->addDays((int) config('fabstudio.automations.milestone_due_soon_days', 3));

        return Milestone::query()
            ->with('project.client')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->whereNull('completed_at')
            ->whereDate('due_at', '>=', $now->toDateString())
            ->whereDate('due_at', '<=', $until->toDateString())
            ->orderBy('due_at')
            ->limit(100)
            ->get()
            ->map(function (Milestone $milestone): array {
                return $this->candidate(
                    automationKey: 'milestone.due_soon',
                    subject: $milestone,
                    title: 'Hito proximo a vencer',
                    summary: 'El hito '.$milestone->title.' del proyecto '.$milestone->project?->name.' vence el '.$milestone->due_at?->format('Y-m-d').'.',
                    actionUrl: url('/admin/milestones/'.$milestone->id),
                    severity: 'warning',
                    recipients: $this->adminRecipients(),
                    deduplicationKey: 'milestone.due_soon:'.$milestone->id.':'.$milestone->due_at?->toDateString(),
                    payload: [
                        'milestone_id' => $milestone->id,
                        'project_id' => $milestone->project_id,
                        'due_at' => $milestone->due_at?->toDateString(),
                    ],
                );
            })
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function overdueMilestones(CarbonInterface $now): array
    {
        return Milestone::query()
            ->with('project.client')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->whereNull('completed_at')
            ->whereDate('due_at', '<', $now->toDateString())
            ->orderBy('due_at')
            ->limit(100)
            ->get()
            ->map(function (Milestone $milestone): array {
                return $this->candidate(
                    automationKey: 'milestone.overdue',
                    subject: $milestone,
                    title: 'Hito vencido',
                    summary: 'El hito '.$milestone->title.' del proyecto '.$milestone->project?->name.' esta vencido desde '.$milestone->due_at?->format('Y-m-d').'.',
                    actionUrl: url('/admin/milestones/'.$milestone->id),
                    severity: 'critical',
                    recipients: $this->adminRecipients(),
                    deduplicationKey: 'milestone.overdue:'.$milestone->id.':'.$milestone->due_at?->toDateString(),
                    payload: [
                        'milestone_id' => $milestone->id,
                        'project_id' => $milestone->project_id,
                        'due_at' => $milestone->due_at?->toDateString(),
                    ],
                );
            })
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function publishedDocuments(): array
    {
        return ProjectDocument::query()
            ->visibleToClients()
            ->with('project.client.users')
            ->latest('updated_at')
            ->limit(100)
            ->get()
            ->map(function (ProjectDocument $document): array {
                return $this->candidate(
                    automationKey: 'document.published',
                    subject: $document,
                    title: 'Nuevo documento disponible',
                    summary: 'FAB STUDIO publico el documento '.$document->title.' para el proyecto '.$document->project?->name.'.',
                    actionUrl: url('/portal/projects/'.$document->project_id.'/documents'),
                    severity: 'info',
                    recipients: $this->clientRecipients($document->project?->client),
                    deduplicationKey: 'document.published:'.$document->id,
                    payload: [
                        'document_id' => $document->id,
                        'project_id' => $document->project_id,
                        'category' => $document->category,
                    ],
                );
            })
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function publishedVisualAssets(): array
    {
        return VisualAsset::query()
            ->visibleToClients()
            ->with('project.client.users')
            ->latest('updated_at')
            ->limit(100)
            ->get()
            ->map(function (VisualAsset $asset): array {
                return $this->candidate(
                    automationKey: 'visual_asset.published',
                    subject: $asset,
                    title: 'Nuevo entregable visual disponible',
                    summary: 'FAB STUDIO publico '.$asset->title.' para el proyecto '.$asset->project?->name.'.',
                    actionUrl: url('/portal/projects/'.$asset->project_id.'/visuals'),
                    severity: 'info',
                    recipients: $this->clientRecipients($asset->project?->client),
                    deduplicationKey: 'visual_asset.published:'.$asset->id,
                    payload: [
                        'visual_asset_id' => $asset->id,
                        'project_id' => $asset->project_id,
                        'type' => $asset->type,
                    ],
                );
            })
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function quotesNearExpiration(CarbonInterface $now): array
    {
        $until = $now->addDays((int) config('fabstudio.automations.quote_validity_warning_days', 7));

        return Quote::query()
            ->with(['client', 'project'])
            ->whereIn('status', ['reviewed', 'approved', 'exported'])
            ->whereDate('valid_until', '>=', $now->toDateString())
            ->whereDate('valid_until', '<=', $until->toDateString())
            ->orderBy('valid_until')
            ->limit(100)
            ->get()
            ->map(function (Quote $quote): array {
                return $this->candidate(
                    automationKey: 'quote.validity_expiring',
                    subject: $quote,
                    title: 'Cotizacion proxima a vencer',
                    summary: 'La cotizacion '.$quote->quote_number.' para '.$quote->client?->name.' vence el '.$quote->valid_until?->format('Y-m-d').'.',
                    actionUrl: url('/admin/quotes/'.$quote->id),
                    severity: 'warning',
                    recipients: $this->adminRecipients(),
                    deduplicationKey: 'quote.validity_expiring:'.$quote->id.':'.$quote->valid_until?->toDateString(),
                    payload: [
                        'quote_id' => $quote->id,
                        'project_id' => $quote->project_id,
                        'client_id' => $quote->client_id,
                        'valid_until' => $quote->valid_until?->toDateString(),
                    ],
                );
            })
            ->all();
    }

    /**
     * @param  array{users: EloquentCollection<int, User>, emails: array<int, string>}  $recipients
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function candidate(
        string $automationKey,
        Model $subject,
        string $title,
        string $summary,
        string $actionUrl,
        string $severity,
        array $recipients,
        string $deduplicationKey,
        array $payload,
    ): array {
        return compact(
            'automationKey',
            'subject',
            'title',
            'summary',
            'actionUrl',
            'severity',
            'recipients',
            'deduplicationKey',
            'payload',
        );
    }

    /**
     * @param  array<string, mixed>  $candidate
     */
    private function recordAndNotify(array $candidate, CarbonInterface $now): string
    {
        $log = AutomationLog::query()->firstOrCreate(
            ['deduplication_key' => $candidate['deduplicationKey']],
            [
                'automation_key' => $candidate['automationKey'],
                'category' => 'operation',
                'severity' => $candidate['severity'],
                'status' => 'pending',
                'subject_type' => $candidate['subject']::class,
                'subject_id' => $candidate['subject']->getKey(),
                'channel' => 'mail',
                'title' => $candidate['title'],
                'summary' => $candidate['summary'],
                'payload' => [
                    ...$candidate['payload'],
                    'action_url' => $candidate['actionUrl'],
                    'recipients' => $this->recipientSummaries($candidate['recipients']),
                ],
                'processed_at' => $now,
            ],
        );

        if (! $log->wasRecentlyCreated) {
            return 'skipped';
        }

        if ($this->recipientCount($candidate['recipients']) === 0) {
            $log->update([
                'status' => 'skipped',
                'summary' => $candidate['summary'].' No se encontraron destinatarios configurados.',
                'processed_at' => $now,
            ]);

            return 'created';
        }

        $notification = new OperationalAutomationNotification(
            title: $candidate['title'],
            summary: $candidate['summary'],
            actionText: 'Abrir FAB STUDIO App',
            actionUrl: $candidate['actionUrl'],
        );

        Notification::send($candidate['recipients']['users'], $notification);

        foreach ($candidate['recipients']['emails'] as $email) {
            Notification::route('mail', $email)->notify($notification);
        }

        $log->update([
            'status' => 'sent',
            'notified_at' => $now,
        ]);

        return 'notified';
    }

    /**
     * @return array{users: EloquentCollection<int, User>, emails: array<int, string>}
     */
    private function adminRecipients(): array
    {
        $users = User::query()
            ->whereHas('roles', fn ($query) => $query->whereIn('name', ['super_admin', 'admin']))
            ->get();

        $emails = [];
        $operationsEmail = (string) config('fabstudio.operations_email');

        if ($users->isEmpty() && filled($operationsEmail)) {
            $emails[] = $operationsEmail;
        }

        return [
            'users' => $users,
            'emails' => array_values(array_unique(array_filter($emails))),
        ];
    }

    /**
     * @return array{users: EloquentCollection<int, User>, emails: array<int, string>}
     */
    private function clientRecipients(?Client $client): array
    {
        if (! $client) {
            return [
                'users' => User::query()->whereRaw('1 = 0')->get(),
                'emails' => [],
            ];
        }

        $users = $client->users()
            ->whereNotNull('email')
            ->get();

        $userEmails = $users->pluck('email')->filter()->all();
        $emails = [];

        if ($client->email && ! in_array($client->email, $userEmails, true)) {
            $emails[] = $client->email;
        }

        return [
            'users' => $users,
            'emails' => array_values(array_unique(array_filter($emails))),
        ];
    }

    /**
     * @param  array{users: EloquentCollection<int, User>, emails: array<int, string>}  $recipients
     * @return array<int, string>
     */
    private function recipientSummaries(array $recipients): array
    {
        return collect($recipients['users'])
            ->map(fn (User $user): string => 'user:'.$user->id.':'.Str::lower($user->email))
            ->merge(collect($recipients['emails'])->map(fn (string $email): string => 'mail:'.Str::lower($email)))
            ->values()
            ->all();
    }

    /**
     * @param  array{users: EloquentCollection<int, User>, emails: array<int, string>}  $recipients
     */
    private function recipientCount(array $recipients): int
    {
        return $recipients['users']->count() + count($recipients['emails']);
    }
}
