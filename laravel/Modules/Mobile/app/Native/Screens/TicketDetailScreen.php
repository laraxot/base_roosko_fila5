<?php

declare(strict_types=1);

namespace Modules\Mobile\Native\Screens;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

/**
 * @phpstan-type TicketData array{
 *     id: string,
 *     title: string,
 *     type: string,
 *     type_label: string,
 *     type_icon: string,
 *     status: string,
 *     status_label: string,
 *     status_color: string,
 *     city: string,
 *     address: string,
 *     description: string,
 *     location: array{lat: float, lng: float}|null,
 *     created_at: string,
 *     updated_at: string,
 *     photos: list<string>,
 *     reporter_name?: string,
 *     reporter_phone?: string,
 *     assigned_department?: string,
 *     assigned_operator?: string,
 *     sla_deadline?: string,
 *     priority?: string,
 *     resolution?: string
 * }
 * @phpstan-type TimelineEntry array{action: string, label: string, user: string, timestamp: string}
 */
class TicketDetailScreen extends NativeComponent
{
    public string $ticketId = '';

    /** @var TicketData */
    public array $ticket;

    /** @var list<TimelineEntry> */
    public array $timeline = [];

    public bool $isLoading = true;

    public string $error = '';

    public bool $canUpdate = false;

    public bool $isSubscribed = false;

    /**
     * NativeComponent::mountComponent() risolve i parametri di montaggio per
     * NOME dal route (routeParameterFor), mai come array: un parametro
     * `array $params` riceverebbe sempre il valore di default.
     */
    public function mount(?string $ticketId = null): void
    {
        $this->ticketId = $ticketId ?? '';
        $this->loadTicket();
    }

    public function loadTicket(): void
    {
        $this->isLoading = true;
        $this->error = '';

        // Simulazione - in produzione chiama API o database locale
        $this->ticket = $this->getDemoTicket($this->ticketId);
        $this->timeline = $this->getDemoTimeline($this->ticketId);
        $this->canUpdate = auth()->check() && $this->userCanUpdate();
        $this->isSubscribed = $this->checkSubscription();
        $this->isLoading = false;
    }

    public function subscribe(): void
    {
        if (! auth()->check()) {
            $this->navigate('login', ['redirect' => 'ticket-detail', 'ticketId' => $this->ticketId]);

            return;
        }

        // Chiama action per sottoscrizione
        $this->isSubscribed = true;
        $this->showToast('Ti notificheremo sugli aggiornamenti');
    }

    public function unsubscribe(): void
    {
        $this->isSubscribed = false;
        $this->showToast('Notifiche disattivate');
    }

    public function updateStatus(string $newStatus): void
    {
        if (! $this->canUpdate) {
            $this->showToast('Non hai i permessi per aggiornare lo stato', 'error');

            return;
        }

        // Chiama action per aggiornamento stato
        $this->ticket['status'] = $newStatus;
        $this->ticket['status_label'] = $this->getStatusLabel($newStatus);
        $this->timeline[] = [
            'action' => 'status_changed',
            'label' => 'Stato aggiornato a ' . $this->getStatusLabel($newStatus),
            'user' => auth()->user()->name ?? 'Operatore',
            'timestamp' => now()->toIso8601String(),
        ];
        $this->showToast('Stato aggiornato');
    }

    public function addInternalNote(string $note): void
    {
        if (! $this->canUpdate) {
            return;
        }

        $this->timeline[] = [
            'action' => 'internal_note',
            'label' => 'Nota interna: ' . $note,
            'user' => auth()->user()->name ?? 'Operatore',
            'timestamp' => now()->toIso8601String(),
        ];
        $this->showToast('Nota aggiunta');
    }

    public function shareTicket(): void
    {
        $url = url('/tickets/' . $this->ticketId);
        $this->share([
            'title' => $this->ticket['title'],
            'text' => 'Guarda questa segnalazione: ' . $this->ticket['title'],
            'url' => $url,
        ]);
    }

    public function navigateToMap(): void
    {
        $location = $this->ticket['location'];

        if ($location === null) {
            return;
        }

        $this->navigate('map', ['focus' => $location]);
    }

