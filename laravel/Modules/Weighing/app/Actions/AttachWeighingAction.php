<?php

declare(strict_types=1);

namespace Modules\Weighing\Actions;

use Illuminate\Database\Eloquent\Model;
use Modules\Weighing\Enums\WeighingStatusEnum;
use Modules\Weighing\Models\Weighing;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Collega la pesata al modello di dominio (morph `weighable`), anche dopo la chiusura.
 * Una pesata annullata non si collega.
 */
class AttachWeighingAction
{
    use QueueableAction;

    public function execute(Weighing $weighing, Model $weighable): Weighing
    {
        Assert::true($weighable->exists, (string) __('weighing::exceptions.weighable_not_saved'));
        Assert::true($weighing->status !== WeighingStatusEnum::VOIDED, (string) __('weighing::exceptions.weighing_voided', [
            'ticket' => $weighing->ticket_number,
        ]));

        $weighing->weighable()->associate($weighable);
        $weighing->save();

        return $weighing;
    }
}
