// Importa la configuración por defecto de fuentes de Tailwind
import defaultTheme from 'tailwindcss/defaultTheme';

// Importa plugin para mejorar estilos de formularios (inputs, selects, etc.)
import forms from '@tailwindcss/forms';

/**
 * Configuración principal de Tailwind CSS
 * @type {import('tailwindcss').Config}
 */
export default {

    // Archivos donde Tailwind buscará clases CSS
    // Si no están aquí, Tailwind no generará estilos para ellos
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php', // vistas de Laravel
        './node_modules/flowbite/**/*.js',   // componentes Flowbite
    ],

    // Configuración del diseño (tema)
    theme: {
        extend: {

            //  Personalización de fuentes
            fontFamily: {
                // Usa la fuente Figtree como principal
                // y si no existe, usa las fuentes por defecto de Tailwind
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

        },
    },

    //  Plugins adicionales (extienden Tailwind)
    plugins: [

        // Mejora el diseño de formularios (inputs más bonitos)
        forms,

        // Componentes preconstruidos de Flowbite
        // (botones, modales, cards, navbar, etc.)
        require('flowbite/plugin')
    ],
};