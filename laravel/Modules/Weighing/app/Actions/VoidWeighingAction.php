<?php

declare(strict_types=1);

namespace Modules\Weighing\Actions;

use Modules\Weighing\Enums\WeighingStatusEnum;
use Modules\Weighing\Models\Weighing;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Annulla un ticket aperto o chiuso registrando data e motivo. I pesi restano per l'audit.
 */
class VoidWeighingAction
{
    use QueueableAction;

    public function execute(Weighing $weighing, string $reason): Weighing
    {
        $reason = trim($reason);
        Assert::stringNotEmpty($reason, (string) __('weighing::exceptions.void_reason_required'));
        Assert::true(
            $weighing->status->canTransitionTo(WeighingStatusEnum::VOIDED),
            (string) __('weighing::exceptions.invalid_transition', [
                'ticket' => $weighing->ticket_number,
                'from' => $weighing->status->getLabel(),
                'to' => WeighingStatusEnum::VOIDED->getLabel(),
            ]),
        );

        $weighing->status = WeighingStatusEnum::VOIDED;
        $weighing->voided_at = now()->toImmutable();
        $weighing->void_reason = $reason;
        $weighing->save();

        return $weighing;
    }
}
