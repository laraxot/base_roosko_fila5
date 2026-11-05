<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Contracts\TenantContract;
use Modules\User\Models\Traits\BaseTenantRelationsTrait;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Contracts\UserContract;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * Modules\User\Models\Tenant.
 *
 * @method static Builder|Tenant newModelQuery()
 * @method static Builder|Tenant newQuery()
 * @method static Builder|Tenant query()
 *
 * @property EloquentCollection<int, Model&UserContract> $members
 * @property int|null $members_count
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 *
 * @mixin \Eloquent
 */
abstract class BaseTenant extends BaseModel implements HasAvatar, HasMedia, TenantContract
{
    use HasSlug {
        HasSlug::resolveRouteBindingQuery as resolveRouteBindingQueryFromSlug;
    }

    /** La chiave è una stringa: senza un id esplicito viene generato un UUID. */
    use HasUuids {
        HasUuids::resolveRouteBindingQuery as resolveRouteBindingQueryFromUuids;
    }

    use InteractsWithMedia;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Risolve la collisione tra HasSlug e HasUuids su resolveRouteBindingQuery:
     * valida l'UUID per le chiavi univoche (HasUuids), poi risolve lo slug (HasSlug).
     *
     * @param  Builder<Model>|Model  $query
     * @param  string|int  $value
     * @param  string|null  $field
     * @return Builder<Model>
     */
    public function resolveRouteBindingQuery($query, $value, $field = null): Builder
    {
        $query = $this->resolveRouteBindingQueryFromUuids($query, $value, $field);

        return $this->resolveRouteBindingQueryFromSlug($query, $value, $field);
    }

    /** @var list<string> */
    protected $fillable = [
        'id',
        'name',
        'slug',
        'email_address',
        'phone',
        'mobile',
        'address',
        'primary_color',
        'secondary_color',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    /**
     * Ottiene l'URL dell'avatar del tenant per Filament.
     *
     * @return string|null URL dell'avatar o null se non presente
     */
    public function getFilamentAvatarUrl(): ?string
    {
        // return $this->avatar_url;
        return $this->getFirstMediaUrl('avatar');
    }

    // public function getSlugAttribute(?string $value): ?string
    // {
    //     if(is_string($value) || $this->getKey() == null) {
    //         return $value;
    //     }
    //     $slug = Str::slug($this->name);
    //     $this->slug = $slug;
    //     $this->save();
    //     return $slug;
    // }

    use BaseTenantRelationsTrait;
}
