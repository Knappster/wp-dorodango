import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import { ViteImageOptimizer } from "vite-plugin-image-optimizer";
import eslint from "vite-plugin-eslint";
import browserslist from "browserslist";
import { browserslistToTargets } from "lightningcss";

export default defineConfig({
  css: {
    transformer: "lightningcss",
    lightningcss: {
      targets: browserslistToTargets(browserslist("defaults")),
      drafts: {
        customMedia: true,
      },
    },
  },
  base: "/wp-content/themes/theme/dist",
  server: {
    port: 5173,
    host: "0.0.0.0",
    origin: "http://localhost:5173",
  },
  build: {
    manifest: true,
    rollupOptions: {
      input: {
        main: "./assets/main.js",
        login: "./assets/login.js",
      },
    },
    outDir: "./public/wp-content/themes/theme/dist",
    copyPublicDir: false,
    assetsDir: "assets",
    cssCodeSplit: true,
    sourcemap: true,
    cssMinify: "lightningcss",
  },
  plugins: [ViteImageOptimizer({}), vue(), eslint()],
});
