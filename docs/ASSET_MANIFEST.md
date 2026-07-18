# Joshua Tree Health — Web Asset Manifest

## Source policy

Nicole's files under `/Users/eliseo/Downloads/Joshua Tree Health_*` remain untouched and serve as print/master sources. The normalized derivatives under `public/brand-v2/` are the website library.

The web library currently contains 84 files totaling approximately 2.92 MB. Pages should load only the assets they use; the full library is not intended to load on every route.

## Official palette provenance

The six web tokens were extracted directly from repeated fills in Nicole's RGB SVG exports:

- Forest `#1E3D34`
- Deep Sage `#2C5F52`
- Mist Sage `#8FBFB0`
- Cyber Lime `#B8E04A`
- Digital Lavender `#A89FD8`
- Light Cream `#FFFAF3`

## Library

| Directory | Files | Approx. weight | Format/use |
| --- | ---: | ---: | --- |
| `logos/` | 6 | 81 KB | Official primary, secondary, and submark SVGs in Forest and White |
| `spots/` | 8 | 186 KB | Official full-color spot illustrations as SVG |
| `graphics/` | 14 | 26 KB | Selected official graphic marks and colorways as SVG |
| `halos/` | 25 | 24 KB | Complete Forest halo set plus selected Lime/Lavender variants |
| `squiggles/` | 4 | 254 KB | Assorted and single-color official line-art sheets |
| `tapes/` | 8 | 234 KB | Transparent WebP derivatives, max width 1200 px |
| `washes/` | 11 | 819 KB | Transparent WebP derivatives, max width 1800 px |
| `collage/` | 1 | 394 KB | Cut Joshua tree collage, transparent WebP, max width 1800 px |
| `watercolor/` | 2 | 606 KB | Therapy scenes, transparent WebP, max width 1800 px |
| `fonts/` | 5 | 365 KB | Batica Sans, Misoka Heavy, and Fraunces SuperSoft TTF review files |

## Naming decisions

- Spaces and title casing were normalized to lowercase kebab-case.
- “Mindfulness - Medication.svg” was renamed `mindfulness-meditation.svg` because the Figma label is understood to be a typo.
- RGB assets are used for web. CMYK files remain print-only masters.

## Production rules

1. Never ship `.ai`, `.eps`, `.psd`, or CMYK files to the browser.
2. Do not use the 73–75 MB Tree Growth Halo SVG exports. They are too complex for production and should be rasterized or re-exported as simplified vectors if required.
3. Use official SVG files directly for simple marks, logos, and spot illustrations.
4. Use transparent WebP derivatives for watercolor, collage, and tape.
5. Do not use the combined Lavender or Forest squiggle sheets in production. Their intended text-crossing treatment requires individually exported, composition-ready SVGs; use the approved halo and graphic marks until those exist.
6. Font web licensing must be confirmed before Batica Sans, Misoka Heavy, or Fraunces SuperSoft is deployed publicly. Convert approved font files to WOFF2 and subset them before launch.
7. Preserve source aspect ratio; do not stretch artwork.

## Recommended next exports from the designer

- Individual squiggle marks rather than only combined sheets.
- A simplified, web-safe Tree Growth Halo vector under 150 KB.
- WOFF2 files or written confirmation that the Batica, Misoka, and Fraunces licenses permit web embedding.
- A corrected source filename for Mindfulness / Meditation.
