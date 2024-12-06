import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import path from 'path'

export default defineConfig({
	build: {
		manifest: true,
		outDir: 'public/build/',
		cssCodeSplit: true,
		minify: 'terser',
		rollupOptions: {
			output: {
				assetFileNames: (css) => {
					if (css.name.split('.').pop() == 'css') {
						return 'css/' + `[name]` + '.min.css';
					} else {
						return 'icons/' + css.name;
					}
				},
				entryFileNames: 'js/' + `[name]` + '.min.js',
				chunkFileNames: 'js/' + `[name]` + '.min.js',
			},
		},
	},
	plugins: [
		laravel({
			input: ['resources/sass/app.scss',
				'resources/js/app.js',
				'resources/js/eilinger.js',
				'resources/sass/eilinger.scss',
				'resources/sass/dashboard.scss',
			],
			refresh: true,
		}),
	],

	resolve: {
		alias: {
			'~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
		}
	},
})
