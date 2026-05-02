import { h } from "vue";

export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                hanuman: ["Hanuman", "serif"],
                lora: ["Lora", "serif"],
                montserrat: ["Montserrat", "sans-serif"],
            },
        },
    },
    plugins: [],
};
