<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Modules\Fixcity\Actions\Privacy\PurgeDeletedAccountsAction;
use Modules\User\Models\AuthenticationLog;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Retention promised by the privacy notice (config/local/fixcity/lang/it/policy.md, "Periodo di conservazione").
// Periods in days: activitylog.clean_after_days, gdpr.retention.authentication_log_days, gdpr.retention.deleted_account_days.
Schedule::command('activitylog:clean --force')->daily()->withoutOverlapping();
Schedule::command('model:prune', ['--model' => [AuthenticationLog::class]])->daily()->withoutOverlapping();
Schedule::call(static fn (): int => app(PurgeDeletedAccountsAction::class)->execute())
    ->name('purge-deleted-accounts')
    ->daily()
    ->withoutOverlapping();
