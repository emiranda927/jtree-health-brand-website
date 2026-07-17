---
register: brand
palette:
  forest: "#1e3d34"        # ink + primary surface (dark)
  forest-2: "#15342b"
  forest-3: "#071b15"
  deep-sage: "#2c5f52"
  deep-sage-2: "#1d4f42"
  deep-sage-3: "#0c3228"
  mist-sage: "#8fbfb0"
  mist-sage-2: "#5d9a87"
  mist-sage-3: "#3b7d69"
  cyber-lime: "#b8e04a"
  cyber-lime-2: "#9dc62e"
  cyber-lime-3: "#7ea70d"
  digital-lavender: "#a89fd8"
  digital-lavender-2: "#8478c4"
  digital-lavender-3: "#685ab1"
  cream: "#fffaf3"         # paper default
  cream-2: "#f1e2ce"
  cream-3: "#dec6a6"
fonts:
  display: "Batica Sans"        # headlines, 700, sentence case
  label: "Misoka"               # buttons + kicker/card labels, uppercase
  editorial: "Fraunces Variable (SOFT 100 'SuperSoft')"  # ledes, body, quotes
  utility: "Inter Variable"     # forms, legal, small UI
source_of_truth: "Figma 'JTH - Graphic Assets (Copy)' (b8sExLzvFlD1XeE7cGhEfS) + official brand kit (BRAND_ASSETS.md)"
---

# JTree Health — Design Language

**Written 2026-07-17 from the designer's Figma comps (Header options 1–2, palette
board 199:734, spot-illustration board 267:2, branded-line-art frames 269:*) and the
official brand kit.** Supersedes the WP-era `DESIGN.md` in the old workspace. Read
`PRODUCT.md` (strategy, audiences, anti-references) before any UI work; read
`BRAND_ASSETS.md` for where every asset lives.

## 1. Design DNA

**A scrapbook made by a careful adult.** Real torn paper, real watercolor, real
photographs, hand-drawn doodles — composed with clinical calm on warm cream paper.
The tension IS the brand: playful analog materials (teen energy, hope) arranged with
editorial restraint (clinical credibility, safety). Premium comes from *material
honesty* and *composure*: few elements, real textures at high resolution, enormous
confident type, and one visual idea per viewport. It never comes from effects.
Nothing here is glossy, gradiented, glassy, or synthetic.

The quality test for every section: **would this layout survive being printed as a
poster?** Collage pieces cast soft paper shadows; everything else is flat.

## 2. Color

Strategy: **Committed** — Cream carries ~60%+ of every page as paper; Forest is the
ink; the three brights (Cyber Lime, Digital Lavender, Mist Sage) appear as deliberate
*fields and objects*, never as tints sprayed across components.

Roles:

| Token | Role |
|---|---|
| `cream #fffaf3` | Paper. Default page + section surface. |
| `cream-2/3` | Paper depth: alternate panels, framed figures, aged-paper accents. |
| `forest #1e3d34` | The ink. ALL text on light surfaces; dark surface for gravitas sections; doodle stroke color. |
| `deep-sage` | Support green: secondary buttons on dark, tree-growth art, quiet accents. |
| `mist-sage` | Calm field + clinical-adjacent accent (clouds, halos). Decorative; never text. |
| `cyber-lime` | Energy. CTAs on dark, tape, sun/star fills, ONE field moment per page max. |
| `digital-lavender` | Emotional softness. Quote fields, watercolor domes, illustration clothing. |
| `*-2 / *-3` steps | Shading within an object (illustration depth, hover states). Not new surface colors. |

Hard rules:
- Text is **Forest on light** or **Cream on dark**. The brights never carry body text.
  Forest-on-lime and Forest-on-lavender are allowed for labels/headlines ≥18px (both
  clear AA for large text; lime+forest clears AA at any size).
- Never `#000` or `#fff`; Cream and Forest-3 are the poles.
- Buttons: Forest fill + Cream Misoka label on light; Cyber Lime fill + Forest label
  on dark. Outline button = 1px Forest (or Cream on dark), transparent fill.
- **Migration note:** the live site still runs the older tokens (`#183b2e`,
  `#c8e869`, `#b8a7e6`, `#f6f4ec`…). Migrate by re-pointing the existing CSS
  variables to the values above in one commit (they are perceptual neighbors), then
  audit contrast per surface. Never mix old and new values on one page.

## 3. Typography

Four voices, each with one job. (Identity-preservation: these are the brand's
licensed faces from the kit — not open-catalog picks.)

| Voice | Face | Job | Rules |
|---|---|---|---|
| **Display** | Batica Sans Bold | H1/H2 headlines | Sentence case. `lh 0.95–1.02`, `ls -0.04em`. H1 `clamp(2.6rem → 4.55rem)`. Never all-caps, never below 1.5rem. |
| **Label** | Misoka (Heavy) | Buttons, kickers/eyebrows, card labels, nav | UPPERCASE, `0.78–0.95rem`, `ls 0.04–0.08em`. Replaces the mono eyebrow on all marketing surfaces. Short strings only (≤ 4 words). |
| **Editorial** | Fraunces SuperSoft (`SOFT 100`) | Ledes, subheads, body, pull-quotes, card one-liners | Ledes `1.25–1.8rem` medium; body `1.0625–1.125rem`, 65–75ch, `lh 1.6+`. Italic for pull-quotes only. |
| **Utility** | Inter | Form fields, legal, crisis bar detail, table data | Invisible workhorse. Never for headlines. |

