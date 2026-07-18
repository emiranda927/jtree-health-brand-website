// @ts-check
import { defineConfig } from 'astro/config';
import sitemap from '@astrojs/sitemap';
import tailwindcss from '@tailwindcss/vite';

// https://astro.build/config
export default defineConfig({
  site: 'https://www.jtreehealth.com',
  integrations: [sitemap({
    filter: (page) => ![
      '/admissions/',
      '/design-lab/',
      '/learning-hub/blog/',
      '/privacy/',
      '/thank-you/',
    ].includes(new URL(page).pathname),
  })],
  prefetch: { prefetchAll: true, defaultStrategy: 'viewport' },
  vite: {
    plugins: [tailwindcss()],
  },
  image: {
    responsiveStyles: true,
  },
});
