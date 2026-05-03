<?php

use App\Jobs\RunOperationalAutomationsJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new RunOperationalAutomationsJob)
    ->name('fabstudio-operational-automations')
    ->everyFifteenMinutes()
    ->withoutOverlapping(10)
    ->onOneServer();

Schedule::command('app:readiness-check')
    ->dailyAt('06:00')
    ->withoutOverlapping(10)
    ->onOneServer();
