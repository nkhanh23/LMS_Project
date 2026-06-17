import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Space Grotesk"', 'sans-serif', 'Figtree', ...defaultTheme.fontFamily.sans],
                pixel: ['"Press Start 2P"', 'cursive'],
                mono: ['"VT323"', 'monospace'],
            },
            colors: {
                brand: '#A6E22E',
                'cyber-dark': '#1E1E2E',
                'cyber-surface': '#2A2A3C',
                'cyber-cyan': '#66D9EF',
                'text-primary': '#F8F8F2',
                'text-secondary': '#A0A0B0',
            },
        },
    },

    plugins: [forms],
};
