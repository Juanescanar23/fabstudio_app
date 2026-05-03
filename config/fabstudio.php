<?php

return [
    'operations_email' => env('FABSTUDIO_OPERATIONS_EMAIL', env('MAIL_FROM_ADDRESS')),

    'automations' => [
        'enabled' => (bool) env('FABSTUDIO_AUTOMATIONS_ENABLED', true),
        'lead_followup_minutes' => (int) env('FABSTUDIO_LEAD_FOLLOWUP_MINUTES', 15),
        'milestone_due_soon_days' => (int) env('FABSTUDIO_MILESTONE_DUE_SOON_DAYS', 3),
        'quote_validity_warning_days' => (int) env('FABSTUDIO_QUOTE_VALIDITY_WARNING_DAYS', 7),
    ],

    'backups' => [
        'provider' => env('FABSTUDIO_BACKUP_PROVIDER'),
        'retention_days' => (int) env('FABSTUDIO_BACKUP_RETENTION_DAYS', 7),
        'owner_email' => env('FABSTUDIO_BACKUP_OWNER_EMAIL', env('FABSTUDIO_OPERATIONS_EMAIL')),
    ],
];
