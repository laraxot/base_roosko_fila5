import defaultTheme from 'tailwindcss/defaultTheme';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './Themes/*/resources/views/**/*.blade.php',
        './Themes/*/resources/js/**/*.js',
        './Themes/*/resources/vue/**/*.vue',
        './Modules/*/resources/views/**/*.blade.php',
        './Modules/*/resources/js/**/*.js',
        './Modules/*/resources/vue/**/*.vue',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            // Bootstrap Italia Color Palette
            colors: {
                // Colori primari Italia
                'italia-blue': {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#0066CC', // Primary blue
                    600: '#0059B3',
                    700: '#004C99',
                    800: '#003F80',
                    900: '#003366',
                    950: '#001F3D',
                },
                // Verde per successo
                'italia-green': {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#00B373', // Success green
                    600: '#009959',
                    700: '#00804D',
                    800: '#006640',
                    900: '#004D33',
                    950: '#003326',
                },
                // Rosso per errori
                'italia-red': {
                    50: '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#D9364F', // Error red
                    600: '#CC1F38',
                    700: '#B31829',
                    800: '#99141F',
                    900: '#800F17',
                    950: '#660C12',
                },
                // Giallo per warning
                'italia-yellow': {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#FFB400', // Warning yellow
                    600: '#E6A200',
                    700: '#CC9100',
                    800: '#B37F00',
                    900: '#996D00',
                    950: '#805C00',
                },
                // Grigi neutri
                'italia-gray': {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                    950: '#020617',
                },
                // Alias per compatibilità
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#0066CC',
                    600: '#0059B3',
                    700: '#004C99',
                    800: '#003F80',
                    900: '#003366',
                    950: '#001F3D',
                },
                success: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#00B373',
                    600: '#009959',
                    700: '#00804D',
                    800: '#006640',
                    900: '#004D33',
                    950: '#003326',
                },
                danger: {
                    50: '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#D9364F',
                    600: '#CC1F38',
                    700: '#B31829',
                    800: '#99141F',
                    900: '#800F17',
                    950: '#660C12',
                },
                warning: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#FFB400',
                    600: '#E6A200',
                    700: '#CC9100',
                    800: '#B37F00',
                    900: '#996D00',
                    950: '#805C00',
                },
            },
            // Bootstrap Italia Typography
            fontFamily: {
                sans: ['Titillium Web', 'Inter', 'system-ui', ...defaultTheme.fontFamily.sans],
                serif: ['Lora', 'Georgia', ...defaultTheme.fontFamily.serif],
                mono: ['Roboto Mono', 'Menlo', ...defaultTheme.fontFamily.mono],
                display: ['Titillium Web', 'Inter', 'system-ui', ...defaultTheme.fontFamily.sans],
            },
            // Font weights specifici per Bootstrap Italia
            fontWeight: {
                light: '300',
                normal: '400',
                semibold: '600',
                bold: '700',
            },
            // Spaziature conformi al design system
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
            },
            // Border radius conformi
            borderRadius: {
                'xs': '0.125rem',
                'sm': '0.25rem',
                'md': '0.375rem',
                'lg': '0.5rem',
                'xl': '0.75rem',
                '2xl': '1rem',
                '3xl': '1.5rem',
            },
            // Breakpoints responsive conformi
            screens: {
                'xs': '475px',
                'sm': '576px',
                'md': '768px',
                'lg': '992px',
                'xl': '1200px',
                '2xl': '1400px',
            },
            // Animazioni e transizioni
            animation: {
                'fade-in': 'fadeIn 0.3s ease-in-out',
                'slide-down': 'slideDown 0.3s ease-in-out',
                'slide-up': 'slideUp 0.3s ease-in-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideDown: {
                    '0%': { transform: 'translateY(-10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
            },
            // Box shadows conformi al design system
            boxShadow: {
                'sm': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                'DEFAULT': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                'md': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                'lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                'xl': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                '2xl': '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
                'italia': '0 2px 4px 0 rgba(0, 102, 204, 0.1)',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
    ],
};
