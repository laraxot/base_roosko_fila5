<?php

declare(strict_types=1);

use Modules\Mobile\Actions\Mobile\TakeOrderAction;

test('take order rejects a payload without waiter session or table', function (): void {
    $action = new TakeOrderAction();

    expect(fn () => $action->execute([]))->toThrow(InvalidArgumentException::class)
        ->and(fn () => $action->execute(['waiter_session_id' => 'session-1']))->toThrow(InvalidArgumentException::class)
        ->and(fn () => $action->execute(['table_id' => 3]))->toThrow(InvalidArgumentException::class);
});
