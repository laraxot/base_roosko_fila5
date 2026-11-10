<?php

declare(strict_types=1);

namespace Modules\Weighing\Actions;

use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Expression;
use Modules\Weighing\Models\WeighingSequence;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Prossimo numero ticket `W-{anno}-{progressivo a 6 cifre}`.
 *
 * Una sola istruzione crea o incrementa il contatore dell'anno: `INSERT ... ON DUPLICATE KEY UPDATE
 * last_number = last_number + 1` (`upsert`, unique su `year`; `ON CONFLICT DO UPDATE` su sqlite/pgsql).
 * InnoDB prende subito il lock esclusivo sulla riga: niente lock condiviso da convertire in esclusivo
 * come con il vecchio `insertOrIgnore` + `SELECT ... FOR UPDATE`, che con due pesate concorrenti
 * andava in deadlock (audit database R10). Il valore si rilegge nella stessa transazione, a riga bloccata.
 *
 * Dentro la transazione di chi salva la pesata il lock resta fino al commit: due pesate concorrenti non
 * hanno lo stesso numero e un rollback non lascia buchi. Se la chiamata non e' dentro una transazione,
 * ne apre una con 3 tentativi sugli errori di concorrenza (deadlock, serializzazione); dentro una
 * transazione esterna il retry spetta a chi la apre (MySQL annulla l'intera transazione sul deadlock).
 */
class GenerateWeighingTicketNumberAction
{
    use QueueableAction;

    public const int ATTEMPTS = 3;

    public function execute(?CarbonImmutable $at = null): string
    {
        $year = ($at ?? now()->toImmutable())->year;
        $connection = (new WeighingSequence)->getConnection();

        $next = static function () use ($year): string {
            WeighingSequence::query()->upsert(
                [['year' => $year, 'last_number' => 1]],
                ['year'],
                ['last_number' => new Expression('last_number + 1')],
            );

            $number = WeighingSequence::query()
                ->where('year', $year)
                ->lockForUpdate()
                ->value('last_number');
            Assert::integerish($number);

            return sprintf('W-%d-%06d', $year, (int) $number);
        };

        return $connection->transactionLevel() > 0
            ? $next()
            : $connection->transaction($next, self::ATTEMPTS);
    }
}
