{{-- Componente riutilizzabile per i badge di status dei predict --}}
@props([
    'status' => 'draft',
    'withAnimation' => false
])

@php
    $statusConfig = match($status) {
        'open' => [
            'classes' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'iconColor' => 'bg-green-400',
            'label' => __('predict::common.labels.status.open.label'),
            'showAnimation' => true,
        ],
        // Chiuso non è un errore: ambra (in attesa), non rosso.
        'closed' => [
            'classes' => 'bg-amber-100 text-amber-900 dark:bg-amber-900 dark:text-amber-100',
            'iconColor' => 'bg-amber-500',
            'label' => __('predict::common.labels.status.closed.label'),
            'showAnimation' => false,
        ],
        'resolved' => [
            'classes' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
            'iconColor' => 'bg-gray-400',
            'label' => __('predict::common.labels.status.resolved.label'),
            'showAnimation' => false,
        ],
        default => [
            'classes' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'iconColor' => 'bg-yellow-400',
            'label' => ucfirst($status),
            'showAnimation' => false,
        ],
    };
@endphp

<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusConfig['classes'] }}">
    <span class="w-2 h-2 {{ $statusConfig['iconColor'] }} rounded-full mr-1 {{ $withAnimation && $statusConfig['showAnimation'] ? 'animate-pulse' : '' }}"></span>
    {{ $statusConfig['label'] }}
</span> 