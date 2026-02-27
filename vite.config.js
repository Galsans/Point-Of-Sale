import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: "0.0.0.0",
        port: 5173,
        strictPort: true,
        hmr: {
            // host: "192.168.100.8",
            host: "127.0.0.1",
            protocol: "ws",
            port: 5173,
        },
        cors: {
            origin: "*", // Allow semua origin
            methods: ["GET", "POST", "PUT", "DELETE", "OPTIONS"],
            allowedHeaders: ["Content-Type", "Authorization"],
        },
        watch: {
            ignored: ["**/storage/framework/views/**"],
            usePolling: true, // Untuk Windows
        },
    },
});