Demotions: **Sora** → fallback stack only. **Caveat** → retired (Misoka is the
personality face now). **JetBrains Mono** → data strings only (phone, hours, address);
never as an eyebrow again.

Hierarchy pattern for a standard section head, top to bottom:
`MISOKA KICKER` (lime or sage on dark, deep-sage on light) → Batica headline →
Fraunces lede. Scale ratio between steps ≥ 1.25.

## 4. The section system (the rulebook)

Every section on the site is exactly one of four surface types. This closed set is
what makes pages cohere; no bespoke section styling.

| Surface | Background | Text | What lives here | Per-page budget |
|---|---|---|---|---|
| **Paper** | `cream` | Forest | The default. Content, cards, forms, FAQ, spot-illustration rows. Dot-grid backdrop allowed once per page (hero only). | Unlimited; ≥60% of page height |
| **Field** | one bright (`cyber-lime`, `digital-lavender`, or `mist-sage`) | Forest | ONE loud moment: a branded-line-art figure (photo cutout + doodles), a single statement, or the closing CTA band. | Max 1 (long pages: 2, never adjacent) |
| **Ink** | `forest` | Cream (+ lime/lavender accents) | Gravitas: manifesto, testimonial/pull-quote, footer. Cream/mist squiggle line-art allowed as accent. | Max 2 (footer counts as one) |
| **Collage Moment** | Paper base + layered collage kit | Forest | The signature. Torn-photo window, watercolor washes, tape, doodle marks, dot grid. | Exactly 1, hero-scale, top of page (or 0 on utility pages) |

Cadence grammar:
- Paper is the ground truth. **Never two accent surfaces (Field/Ink) adjacent** —
  every accent is preceded and followed by Paper.
- Section transitions are whitespace + surface change. **No dividers, no waves, no
  SVG edges** between sections; torn/watercolor edges belong INSIDE a Collage
  Moment or Field composition, not as section seams.
- One shared vertical rhythm: keep the existing `--section-y` clamp for every
  section; a Field/Ink section may not shrink its padding to "fit more in."
- One idea per viewport. If a section needs a second heading, it's two sections.
- Utility pages (privacy, thank-you, crisis) are Paper + footer Ink only. Crisis
  page: no collage, no Fields, no spot illustrations except `spot-nature-walk`
  permitted near resources. Calm is the feature.

## 5. Material library (what decorates, and when)

All from `public/brand/kit/` — see BRAND_ASSETS.md. **Never re-create any motif in
CSS/SVG; always place the real asset.** Poster density, not sticker-book density: a
Collage Moment uses 5–9 pieces; any other section uses 0–2.

| Material | Use | Never |
|---|---|---|
| Torn-photo windows (`collage-torn-joshua-tree-photo`, cut-collage) | The hero window; About-page moment | As content imagery inside cards |
| Watercolor washes/strokes (`watercolor-strokes/*`) | Collage bases; ONE bleeding page-edge accent on Paper | Tiled, stretched, or behind body text |
| Tapes (`tapes/*`) | "Pinning" a photo, quote card, or wash in a collage; max 2 per composition | On buttons/forms; as border decoration |
| Dot grids (`collage-dot-grid-*`) | Hero backdrop texture, once per page | Behind body copy; on Fields |
| Doodle marks (stars, sun, scribbles, squiggles) | Punctuation around a composition's focal point, 2–4 per composition; squiggle set on Ink/Field in a single stroke color | Scattered filler; more than one color-family of doodles per section |
| Halo graphics (`halos/*`) | Encircling ONE word in a headline or ONE small image (the "chosen" mark) | Repeated per-card; as list bullets |
| Tree-growth halo art (`tree-growth-halo/*`) | About/brand-story feature image; one placement site-wide | Decoration on program/insurance pages |
| Branded line art (photo cutout + doodle halo on Field) | The Field-section feature treatment | On Paper (it needs the flat color field) |
| Elements (`elements/*` 13 motifs) | Small punctuation marks (sun, star, cloud) in matching colorway | Building icon grids |

Photography rule: photos never appear as naked rectangles. Every photo is either
inside a torn-paper window, a halo/organic mask, or a photo-cutout with doodles.
Fraunces-era placeholder treatments (plain rounded rectangles) get retrofitted.

## 6. Spot illustration grammar (the wayfinding cast)

The 8 spot illustrations (`src/assets/illustrations/spot-*.svg`) are a **recurring
cast of teens** and the site's only illustration style. They are semantic labels,
not decoration: each names a concept, and appears **only where that concept is the
content**.

