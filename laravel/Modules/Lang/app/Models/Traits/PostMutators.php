<?php

declare(strict_types=1);

namespace Modules\Lang\Models\Traits;

use Illuminate\Support\Str;

trait PostMutators
{
    public function setTitleAttribute(string $value): void
    {
        $this->attributes['title'] = $value;
        $this->attributes['guid'] = Str::slug($value);
    }

    public function getTitleAttribute(?string $value): ?string
    {
        if ($value !== null) {
            return $value;
        }

        $value = $this->getTitle();
        $this->title = $value;

        if ($this->getKey() !== null) {
            $this->update([
                'title' => $value,
            ]);
        }

        return $value;
    }

    public function getGuidAttribute(?string $value): ?string
    {
        if (is_string($value) && $value !== '' && ! str_contains($value, ' ')) {
            return $value;
        }

        $value = $this->getGuid();
        $this->guid = $value;

        if ($this->getKey() !== null) {
            $this->update([
                'guid' => $value,
            ]);
        }

        return $value;
    }

    public function getTxtAttribute(?string $value): ?string
    {
        return $this->getTxt($value);
    }

    public function getTxt(?string $value): ?string
    {
        return $value ?? '';
    }

    /**
     * Titolo calcolato da post_type e post_id. Calcolo puro: nessuna scrittura.
     */
    public function getTitle(): string
    {
        if (isset($this->attributes['post_type']) && '' !== $this->attributes['post_type'] && '0' !== $this->attributes['post_type']) {
            $postType = isset($this->attributes['post_type']) && is_string($this->attributes['post_type'])
                ? $this->attributes['post_type']
                : '';
            $postId = isset($this->attributes['post_id']) && is_scalar($this->attributes['post_id'])
                ? ((string) $this->attributes['post_id'])
                : '';

            return $postType.' '.$postId;
        }

        $postType = is_string($this->post_type) ? $this->post_type : '';
        $postId = is_scalar($this->post_id) ? ((string) $this->post_id) : '';

        return $postType.' '.$postId;
    }

    /**
     * Slug del guid a partire dal titolo. Calcolo puro: nessuna scrittura.
     */
    public function getGuid(): string
    {
        $value = $this->title;
        if ('' === $value) {
            $postType = isset($this->attributes['post_type']) && is_string($this->attributes['post_type'])
                ? $this->attributes['post_type']
                : '';
            $postId = isset($this->attributes['post_id']) && is_scalar($this->attributes['post_id'])
                ? ((string) $this->attributes['post_id'])
                : '';
            $value = $postType.' '.$postId;
        }
        if (null === $value) {
            $value = 'u-'.random_int(1, 1000);
        }

        return Str::slug($value);
    }
}
