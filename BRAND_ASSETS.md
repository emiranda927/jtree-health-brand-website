# JTH Brand Assets — Web Manifest

**Imported 2026-07-17** from the designer's official brand kit (Nic Cayabyab).
**Masters live in Google Drive** — folder "Joshua Tree Health"
(https://drive.google.com/drive/folders/1bNYpSQD4oZXbtrvRf_7zfLFmB8ME8Xik), also
mirrored in Eliseo's `~/Downloads/Joshua Tree Health_*` folders. The Drive kit is
~1.4 GB (`.ai`/`.psd` working files, CMYK print sets, every format × colorway).

**Repo policy: only curated, web-ready derivatives are committed.** No `.ai`, `.psd`,
CMYK, EPS, PDF, or print-resolution files in git — pull from Drive when a new
derivative is needed (rasters get converted to capped WebP via sharp, ~150 dpi,
long edge ≤ 2600px, alpha preserved).

## What's in the repo

| Path | Contents |
|---|---|
| `public/brand/kit/elements/` | 89 graphic-element SVGs (13 motifs × colorways), true vector |
| `public/brand/kit/halos/` | 168 halo-graphic SVGs (16 motifs × colorways), true vector |
| `public/brand/kit/squiggles/` | 8 squiggle line-art SVGs (one per colorway) |
| `public/brand/kit/tree-growth-halo/` | 8 WebP renders of the painterly Tree Growth Halo artwork (the kit's "SVGs" were 65 MB raster-in-SVG shells; converted from the designer's HIGH RES PNGs) |
| `public/brand/kit/tapes/` | 8 washi-tape textures, WebP w/ alpha |
| `public/brand/kit/watercolor-strokes/` | 11 brand watercolor strokes, WebP w/ alpha |
| `public/brand/kit/cut-collage-image.webp` | The flattened hero cut-collage artwork |
| `src/assets/illustrations/spot-*.svg` | 8 official spot illustrations + `-line` (uncolored) variants: anxious, depression, happy, mindfulness, art-therapy, music-therapy, dbt-communication, nature-walk |
| `src/assets/illustrations/*-watercolor.webp` | Official watercolor content illustrations: group-therapy, one-on-one-therapy (supersede the June `group-therapy.png` / `session-1to1.png` still referenced by pages — swap during redesign) |
| `public/fonts/batica-sans/` | Batica Sans webfonts (woff2 + OTF; 400/700, normal + italic) |
| `public/fonts/misoka/` | Misoka accent webfont (woff2 + OTF) |
| `public/brand/` (root, from earlier July imports) | `collage-*` hero-layer singles, `insurers/` payer logos — these predate this kit; several overlap it (tape, watercolor washes, dot grids). Prefer `kit/` versions going forward; the singles stay until the collage hero is re-pointed. |

Fonts are declared in `src/styles/global.css` (`@font-face`) and addressable as
`var(--font-brand)` (Batica Sans → Sora fallback) and `var(--font-accent)` (Misoka).
Fraunces ships via Fontsource variable packages already — the kit's static Fraunces
cuts were intentionally not imported. Batica Sans and Misoka are licensed via the
designer-provided brand kit.

## Official palette (extracted from the kit's SVG fills)

| Colorway name | Hex | Nearest current site token (differs!) |
|---|---|---|
| Forest | `#1e3d34` | `--color-deep-green` `#183b2e` |
| Deep Sage | `#2c5f52` | `--color-pine` `#21503d` |
| Mist Sage | `#8fbfb0` | (none) |
| Digital Lavender | `#a89fd8` | `--color-lavender` `#b8a7e6` |
| Cyber Lime | `#b8e04a` | `--color-lime` `#c8e869` |
| Light Cream | `#fdf9f3` (from spot illos) | `--color-cream` `#f6f4ec` |

Supporting tints seen in the spot illustrations: `#bfde63`, `#a59ed3`, `#98bdb0`, `#395e53`, `#263c34`.

⚠️ The live site still runs on the OLD tokens. Do not bulk-swap colors ad hoc —
the palette migration happens as part of the design-system pass (PRODUCT.md /
DESIGN.md re-grounding via Impeccable), so contrast/accessibility is re-checked
per surface, not find-and-replaced.

## Not imported (and why)

- `.ai` / `.psd` working files (up to 872 MB — exceed GitHub limits; not build inputs)
- CMYK sets, EPS, PDF, JPEG, and PNG duplicates of vector assets (print/managed in Drive)
- Static Fraunces TTFs (variable Fraunces already self-hosted via Fontsource)
- `Logo Package` — the repo already carries the full logo set in `public/brand/`
  (`logo-*.svg/png`, `tree-mark-*`, `wordmark`); reconcile against the kit's logo
  package during the design-system pass if the marks were updated.
