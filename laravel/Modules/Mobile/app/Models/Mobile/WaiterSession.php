<?php

declare(strict_types=1);

namespace Modules\Mobile\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Modules\Xot\Models\XotBaseModel;

/**
 * @method static \Illuminate\Database\Eloquent\Factories\Factory<static> factory($count = null, $state = [])
 * @property string $id
 * @property string $token
 * @property string|null $user_id
 * @property string|null $device_id
 * @property string|null $shift_id
 * @property float|null $location_lat
 * @property float|null $location_lng
 * @property \Illuminate\Support\Carbon|null $last_active_at
 */
class WaiterSession extends XotBaseModel
{
    use HasUuids;

    protected $table = 'mobile_waiter_sessions';

    protected $fillable = [
        'user_id',
        'device_id',
        'device_name',
        'platform',
        'token',
        'biometric_enabled',
        'last_active_at',
        'is_active',
        'location_lat',
        'location_lng',
        'shift_id',
        'notes',
    ];

    protected $casts = [
        'biometric_enabled' => 'boolean',
        'is_active' => 'boolean',
        'last_active_at' => 'datetime',
        'location_lat' => 'float',
        'location_lng' => 'float',
    ];

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /**
     * @param Builder<self> $query
     * @return Builder<self>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * @param Builder<self> $query
     * @return Builder<self>
     */
    public function scopeForDevice(Builder $query, string $deviceId): Builder
    {
        return $query->where('device_id', $deviceId);
    }
}
