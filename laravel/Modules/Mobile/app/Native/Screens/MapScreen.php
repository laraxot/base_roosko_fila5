<?php

declare(strict_types=1);

namespace Modules\Mobile\Native\Screens;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class MapScreen extends NativeComponent
{
    public string $title = 'Mappa Segnalazioni';

    /** @var array<string, mixed> */
    public array $filters = [
        'types' => [],
        'statuses' => [],
        'date_range' => null,
    ];

    public int $markerCount = 0;

    public bool $isLoading = true;

    public string $error = '';

    public function mount(): void
    {
        $this->loadMarkers();
    }

    public function loadMarkers(): void
    {
        $this->isLoading = true;
        $this->error = '';

        // In produzione: usa nativephp_call per fetch nativo o HTTP client
        // Per ora simuliamo il caricamento
        $this->markerCount = 27; // Dal seed demo

        $this->isLoading = false;
    }

    /** @param array<string, mixed> $filters */
    public function applyFilters(array $filters): void
    {
        $this->filters = array_merge($this->filters, $filters);
        $this->loadMarkers();
    }

    public function clearFilters(): void
    {
        $this->filters = [
            'types' => [],
            'statuses' => [],
            'date_range' => null,
        ];
        $this->loadMarkers();
    }

    public function render(): View
    {
        /** @var view-string $view */
        $view = 'mobile::native.screens.map';

        return view($view, [
            'title' => $this->title,
            'markerCount' => $this->markerCount,
            'isLoading' => $this->isLoading,
            'error' => $this->error,
            'filters' => $this->filters,
        ]);
    }
}
