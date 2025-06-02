import { createFilter } from "vite";
import fs from "node:fs/promises";

const PLUGIN_NAME = "vite-plugin-twig-css";
const VIRTUAL_MODULE_ID = `virtual:${PLUGIN_NAME}`;
const RESOLVED_VIRTUAL_MODULE_ID = `\0${VIRTUAL_MODULE_ID}`;

export default function twigCss(options = {}) {
	const filter = createFilter(options.include || /\.twig$/, options.exclude || []);
	const styleBlocks = new Map();

	function extractCss(content) {
		let extractedCss = "";

		// Regex for standard <style> tags
		const htmlStyleRegex = /<style[^>]*?>(.*?)<\/style>/gs;
		let htmlMatch;
		while ((htmlMatch = htmlStyleRegex.exec(content)) !== null) {
			extractedCss += `${htmlMatch[1].trim()}\n`;
		}

		return extractedCss.trim();
	}

	return {
		name: PLUGIN_NAME,
		async load(id) {
			if (id.startsWith(RESOLVED_VIRTUAL_MODULE_ID)) {
				const cssContent = styleBlocks.get(id);
				if (cssContent) {
					return {
						code: cssContent,
						map: null,
					};
				}
			}

			if (!filter(id)) {
				return null;
			}

			try {
				const code = await fs.readFile(id, "utf-8");
				const extractedCss = extractCss(code);

				if (extractedCss) {
					const cssModuleId = `${RESOLVED_VIRTUAL_MODULE_ID}:${id}.css`;
					styleBlocks.set(cssModuleId, extractedCss);

					return {
						code: `import ${JSON.stringify(cssModuleId)};`,
						map: null,
					};
				}

				return null;
			} catch (error) {
				console.error(`Failed to process file ${id}: ${error.message}`);
			}
		},
		resolveId(id) {
			if (id.startsWith(RESOLVED_VIRTUAL_MODULE_ID)) {
				return id;
			}

			return null;
		},
	};
}
