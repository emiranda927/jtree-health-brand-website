/// <reference types="astro/client" />

// Astro/Vite only declare lowercase image extensions; some assets ship with
// uppercase extensions (e.g. Gaby-founder-headshot.JPG).
declare module '*.JPG' {
  const metadata: ImageMetadata;
  export default metadata;
}
