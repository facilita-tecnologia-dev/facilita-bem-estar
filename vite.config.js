import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    build: {
        outDir: "public/build",
    },
    server: {
        host: "127.0.0.1", // Força o Vite a rodar no IPv4
        port: 5173, // Certifique-se de que a porta de preview é 4173
    },
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
});