    /** @return TicketData */
    private function getDemoTicket(string $id): array
    {
        $tickets = [
            'DMO-001' => [
                'id' => 'DMO-001',
                'title' => 'Buca in Via Roma',
                'type' => 'strade',
                'type_label' => 'Strade e Marciapiedi',
                'type_icon' => 'road',
                'status' => 'in_progress',
                'status_label' => 'In Corso',
                'status_color' => '#3b82f6',
                'city' => 'Milano',
                'address' => 'Via Roma, 15',
                'description' => 'Grande buca nell\'asfalto che crea pericolo per ciclisti e automobilisti. Si trova all\'incrocio con Via Torino.',
                'location' => ['lat' => 45.4642, 'lng' => 9.1900],
                'created_at' => '2026-09-25T10:30:00Z',
                'updated_at' => '2026-09-26T14:20:00Z',
                'reporter_name' => 'Mario Rossi',
                'reporter_phone' => '+39 3XX XXXXXXX',
                'photos' => ['ticket_001_1.jpg', 'ticket_001_2.jpg'],
                'assigned_department' => 'Manutenzione Strade',
                'assigned_operator' => 'Ufficio Tecnico Milano Centro',
                'sla_deadline' => '2026-09-28T10:30:00Z',
                'priority' => 'high',
            ],
            'DMO-002' => [
                'id' => 'DMO-002',
                'title' => 'Lampione spento Piazza Duomo',
                'type' => 'illuminazione',
                'type_label' => 'Illuminazione Pubblica',
                'type_icon' => 'lightbulb',
                'status' => 'resolved',
                'status_label' => 'Risolto',
                'status_color' => '#10b981',
                'city' => 'Milano',
                'address' => 'Piazza Duomo',
                'description' => 'Il lampione all\'angolo con Galleria Vittorio Emanuele non funziona da 3 giorni. Buio totale la sera.',
                'location' => ['lat' => 45.4642, 'lng' => 9.1914],
                'created_at' => '2026-09-24T08:15:00Z',
                'updated_at' => '2026-09-25T16:45:00Z',
                'reporter_name' => 'Laura Bianchi',
                'reporter_phone' => '+39 3XX XXXXXXX',
                'photos' => ['ticket_002.jpg'],
                'assigned_department' => 'Illuminazione Pubblica',
                'assigned_operator' => 'Ditta Esterna LuceSpA',
                'sla_deadline' => '2026-09-26T08:15:00Z',
                'priority' => 'medium',
                'resolution' => 'Sostituito corpo illuminante e cavo alimentazione. Testato funzionamento.',
            ],
            'DMO-003' => [
                'id' => 'DMO-003',
                'title' => 'Rifiuti abbandonati Parco Sempione',
                'type' => 'rifiuti',
                'type_label' => 'Rifiuti e Pulizia',
                'type_icon' => 'trash',
                'status' => 'open',
                'status_label' => 'Aperto',
                'status_color' => '#f59e0b',
                'city' => 'Milano',
                'address' => 'Viale Gadio, 1',
                'description' => 'Ingombranti abbandonati vicino all\'ingresso lato Arena: divano, materasso, scatoloni. Presenti da ieri sera.',
                'location' => ['lat' => 45.4730, 'lng' => 9.1710],
                'created_at' => '2026-09-27T09:00:00Z',
                'updated_at' => '2026-09-27T09:00:00Z',
                'reporter_name' => 'Giovanni Verdi',
                'reporter_phone' => '+39 3XX XXXXXXX',
                'photos' => ['ticket_003.jpg', 'ticket_003_2.jpg'],
                'assigned_department' => 'AMSA - Raccolta Rifiuti',
                'assigned_operator' => 'In attesa di assegnazione',
                'sla_deadline' => '2026-09-29T09:00:00Z',
                'priority' => 'medium',
            ],
        ];

        return $tickets[$id] ?? [
            'id' => $id,
            'title' => 'Segnalazione non trovata',
            'type' => 'other',
            'type_label' => 'Altro',
            'type_icon' => 'help',
            'status' => 'open',
            'status_label' => 'Aperto',
            'status_color' => '#f59e0b',
            'city' => '',
            'address' => '',
            'description' => '',
            'location' => null,
            'created_at' => now()->toIso8601String(),
            'updated_at' => now()->toIso8601String(),
            'photos' => [],
        ];
    }

