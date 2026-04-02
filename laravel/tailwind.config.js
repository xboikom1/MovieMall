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
        screens: {
            tablet: '768px',
            desktop: '1024px',
        },
        extend: {
            colors: {
                bg: '#0f172a',
                text: '#f9fafb',
                accent: '#f43f5e',
                border: '#374151',
                placeholder: '#9ca3af',
                dark: '#111827',
                button: '#1f2937',
                rating: '#fbbf24',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
