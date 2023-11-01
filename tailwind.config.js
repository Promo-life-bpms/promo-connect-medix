/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./resources/**/*.blade.php", "./node_modules/flowbite/**/*.js"],
    theme: {
        extend: {
            colors: {
                "primary": "#14449A",
                "primary-dark": "#043966",
                "primary-light": "#1FA7DC",
                "primary-superlight":"#1FA7DC",
                "secondary": "#14449A",
                "secondary-dark": "#043966",
                "secondary-light": "#1FA7DC",
                "dark": "#071A2A",
                "dark-medium": "#555555",
                "dark-light": "#F5F5F5",
            },
        },
        borderColor: {
            "custom-border-color": "#FF5733",
            "focus-border-color": "#E10098", // Define el color de borde enfocado personalizado aquí
        },   
        ringColor: {
            "custom-ring-color": "#E10098", // Define el color de anillo personalizado aquí
        },

    },
    plugins: [require("flowbite/plugin")],
    variants: {
        extend: {
            borderColor: ['focus'], // Habilita las clases de borde enfocado
        },
    },

};
