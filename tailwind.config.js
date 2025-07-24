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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'insuractio-primary': '#1e3c72',
                'insuractio-secondary': '#2a5298',
                'insuractio-success': '#059669',
                'insuractio-warning': '#f59e0b',
                'insuractio-danger': '#dc2626',
                'insuractio-accent': '#9c27b0',
                'motivation-yellow': '#fbbf24',
                'energy-pink': '#ec4899',
                'calm-teal': '#14b8a6',
            }
        },
    },

    plugins: [forms],
};
