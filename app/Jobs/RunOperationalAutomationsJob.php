<?php

namespace App\Jobs;

use App\Services\Automation\OperationalAutomationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RunOperationalAutomationsJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    /**
     * @var array<int, int>
     */
    public array $backoff = [60, 180];

    public function __construct()
    {
        $this->onQueue('automations');
    }

    public function handle(OperationalAutomationService $service): void
    {
        $service->run();
    }
}
