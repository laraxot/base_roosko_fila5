<?php
use function Laravel\Folio\{name};

name('404');
?>
<x-layouts.app
    :title="__('pub_theme::error_page.not_found.page_title.title')"
    :meta-description="__('pub_theme::error_page.not_found.meta_description.description')"
>
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-emerald-950 to-slate-900 flex items-center justify-center px-4">
        <div class="max-w-2xl mx-auto text-center">
            <!-- Animated 404 -->
            <div class="relative mb-8">
                <div class="text-8xl md:text-9xl font-black text-emerald-400 mb-4 animate-pulse">
                    4<span class="inline-block animate-bounce delay-100">0</span><span class="inline-block animate-bounce delay-200">4</span>
                </div>
                <div class="absolute inset-0 text-8xl md:text-9xl font-black text-emerald-400/20 blur-sm">
                    404
                </div>
            </div>

            <!-- Error Message -->
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                {{ __('pub_theme::error_page.not_found.heading.title') }}
            </h1>

            <p class="text-xl text-slate-300 mb-8 max-w-lg mx-auto">
                {{ __('pub_theme::error_page.not_found.intro.description') }}
            </p>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ url('/') }}"
                   class="cyber-button magnetic-hover px-8 py-4 rounded-2xl text-white font-bold text-lg group">
                    <span class="relative z-10">{{ __('pub_theme::error_page.actions.home.label') }}</span>
                </a>

                <button onclick="history.back()"
                        class="px-8 py-4 rounded-2xl border-2 border-white/30 text-white font-bold text-lg hover:border-white/60 hover:bg-white/10 backdrop-blur-xl transition-all duration-300">
                    {{ __('pub_theme::error_page.actions.back.label') }}
                </button>
            </div>

            <!-- Additional Help -->
            <div class="mt-12 text-slate-400">
                <p class="mb-4">{{ __('pub_theme::error_page.suggestions.intro.label') }}</p>
                <div class="flex flex-wrap justify-center gap-4 text-sm">
                    <a href="{{ url('/categories') }}" class="text-emerald-400 hover:text-emerald-300 underline">{{ __('pub_theme::error_page.suggestions.categories.label') }}</a>
                    <a href="{{ url('/search') }}" class="text-emerald-400 hover:text-emerald-300 underline">{{ __('pub_theme::error_page.suggestions.search.label') }}</a>
                    <a href="{{ url('/learn') }}" class="text-emerald-400 hover:text-emerald-300 underline">{{ __('pub_theme::error_page.suggestions.how_it_works.label') }}</a>
                    <a href="{{ url('/contact') }}" class="text-emerald-400 hover:text-emerald-300 underline">{{ __('pub_theme::error_page.suggestions.contact.label') }}</a>
                </div>
            </div>

            <!-- Fun Animation -->
            <div class="mt-16 relative">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-32 h-32 border-4 border-emerald-400/20 rounded-full animate-spin">
                        <div class="w-32 h-32 border-4 border-transparent border-t-emerald-400 rounded-full animate-spin absolute inset-0" style="animation-duration: 1.5s;"></div>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-6xl mb-4">🔮</div>
                    <p class="text-emerald-400 font-medium">{{ __('pub_theme::error_page.not_found.loading.message') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
