const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', 'Geist Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                surface: {
                    DEFAULT: '#F8FAFC',
                    card: '#FFFFFF',
                    subtle: '#F1F5F9',
                },
                navy: {
                    900: '#0F172A',
                    800: '#1E293B',
                    700: '#334155',
                    600: '#475569',
                    500: '#64748B',
                    400: '#94A3B8',
                    300: '#CBD5E1',
                    200: '#E2E8F0',
                    100: '#F1F5F9',
                    50: '#F8FAFC',
                },
                brand: {
                    50:  '#F0FDF4',
                    100: '#DCFCE7',
                    200: '#BBF7D0',
                    300: '#86EFAC',
                    400: '#4ADE80',
                    500: '#22C55E', // Core Bright Happy Green
                    600: '#16A34A', // Hover
                    700: '#15803D', // Active / Dark Text
                    800: '#166534',
                    900: '#14532D',
                },
                lime: {
                    400: '#A3E635',
                    500: '#84CC16',
                },
                ai: {
                    50: '#F5F3FF',
                    100: '#EDE9FE',
                    500: '#8B5CF6',
                    600: '#7C3AED',
                }
            },
            boxShadow: {
                'card': '0 1px 3px rgba(15, 23, 42, 0.05), 0 8px 24px rgba(15, 23, 42, 0.04)',
                'glow': '0 8px 24px rgba(34, 197, 94, 0.18)',
                'modal': '0 20px 25px -5px rgba(15, 23, 42, 0.1), 0 8px 10px -6px rgba(15, 23, 42, 0.1)',
            },
            borderRadius: {
                'card': '16px',
                'modal': '20px',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
