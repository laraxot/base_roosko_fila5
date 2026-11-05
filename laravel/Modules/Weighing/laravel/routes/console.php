<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;
use Modules\User\Models\AuthenticationLog;

// Pulizia periodica dei log (giorni in activitylog.clean_after_days e gdpr.retention.authentication_log_days).
// Le pianificazioni dei moduli di dominio (es. verifica degli esiti RENTRI) sono registrate dai loro provider.
// Serve il cron: * * * * * cd /path/laravel && php artisan schedule:run
Schedule::command('activitylog:clean --force')->daily()->withoutOverlapping(60);
Schedule::command('model:prune', ['--model' => [AuthenticationLog::class]])->daily()->withoutOverlapping(60);
