<?php
<<<<<<< HEAD

use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;

/** @var array $base_middleware */
$base_middleware = [];

name('home');
middleware($base_middleware);

new class extends Component
{
};
=======
use function Laravel\Folio\{middleware, name};
use Filament\Notifications\Notification;
use Filament\Notifications\Livewire\Notifications;
use Filament\Notifications\Actions\Action;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;
use Livewire\Volt\Component;
use Modules\Tenant\Services\TenantService;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;


name('home');
middleware(PageSlugMiddleware::class);

new class extends Component {};
>>>>>>> laraxot/dev

?>

<x-layouts.app>
    @volt('home')
<<<<<<< HEAD
    <div class="min-h-screen bg-gray-100">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
            {{-- Contenuto Principale (3/4 larghezza) - Occupa 3 colonne su 4 --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <x-page side="content" slug="home" />
                </div>
            </div>

            {{-- Sidebar Destra (1/4 larghezza) - Occupa 1 colonna su 4 --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <x-page side="sidebar" slug="home" />
                </div>
            </div>
        </div>
    </div>
=======
        <div>
            <x-page side="content" slug="home" />
        </div>
>>>>>>> laraxot/dev
    @endvolt
</x-layouts.app>
