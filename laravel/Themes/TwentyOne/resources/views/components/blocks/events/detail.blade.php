@php
/** @var \Modules\Blog\Models\Event $event */
@endphp

<article class="event-detail">
    <header class="event-detail__header">
        <h1 class="event-detail__title">{{ $event->title }}</h1>
        <div class="event-detail__meta">
            <span>{{ $event->published_at?->format('d M Y') }}</span>
            <span>{{ $event->location }}</span>
        </div>
    </header>

    <div class="event-detail__body">
        @php
            echo $event->content_blocks ?? $event->body ?? '';
        @endphp
    </div>
</article>