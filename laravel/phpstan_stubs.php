<?php

declare(strict_types=1);

/**
 * PHPStan stubs for optional dependencies.
 *
 * This file provides class stubs for packages that may not be installed
 * but are referenced in the codebase.
 */

namespace Saade\FilamentFullCalendar\Widgets {
    use Filament\Widgets\Widget;

    if (! class_exists(FullCalendarWidget::class)) {
        /**
         * @internal PHPStan stub for FullCalendarWidget
         */
        abstract class FullCalendarWidget extends Widget
        {
            /**
             * @param  array<string, mixed>  $fetchInfo
             * @return array<int, array<string, mixed>>
             */
            abstract public function fetchEvents(array $fetchInfo): array;

            /**
             * @return array<int, mixed>
             */
            abstract public function getFormSchema(): array;
        }
    }
}
