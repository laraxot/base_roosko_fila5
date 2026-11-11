{{--
/**
 * Header section v1 - Data-driven Nav bar.
 * Follows WCAG 2.1 AA standards.
 * Inspired by Meetup theme implementation.
 */
--}}
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-200" role="banner">
    @php
        $pos = collect($blocks)->groupBy('data.position');
        // Su telefono la barra non ci stava (pagina più larga dello schermo): i blocchi che la barra
        // in basso già offre (categorie, classifica) o secondari (tema, notifiche) si nascondono.
        $responsive = [
            'dropdown' => 'hidden md:contents',
            'leaderboard' => 'hidden md:contents',
            'dark_mode' => 'hidden sm:contents',
            'notifications' => 'hidden sm:contents',
        ];
    @endphp
    <nav class="px-4 py-3 mx-auto sm:px-6 sm:py-4 container-xl" role="navigation" aria-label="{{ __('pub_theme::headernav.main') }}">
        <ul class="flex items-center justify-between lg:grid lg:grid-cols-3 gap-x-4">
            {{-- Left Position --}}
            <li class="flex items-center space-x-4 md:space-x-8">
                @foreach($pos->get('left', []) as $block)
                    @php
                        try {
                            $wrap = $responsive[$block->type ?? ''] ?? null;
                            echo ($wrap ? '<span class="'.$wrap.'">' : '').view($block->view, array_merge($block->data, ['blocks' => $blocks]))->render().($wrap ? '</span>' : '');
                        } catch (\Throwable $e) {
                            // Skip blocks with missing dependencies
                        }
                    @endphp
                @endforeach
            </li>

            {{-- Center Position (Desktop Only) --}}
            <li class="hidden grow md:block">
                @foreach($pos->get('center', []) as $block)
                    @php
                        try {
                            $wrap = $responsive[$block->type ?? ''] ?? null;
                            echo ($wrap ? '<span class="'.$wrap.'">' : '').view($block->view, array_merge($block->data, ['blocks' => $blocks]))->render().($wrap ? '</span>' : '');
                        } catch (\Throwable $e) {
                            // Skip blocks with missing dependencies
                        }
                    @endphp
                @endforeach
            </li>

            {{-- Right Position --}}
            <li class="flex items-center justify-end gap-x-2 sm:gap-x-4">
                @foreach($pos->get('right', []) as $block)
                    @php
                        try {
                            $wrap = $responsive[$block->type ?? ''] ?? null;
                            echo ($wrap ? '<span class="'.$wrap.'">' : '').view($block->view, array_merge($block->data, ['blocks' => $blocks]))->render().($wrap ? '</span>' : '');
                        } catch (\Throwable $e) {
                            // Skip blocks with missing dependencies
                        }
                    @endphp
                @endforeach
            </li>
        </ul>
    </nav>
</header>