    /** @return list<TimelineEntry> */
    private function getDemoTimeline(string $id): array
    {
        $timelines = [
            'DMO-001' => [
                ['action' => 'created', 'label' => 'Segnalazione creata da Mario Rossi', 'user' => 'Mario Rossi', 'timestamp' => '2026-09-25T10:30:00Z'],
                ['action' => 'assigned', 'label' => 'Assegnata a Ufficio Tecnico Milano Centro', 'user' => 'Sistema', 'timestamp' => '2026-09-25T10:35:00Z'],
                ['action' => 'status_changed', 'label' => 'Stato aggiornato a In Corso', 'user' => 'Operatore Tecnico', 'timestamp' => '2026-09-26T14:20:00Z'],
                ['action' => 'note', 'label' => 'Sopralluogo effettuato, ordinato materiale per riparazione', 'user' => 'Operatore Tecnico', 'timestamp' => '2026-09-26T14:25:00Z'],
            ],
            'DMO-002' => [
                ['action' => 'created', 'label' => 'Segnalazione creata da Laura Bianchi', 'user' => 'Laura Bianchi', 'timestamp' => '2026-09-24T08:15:00Z'],
                ['action' => 'assigned', 'label' => 'Assegnata a Ditta Esterna LuceSpA', 'user' => 'Sistema', 'timestamp' => '2026-09-24T08:20:00Z'],
                ['action' => 'status_changed', 'label' => 'Stato aggiornato a In Corso', 'user' => 'Tecnico LuceSpA', 'timestamp' => '2026-09-25T09:00:00Z'],
                ['action' => 'resolved', 'label' => 'Risolto: Sostituito corpo illuminante', 'user' => 'Tecnico LuceSpA', 'timestamp' => '2026-09-25T16:45:00Z'],
            ],
            'DMO-003' => [
                ['action' => 'created', 'label' => 'Segnalazione creata da Giovanni Verdi', 'user' => 'Giovanni Verdi', 'timestamp' => '2026-09-27T09:00:00Z'],
                ['action' => 'assigned', 'label' => 'In coda per AMSA', 'user' => 'Sistema', 'timestamp' => '2026-09-27T09:05:00Z'],
            ],
        ];

        return $timelines[$id] ?? [
            ['action' => 'created', 'label' => 'Segnalazione creata', 'user' => 'Sistema', 'timestamp' => now()->toIso8601String()],
        ];
    }

    private function userCanUpdate(): bool
    {
        // Logica: operatori di quartiere e admin possono aggiornare
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->hasRole(['operator', 'department_head', 'admin', 'super_admin']);
    }

    private function checkSubscription(): bool
    {
        // Verifica se l'utente è sottoscritto a questa segnalazione
        return false; // Simulazione
    }

    private function getStatusLabel(string $status): string
    {
        return match ($status) {
            'open' => 'Aperto',
            'in_progress' => 'In Corso',
            'resolved' => 'Risolto',
            'closed' => 'Chiuso',
            default => $status,
        };
    }

    private function showToast(string $message, string $type = 'success'): void
    {
        // In produzione: usa bridge function per toast nativo
    }

    /** @param array{title: string, text: string, url: string} $data */
    private function share(array $data): void
    {
        // In produzione: usa bridge function per share nativo
    }

    public function render(): View
    {
        /** @phpstan-var view-string $view */
        $view = 'mobile::native.screens.ticket-detail';

        return view($view, [
            'ticket' => $this->ticket,
            'timeline' => $this->timeline,
            'isLoading' => $this->isLoading,
            'error' => $this->error,
            'canUpdate' => $this->canUpdate,
            'isSubscribed' => $this->isSubscribed,
        ]);
    }
}