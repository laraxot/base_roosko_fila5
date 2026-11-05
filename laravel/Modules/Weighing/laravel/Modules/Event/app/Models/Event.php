<?php

namespace Modules\Event\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Modules\Blog\Models\Category;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\CommentNotificationSubscription;
use Modules\User\Models\Profile;
use Safe\DateTime;

/**
 * Event model representing a scheduled event.
 *
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $description_long
 * @property string $image
 * @property int|null $category_id
 * @property Carbon $event_start_date
 * @property Carbon $event_end_date
 * @property bool $is_wagerable
 * @property Carbon|null $bet_end_date
 * @property bool $is_featured
 * @property bool $show_on_homepage
 * @property bool|string $main_image_upload
 * @property string $main_image_url
 * @property Carbon|null $published_at
 * @property Carbon|null $closed_at
 * @property string $content_type
 * @property string $type
 * @property bool $type_is_wagerable
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection<int, Comment> $comments
 * @property Collection<int, CommentNotificationSubscription> $notificationSubscriptions
 * @property Category|null $category
 * @property Profile|null $author
 */
final class Event extends Model
{
    protected $table = 'events';

    protected $fillable = [
        'uuid',
        'user_id',
        'title',
        'slug',
        'description',
        'description_long',
        'image',
        'category_id',
        'event_start_date',
        'event_end_date',
        'is_wagerable',
        'bet_end_date',
        'is_featured',
        'show_on_homepage',
        'main_image_upload',
        'main_image_url',
        'published_at',
        'closed_at',
        'content_type',
        'type',
        'type_is_wagerable',
        'created_at',
        'updated_at',
    ];

    /**
     * Relationships.
     */
    /** @return MorphMany<Comment, $this> */
    public function comments(): MorphMany
    {
        return $this->morphMany(
            Comment::class,
            'commentable'
        );
    }

    /** @return MorphMany<CommentNotificationSubscription, $this> */
    public function notificationSubscriptions(): MorphMany
    {
        return $this->morphMany(
            CommentNotificationSubscription::class,
            'commentable'
        );
    }

    /** @return BelongsTo<Category, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'category_id'
        );
    }

    public function type(): string
    {
        /** @var string $type */
        $type = $this->type;

        return $type;
    }

    public function getTitle(): string
    {
        /** @var string $title */
        $title = $this->title;

        return $title;
    }

    public function getShortBody(): string
    {
        return Str::words(
            strip_tags((string) $this->description),
            30
        );
    }

    public function getFormattedDate(): string
    {
        return $this->getPublishedAt()->format('F jS Y');
    }

    public function getThumbnail(): ?string
    {
        return $this->getMainImage() ?: null;
    }

    public function getHumanReadTime(): string
    {
        $words = Str::wordCount(strip_tags($this->description));
        $minutes = max(1, (int) ceil($words / 200));

        return $minutes.' '.str('min')->plural($minutes).', '.$words.' '.str('word')->plural($words);
    }

    public function getMainImage(): string
    {
        return $this->main_image_url ?: $this->image;
    }

    /** @return BelongsTo<Profile, $this> */
    public function author(): BelongsTo
    {
        return $this->belongsTo(
            Profile::class,
            'user_id'
        );
    }

    public function getType(): string
    {
        /** @var string $type */
        $type = $this->type;

        return $type;
    }

    public function isWagerable(): bool
    {
        $type_is_wagerable = $this->type_is_wagerable;

        return $type_is_wagerable;
    }

    public function isFeatured(): bool
    {
        $is_featured = $this->is_featured;

        return $is_featured;
    }

    public function showOnHomepage(): bool
    {
        $show_on_homepage = $this->show_on_homepage;

        return $show_on_homepage;
    }

    public function getMainImageUpload(): string
    {
        $main_image_upload = $this->main_image_upload;

        return (string) $main_image_upload;
    }

    public function getMainImageUrl(): string
    {
        $main_image_url = $this->main_image_url;

        return $main_image_url;
    }

    public function getPublishedAt(): \DateTime
    {
        $published_at = $this->published_at;

        return $published_at instanceof Carbon ? $published_at->toDateTime() : new DateTime;
    }

    public function getClosedAt(): \DateTime
    {
        $closed_at = $this->closed_at;

        return $closed_at instanceof Carbon ? $closed_at->toDateTime() : new DateTime;
    }

    public function getContentType(): string
    {
        $content_type = $this->content_type;

        return $content_type;
    }

    /**
     * Casts for mass assignment.
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'uuid' => 'string',
            'user_id' => 'integer',
            'title' => 'string',
            'slug' => 'string',
            'description' => 'string',
            'description_long' => 'string',
            'image' => 'string',
            'category_id' => 'integer',
            'event_start_date' => 'datetime',
            'event_end_date' => 'datetime',
            'is_wagerable' => 'boolean',
            'bet_end_date' => 'datetime',
            'is_featured' => 'boolean',
            'show_on_homepage' => 'boolean',
            'main_image_upload' => 'boolean',
            'main_image_url' => 'string',
            'published_at' => 'datetime',
            'closed_at' => 'datetime',
            'content_type' => 'string',
            'type' => 'string',
            'type_is_wagerable' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
