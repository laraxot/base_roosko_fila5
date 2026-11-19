<div>
    <div class="flex gap-4 mb-4">
        <button
            wire:click="$set('status', 'Active')"
            class="px-3 py-1 rounded-full text-xs {{ $status === 'Active' ? 'bg-blue-100 text-blue-700' : 'text-gray-600' }}">
            {{ __('pub_theme::widgets_ratings.positions.active.label') }}
        </button>

        <button
            wire:click="$set('status', 'Closed')"
            class="px-3 py-1 rounded-full text-xs {{ $status === 'Closed' ? 'bg-blue-100 text-blue-700' : 'text-gray-600' }}">
            {{ __('pub_theme::widgets_ratings.positions.closed.label') }}
        </button>

        <button
            wire:click="$set('status', 'Open')"
            class="px-3 py-1 rounded-full text-xs {{ $status === 'Open' ? 'bg-blue-100 text-blue-700' : 'text-gray-600' }}">
            {{ __('pub_theme::widgets_ratings.positions.open_orders.label') }}
        </button>
    </div>

    {{ $this->table }}
</div>
