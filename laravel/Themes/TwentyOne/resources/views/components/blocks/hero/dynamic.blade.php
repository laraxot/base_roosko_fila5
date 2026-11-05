@props([
    'title' => 'Predict. Win. Repeat.',
    'subtitle' => 'Join thousands predicting the future in real-time',
    'cta_text' => 'Start Predicting Now',
    'cta_link' => '/markets',
    'background_image' => '/images/hero-bg.jpg',
    'trending_markets' => []
])

@php
    /**
     * Scopo hero: fino a 3 market trend dal CMS/block props.
     * Nessun Service: il modulo Predict non esiste qui; i dati arrivano gia' shaped
     * (slug, title, trending_up, yes_percent) dal chiamante del blocco.
     */
    $trending_markets = collect($trending_markets)->take(3);
    $market_data = $trending_markets->map(static function (mixed $market): array {
        if (is_array($market)) {
            return [
                'slug' => (string) ($market['slug'] ?? ''),
                'title' => (string) ($market['title'] ?? ''),
                'trending_up' => (bool) ($market['trending_up'] ?? true),
                'yes_percent' => $market['yes_percent'] ?? $market['change'] ?? '',
            ];
        }

        return [
            'slug' => '',
            'title' => is_scalar($market) ? (string) $market : '',
            'trending_up' => true,
            'yes_percent' => '',
        ];
    });
@endphp

<section class="relative bg-gray-900 text-white py-20 md:py-32 overflow-hidden" style="background-image: url('{{ $background_image }}'); background-size: cover; background-position: center;">
    <div class="absolute inset-0 bg-black/60"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">{{ $title }}</h1>
            <p class="text-xl md:text-2xl mb-8">{{ $subtitle }}</p>
            
            <div class="flex justify-center mb-10">
                <a href="{{ $cta_link }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-8 rounded-full text-lg transition-all transform hover:scale-105">
                    {{ $cta_text }}
                </a>
            </div>
            
            @if($trending_markets->isNotEmpty())
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 max-w-2xl mx-auto">
                    <h3 class="text-lg font-semibold mb-4">🔥 Trending Right Now</h3>
                    <div class="flex flex-wrap justify-center gap-4">
                        @foreach($market_data as $market)
                            <a href="/markets/{{ $market['slug'] }}" class="bg-white/5 hover:bg-white/20 transition-colors px-4 py-2 rounded-full flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $market['trending_up'] ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                {{ $market['title'] }} 
                                <span class="text-xs opacity-80">{{ $market['yes_percent'] }}%</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Animated background elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-32 h-32 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-1/2 right-1/4 w-48 h-48 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-1/4 left-1/2 w-40 h-40 bg-pink-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    </div>
</section>

@push('styles')
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite ease-in-out; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
@endpush
