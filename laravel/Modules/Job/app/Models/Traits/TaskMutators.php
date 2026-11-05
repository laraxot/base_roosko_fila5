<?php

declare(strict_types=1);

namespace Modules\Job\Models\Traits;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Modules\Job\Database\Factories\TaskFactory;
use Modules\Job\Models\Traits\FrontendSortable;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Models\Traits\HasXotFactory;
use Webmozart\Assert\Assert;
use function Safe\json_decode;
use Modules\Job\Models\Result;

trait TaskMutators
{
    function getActivatedAttribute(): bool
        {
            return $this->getActivated();
        }

    function getActivated(): bool
        {
            return (bool) $this->is_active;
        }

    function getUpcomingAttribute(): string
        {
            return $this->getUpcoming();
        }

    function getUpcoming(): string
        {
            // return CronExpression::factory($this->getCronExpression())->getNextRunDate()->format('Y-m-d H:i:s');
            return 'preso';
        }

    function getLastResultAttribute(): ?Result
        {
            return $this->getLastResult();
        }

    function getLastResult(): ?Result
        {
            $res = $this->results()->orderBy('id', 'desc')->first();
            if ($res === null) {
                return null;
            }
            Assert::isInstanceOf($res, Result::class);
    
            return $res;
        }

    function getAverageRuntimeAttribute(): float
        {
            return $this->getAverageRuntime();
        }

    function getAverageRuntime(): float
        {
            /**
             * @var float $avg_duration
             */
            $avg_duration = $this->results()->avg('duration');
    
            return (float) $avg_duration;
        }

=======
use Modules\Job\Models\Result;
use Webmozart\Assert\Assert;

trait TaskMutators
{
    public function getActivatedAttribute(): bool
    {
        return $this->getActivated();
    }

    public function getActivated(): bool
    {
        return (bool) $this->is_active;
    }

    public function getUpcomingAttribute(): string
    {
        return $this->getUpcoming();
    }

    public function getUpcoming(): string
    {
        // return CronExpression::factory($this->getCronExpression())->getNextRunDate()->format('Y-m-d H:i:s');
        return 'preso';
    }

    public function getLastResultAttribute(): ?Result
    {
        return $this->getLastResult();
    }

    public function getLastResult(): ?Result
    {
        $res = $this->results()->orderBy('id', 'desc')->first();
        if ($res === null) {
            return null;
        }
        Assert::isInstanceOf($res, Result::class);

        return $res;
    }

    public function getAverageRuntimeAttribute(): float
    {
        return $this->getAverageRuntime();
    }

    public function getAverageRuntime(): float
    {
        /**
         * @var float $avg_duration
         */
        $avg_duration = $this->results()->avg('duration');

        return (float) $avg_duration;
    }
>>>>>>> laraxot/dev
}
