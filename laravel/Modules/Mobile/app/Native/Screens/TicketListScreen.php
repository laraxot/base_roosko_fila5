<?php

declare(strict_types=1);

namespace Modules\Mobile\Native\Screens;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class TicketListScreen extends NativeComponent
{
    public string $title = 'Le Mie Segnalazioni';

    /** @var array<int, array<string, mixed>> */
    public array $tickets = [];

    /** @var array<string, mixed> */
    public array $filters = [
        'status' => 'all',
        'search' => '',
        'type' => '',
    ];

    public bool $isLoading = true;

    public bool $isRefreshing = false;

    public string $error = '';

    public int $currentPage = 1;

    public bool $hasMore = true;

    public function mount(): void
    {
        $this->loadTickets();
    }

    public function loadTickets(): void
    {
        $this->isLoading = true;
        $this->error = '';

        // Simulazione - in produzione chiama API o database locale
        $this->tickets = $this->getDemoTickets();
        $this->hasMore = false;
        $this->isLoading = false;
        $this->isRefreshing = false;
    }

    public function pullToRefresh(): void
    {
        $this->isRefreshing = true;
        $this->currentPage = 1;
        $this->loadTickets();
    }

    public function loadMore(): void
    {
        if ($this->hasMore && ! $this->isLoading) {
            $this->currentPage++;
            $this->loadTickets();
        }
    }

    public function applyFilter(string $filter, mixed $value): void
    {
        $this->filters[$filter] = $value;
        $this->currentPage = 1;
        $this->loadTickets();
    }

    public function clearFilters(): void
    {
        $this->filters = [
            'status' => 'all',
            'search' => '',
            'type' => '',
        ];
        $this->currentPage = 1;
        $this->loadTickets();
    }

    public function navigateToDetail(string $ticketId): void
    {
        $this->navigate('ticket-detail', ['ticketId' => $ticketId]);
    }

    public function navigateToCreate(): void
    {
        $this->navigate('create-ticket');
    }

    /** @return array<int, array<string, mixed>> */
    private function getDemoTickets(): array
    {
        return [
            [
                'id' => 'DMO-001',
                'title' => 'Buca in Via Roma',
                'type' => 'strade',
                'type_label' => 'Strade e Marciapiedi',
                'status' => 'in_progress',
                'status_label' => 'In Corso',
                'city' => 'Milano',
                'address' => 'Via Roma, 15',
                'created_at' => '2026-09-25T10:30:00Z',
                'updated_at' => '2026-09-26T14:20:00Z',
                'photo' => null,
            ],
            [
                'id' => 'DMO-002',
                'title' => 'Lampione spento Piazza Duomo',
                'type' => 'illuminazione',
                'type_label' => 'Illuminazione Pubblica',
                'status' => 'resolved',
                'status_label' => 'Risolto',
                'city' => 'Milano',
                'address' => 'Piazza Duomo',
                'created_at' => '2026-09-24T08:15:00Z',
                'updated_at' => '2026-09-25T16:45:00Z',
                'photo' => 'ticket_002.jpg',
            ],
            [
                'id' => 'DMO-003',
                'title' => 'Rifiuti abbandonati Parco Sempione',
                'type' => 'rifiuti',
                'type_label' => 'Rifiuti e Pulizia',
                'status' => 'open',
                'status_label' => 'Aperto',
                'city' => 'Milano',
                'address' => 'Viale Gadio, 1',
                'created_at' => '2026-09-27T09:00:00Z',
                'updated_at' => '2026-09-27T09:00:00Z',
                'photo' => 'ticket_003.jpg',
            ],
            [
                'id' => 'DMO-004',
                'title' => 'Segnaletica danneggiata Corso Buenos Aires',
                'type' => 'segnaletica',
                'type_label' => 'Segnaletica Stradale',
                'status' => 'closed',
                'status_label' => 'Chiuso',
                'city' => 'Milano',
                'address' => 'Corso Buenos Aires, 45',
                'created_at' => '2026-09-20T11:30:00Z',
                'updated_at' => '2026-09-22T10:00:00Z',
                'photo' => null,
            ],
            [
                'id' => 'DMO-005',
                'title' => 'Gioco rotto area giochi Giardini Pubblici',
                'type' => 'verde',
                'type_label' => 'Verde Pubblico e Giardini',
                'status' => 'in_progress',
                'status_label' => 'In Corso',
                'city' => 'Milano',
                'address' => 'Via Palestro, 14',
                'created_at' => '2026-09-26T15:45:00Z',
                'updated_at' => '2026-09-27T11:30:00Z',
                'photo' => 'ticket_005.jpg',
            ],
        ];
    }

    public function render(): View
    {
        /** @var view-string $view */
        $view = 'mobile::native.screens.ticket-list';

        return view($view, [
            'title' => $this->title,
            'tickets' => $this->tickets,
            'filters' => $this->filters,
            'isLoading' => $this->isLoading,
            'isRefreshing' => $this->isRefreshing,
            'error' => $this->error,
            'hasMore' => $this->hasMore,
        ]);
    }
}