| Illustration | Concept | Belongs on |
|---|---|---|
| `spot-anxious` | Anxiety & panic | What We Treat condition card |
| `spot-depression` | Depression | What We Treat condition card |
| `spot-mindfulness` | Mindfulness / meditation, polyvagal work | Our Approach, IOP/PHP "what it includes" |
| `spot-art-therapy` | Expressive therapy (art) | Our Approach, program pages |
| `spot-music-therapy` | Expressive therapy (music) | Our Approach, program pages |
| `spot-nature-walk` | Experiential work, lakeside walks; hope-in-motion | Our Approach, About, crisis-resources (only permitted one there) |
| `spot-dbt-communication` | DBT/CBT skills, family sessions | Our Approach, For Parents |
| `spot-happy` | Outcomes, joy, "getting better" | Homepage services row, thank-you, closing CTA neighborhoods |

The canonical placement (from the Figma comp) is the **cast card**:
illustration (target 200–240px, min 140px) → `MISOKA LABEL` → Fraunces one-liner →
`Learn More →`. Cards sit on Paper, transparent background, no card chrome (no
border, no shadow); the row IS the section.

Rules:
- **2–4 per row, one row per page.** A page that wants more is a page that should
  link to another page.
- Equal scale within a row; never crop, rotate, recolor, mirror, or outline them.
- Paper or `cream-2` surfaces only (colored variants). The `-line` variants are the
  small-scale/secondary form: 48–96px inline markers or on `cream-2`; never on Ink
  (they'd vanish) and never mixed with colored variants in the same row.
- **Emotional adjacency:** `anxious`/`depression` appear only where conditions are
  being named, never adjacent to a CTA button, pricing, or the inquiry form, and
  always in a row that also contains a supportive/hopeful cast member. The set reads
  struggle → support → growth; keep that arc left-to-right.
- Never as: hero art, background texture, list bullets (colored), empty-state mascots,
  or paired with any other icon/illustration system. Marketing pages use spot
  illustrations or nothing; generic icon fonts/libraries are banned.

## 7. Components

- **Buttons**: pill (existing radius), Misoka uppercase label. Primary: Forest/Cream.
  On dark: Cyber Lime/Forest. Ghost: 1px outline. Arrow `→` on primary only. No
  shadows, no gradients; hover = translateY(-2px) + the *-2 shade.
- **Cast card**: see §6. The only "card" on marketing pages that has no chrome.
- **Content cards** (programs, FAQ, info): flat `cream` card on `cream-2` section or
  vice versa, 1px `cream-3` line, radius per existing tokens. No drop shadows;
  elevation is reserved for collage pieces.
- **Kicker**: Misoka, replaces `.eyebrow` mono style. Keep the small leading rule
  only on Paper; on Ink/Field the color contrast is the rule.
- **Nav**: current structure stays; link labels move to Misoka; logo swaps to the
  official kit lockup; utility bar unchanged.
- **Footer**: Ink surface; columns stay; headings become Misoka; a single squiggle
  or element mark max.
- **Forms**: untouched functionally (see AGENT_HANDOFF rules); restyle limited to
  fonts (labels → Inter 500, section head → Batica) and focus ring → `cyber-lime`.
- **Pull-quote**: Ink or Lavender Field, Fraunces italic, tape "pinning" the quote
  card allowed as its one decoration.

## 8. Motion

Keep the existing system exactly (Lenis + staggered reveals, reduced-motion
respected). Additions allowed only within a Collage Moment: pieces may settle with
±1–2° rotation on reveal (paper falling onto a desk), 0.4–0.6s, ease-out-quint,
once. No parallax on body sections, no scroll-jacking, no hover motion on
illustrations. The calm IS the brand; motion never competes with reading.

## 9. Do / Don't

**Do**: enormous Batica statements with one halo-circled word · one perfect collage
moment per page · flat color Fields with a single line-art figure · real materials
at retina resolution · Forest ink everywhere text lives · generous `--section-y` ·
cast cards as the services/conditions pattern.

**Don't**: CSS-faked collage/torn edges · wave dividers · icon libraries · identical
three-card grids with icons · shadows on non-collage elements · gradient anything ·
glass anything · brights as text or tints · more than one Field per page · spot
illustrations as furniture · mixing old and new palette values · Caveat/mono
eyebrows on new work · centered-stack hero templates.

## 10. Getting there (rollout order)

1. **Logos**: import the kit's Logo Package (RGB/SVG from Drive) → replace
   `public/brand/logo-*`, tree-marks, wordmark, favicons; audit Nav/Footer/OG.
2. **Token flip**: re-point palette variables to §2 values; add `*-2/*-3` steps;
   swap `.eyebrow` to Misoka kicker; set Fraunces SuperSoft as editorial body on
   marketing pages; demote Sora/Caveat/mono per §3. One commit, whole-site audit.
3. **Merge the hero PR** (collage moment, already Batica) and normalize every page
   onto the four-surface system — this is mostly deleting bespoke section styling.
4. **Homepage services row** → cast cards (comp exists in Figma Header options).
5. **What We Treat + Our Approach** → spot-illustration grammar (§6).
6. **Ink moments** (manifesto, quote, footer refit) + Field CTA band.
7. **Photography retrofit** (§5 photo rule) + About tree-growth feature.
8. `/impeccable audit` pass: contrast on every surface pair, responsive, reduced-motion.
