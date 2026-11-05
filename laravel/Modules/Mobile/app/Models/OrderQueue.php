<?php

declare(strict_types=1);

namespace Modules\Mobile\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Xot\Models\XotBaseModel;

/**
 * @method static \Illuminate\Database\Eloquent\Factories\Factory<static> factory($count = null, $state = [])
 * @property string $id
 * @property int $table_id
 * @property array<string, mixed> $order_data
 * @property int $sync_attempts
 */
class OrderQueue extends XotBaseModel
{
    use HasUuids;

    protected $table = 'mobile_order_queue';

    protected $fillable = [
        'waiter_session_id',
        'table_id',
        'order_data',
        'status',
        'sync_attempts',
        'last_sync_at',
        'error_message',
    ];

    protected $casts = [
        'order_data' => 'array',
        'sync_attempts' => 'integer',
        'last_sync_at' => 'datetime',
    ];

    public const string STATUS_PENDING = 'pending';
    public const string STATUS_SYNCING = 'syncing';
    public const string STATUS_SYNCED = 'synced';
    public const string STATUS_FAILED = 'failed';

    /** @return BelongsTo<WaiterSession, $this> */
    public function waiterSession(): BelongsTo
    {
        return $this->belongsTo(WaiterSession::class, 'waiter_session_id');
    }

    /**
     * @param Builder<self> $query
     * @return Builder<self>
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * @param Builder<self> $query
     * @return Builder<self>
     */
    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_FAILED);
    }
}
