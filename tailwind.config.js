/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./resources/**/*.blade.php", "./node_modules/flowbite/**/*.js"],
    theme: {
        extend: {
            colors: {
                "primary": 'rgb(12,109,173)',
                "primary-dark": 'rgb(12,109,173)',
                "primary-light": 'rgb(164,190,223)',
                "primary-superlight":"#1FA7DC",
                "secondary": "#6B3F23",
                "secondary-dark": "#6B3F23",
                "secondary-light": "#6B3F23",
                "dark": "#071A2A",
                "dark-medium": "#555555",
                "dark-light": "#F5F5F5",
            },
            borderColor: {
                orange: '#FFA500',
            },
            fontFamily: {
                sans: ['Arial', 'sans-serif'],
            },
        },
        borderColor: {
            "custom-border-color": "#FF5900",
            "focus-border-color": "#FF5900", // Define el color de borde enfocado personalizado aquí
        },   
        ringColor: {
            "custom-ring-color": "#FF5900", // Define el color de anillo personalizado aquí
        },

    },
    plugins: [require("flowbite/plugin")],
    variants: {
        extend: {
            borderColor: ['focus'], // Habilita las clases de borde enfocado
        },
    },

};
