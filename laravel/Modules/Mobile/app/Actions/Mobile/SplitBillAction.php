<?php

declare(strict_types=1);

namespace Modules\Mobile\Actions\Mobile;

use Illuminate\Support\Facades\DB;
use Modules\Mobile\Models\OrderQueue;
use Spatie\QueueableAction\QueueableAction;

/**
 * Action for splitting bill on mobile device.
 * Supports equal split, custom amounts, or item-based split.
 */
class SplitBillAction
{
    use QueueableAction;

    /**
     * @param array<int, array{notes?: string, amount?: float|int, item_ids?: array<int, int>}> $splits
     * @param array<int, string>|null $paymentMethods
     * @return list<array{order_id: string, amount: float, method: string, notes: string}>
     */
    public function execute(
        int $orderId,
        string $splitType,
        array $splits,
        ?array $paymentMethods = null
    ): array {
        $order = OrderQueue::query()->findOrFail($orderId);

        if ($order->getAttribute('status') === 'paid') {
            throw new \InvalidArgumentException('Order already paid');
        }

        $orderData = $order->order_data;
        $rawTotal = $orderData['total'] ?? 0;
        $totalAmount = is_numeric($rawTotal) ? (float) $rawTotal : 0.0;

        return DB::transaction(function () use ($order, $orderData, $splitType, $splits, $paymentMethods, $totalAmount): array {
            $results = [];

            switch ($splitType) {
                case 'equal':
                    $count = max(1, count($splits));
                    $amountPerPerson = round((float) $totalAmount / $count, 2);
                    foreach ($splits as $index => $split) {
                        $payment = [
                            'order_id' => $order->id,
                            'amount' => $amountPerPerson,
                            'method' => $paymentMethods[$index] ?? 'cash',
                            'notes' => $split['notes'] ?? ('Split '.($index + 1).'/'.$count),
                        ];
                        $results[] = $payment;
                    }
                    break;

                case 'custom':
                    $sum = array_sum(array_map(static fn (array $split): float => (float) ($split['amount'] ?? 0), $splits));
                    if (abs($sum - (float) $totalAmount) > 0.01) {
                        throw new \InvalidArgumentException('Split amounts do not match total');
                    }
                    foreach ($splits as $index => $split) {
                        $payment = [
                            'order_id' => $order->id,
                            'amount' => (float) ($split['amount'] ?? 0),
                            'method' => $paymentMethods[$index] ?? 'cash',
                            'notes' => $split['notes'] ?? ('Custom split '.($index + 1)),
                        ];
                        $results[] = $payment;
                    }
                    break;

                case 'items':
                    foreach ($splits as $index => $split) {
                        $amount = 0;
                        foreach (($split['item_ids'] ?? []) as $itemId) {
                            $items = $orderData['items'] ?? [];
                            $items = is_array($items) ? $items : [];
                            $item = null;
                            foreach ($items as $candidate) {
                                if (is_array($candidate) && ($candidate['id'] ?? null) === $itemId) {
                                    $item = $candidate;
                                    break;
                                }
                            }
                            if (is_array($item)) {
                                $quantity = $item['quantity'] ?? 0;
                                $unitPrice = $item['unit_price'] ?? $item['price'] ?? 0;
                                $amount += (is_numeric($quantity) ? (float) $quantity : 0.0)
                                    * (is_numeric($unitPrice) ? (float) $unitPrice : 0.0);
                            }
                        }
                        $payment = [
                            'order_id' => $order->id,
                            'amount' => round($amount, 2),
                            'method' => $paymentMethods[$index] ?? 'cash',
                            'notes' => $split['notes'] ?? ('Items split '.($index + 1)),
                        ];
                        $results[] = $payment;
                    }
                    break;

                default:
                    throw new \InvalidArgumentException("Unknown split type: {$splitType}");
            }

            // OrderQueue non ha uno stato "paid": le quote restano nel payload dell'ordine in coda.
            $order->update(['order_data' => [...$orderData, 'splits' => $results]]);

            return $results;
        });
    }
}
