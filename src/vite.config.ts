import path from "path";
import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import environment from "vite-plugin-environment";

export default defineConfig({
    plugins: [react(), environment(["VITE_GITHUB_URL", "VITE_NEW_RELIC_URL"])],
    build: {
        emptyOutDir: false,
        sourcemap: true,
        outDir: path.resolve(__dirname, "public"),
        assetsDir: "",
        rollupOptions: {
            input: [
                path.resolve(__dirname, "resources/ts/app.tsx"),
                path.resolve(__dirname, "resources/css/app.css"),
            ],
            output: {
                entryFileNames: "js/[name].js",
                assetFileNames: (assetInfo) => {
                    const extType = assetInfo?.name?.split('.').slice(-1)[0] || '';
                    return `${extType}/[name][extname]`;
                },
            },
        },
    },
    optimizeDeps: {
        include: ["react", "react-dom"],
    },
});
