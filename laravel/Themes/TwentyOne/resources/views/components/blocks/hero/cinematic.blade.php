@props([
    'hero_title' => null,
    'hero_subtitle' => null,
    'cta_primary' => null,
    'cta_secondary' => null,
])

@php
    $hero = app(\Themes\TwentyOne\Actions\Hero\ResolveCinematicHeroDataAction::class)
        ->execute($hero_title, $hero_subtitle, $cta_primary, $cta_secondary);
    $particlesColor = 'rgba(125,211,252,0.48)';
@endphp

<section class="antigravity-field relative overflow-hidden bg-[radial-gradient(circle_at_top,rgba(14,165,233,0.16),transparent_22%),linear-gradient(180deg,#020617_0%,#0f172a_55%,#020617_100%)]" data-antigravity-field>
    <x-ui.particles count="84" :color="$particlesColor" size="3px" zIndex="0" variant="antigravity" />
    <div class="antigravity-spotlight" aria-hidden="true"></div>
    <div class="antigravity-orb antigravity-orb-1" aria-hidden="true"></div>
    <div class="antigravity-orb antigravity-orb-2" aria-hidden="true"></div>
    <div class="antigravity-orb antigravity-orb-3" aria-hidden="true"></div>

    <div class="container relative z-10 mx-auto px-4 pb-16 pt-24 md:pb-24 md:pt-28">
        <div class="grid items-center gap-10 lg:grid-cols-[minmax(0,1.05fr)_minmax(340px,0.95fr)] lg:gap-14">
            <div class="max-w-3xl">
                {{-- Etichetta sopra il titolo ("MERCATI MULTI-OPZIONE") tolta: il titolo basta da solo. --}}

                <h1 class="reveal-kinetic max-w-4xl text-5xl font-black leading-[0.94] tracking-tight text-balance text-white md:text-7xl xl:text-[5.5rem]" data-kinetic-block="fade-up" data-kinetic-delay="80">
                    {{ $hero['title'] }}
                </h1>

                <p class="reveal-kinetic mt-6 max-w-2xl text-pretty text-lg leading-8 text-slate-300 md:text-xl" data-kinetic-block="fade-up" data-kinetic-delay="140">
                    {{ $hero['subtitle'] }}
                </p>

                <div class="reveal-kinetic mt-8 flex flex-wrap gap-3" data-kinetic-block="fade-up" data-kinetic-delay="220">
                    @foreach($hero['highlights'] as $highlight)
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/6 px-4 py-2 text-sm text-slate-200 backdrop-blur">
                            <span class="h-2 w-2 rounded-full bg-cyan-300"></span>
                            <span>{{ $highlight }}</span>
                        </span>
                    @endforeach
                </div>

                <div class="reveal-kinetic mt-10 flex flex-col gap-4 sm:flex-row" data-kinetic-block="fade-up" data-kinetic-delay="300">
                    <a
                        href="{{ $hero['primaryCta']['url'] }}"
                        class="btn-kinetic inline-flex items-center justify-center gap-2 rounded-full border border-cyan-300/20 bg-gradient-to-r from-cyan-500 to-sky-500 px-7 py-4 text-base font-semibold text-slate-950 shadow-xl"
                    >
                        <span>{{ $hero['primaryCta']['text'] }}</span>
                        <x-filament::icon icon="heroicon-o-arrow-right" class="h-5 w-5" />
                    </a>

                    <a
                        href="{{ $hero['secondaryCta']['url'] }}"
                        class="btn-kinetic inline-flex items-center justify-center gap-2 rounded-full border border-white/12 bg-white/8 px-7 py-4 text-base font-semibold text-white backdrop-blur"
                    >
                        <x-filament::icon icon="heroicon-o-squares-2x2" class="h-5 w-5" aria-hidden="true" />
                        <span>{{ $hero['secondaryCta']['text'] }}</span>
                    </a>
                </div>

                {{-- Numeri in una riga (prima tre riquadri grandi, lo schema "numero gigante + etichetta"). --}}
                <dl class="reveal-kinetic mt-10 flex flex-wrap gap-x-8 gap-y-3 text-white" data-kinetic-block="fade-up" data-kinetic-delay="380">
                    @foreach($hero['stats'] as $stat)
                        <div>
                            <dt class="text-sm text-slate-300">{{ $stat['label'] }}</dt>
                            <dd class="text-2xl font-bold tabular-nums">{{ $stat['value'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <div class="reveal-kinetic hero-spotlight-shell rounded-[2rem] border border-white/10 bg-white/6 p-4 shadow-[0_30px_90px_rgba(2,6,23,0.42)] backdrop-blur md:p-5" data-kinetic-block="fade-left" data-kinetic-delay="180">
                <div class="mb-4 flex items-center justify-between gap-4">
                    <h2 class="text-2xl font-black tracking-tight text-balance text-white">{{ __('pub_theme::home.spotlight_title') }}</h2>
                </div>

                <div class="grid gap-4">
                    @forelse($hero['spotlightCards'] as $card)
                        <x-predict::spotlight-card :card="$card" />
                    @empty
                        <div class="rounded-[1.7rem] border border-dashed border-white/15 bg-slate-950/55 p-8 text-center text-slate-300">
                            {{-- Prima: istruzioni per sviluppatori ("Popola il database…") mostrate ai visitatori. --}}
                            <p class="text-lg font-semibold text-white">{{ __('pub_theme::home.spotlight_empty.title') }}</p>
                            <p class="mt-2 text-sm">{{ __('pub_theme::home.spotlight_empty.text') }}</p>
                            <a href="{{ url(app()->getLocale().'/predicts') }}" class="mt-4 inline-flex rounded-lg bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/20">{{ __('pub_theme::home.spotlight_empty.cta') }}</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
