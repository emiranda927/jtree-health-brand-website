# Joshua Tree Health — Website

Mixed-media editorial marketing site for Joshua Tree Health, an adolescent
(ages 10–17) PHP and IOP mental-health program in Apex, NC.

Built with **Astro 5 + Tailwind v4 + GSAP + Lenis**. Static output, deploys to **Vercel**.

---

## Quick start

```bash
npm install
npm run dev      # local dev at http://localhost:4321
npm run build    # static build → ./dist
npm run verify   # build + SEO/indexability/link audit
npm run preview  # preview the production build
```

Node 18.20.8+ / 20.3+ / 22+ required.

---

## Project structure

```
src/
  layouts/Base.astro        # <head>, fonts, SEO, JSON-LD, nav/footer/crisis shell, motion bootstrap
  components/
    Nav.astro               # sticky nav + mobile menu
    Footer.astro            # footer + sitewide closing CTA
    CrisisBar.astro         # persistent 988 / 741741 safety strip
    PageHero.astro          # reusable inner-page hero (cream or green variant)
    Figure.astro            # framed illustration (white-bg art melts in via multiply)
  pages/                    # one .astro per route
  styles/global.css         # design tokens + reusable component classes
  lib/motion.ts             # Lenis smooth-scroll + GSAP reveals (respects reduced-motion)
  assets/                   # optimized via astro:assets (imported images only)
public/brand-v2/            # current mixed-media visual identity and logos
```

## Visual source of truth

`origin/main` is the canonical visual identity. Update local work from main before
making visual changes; do not merge older local visual layers back into main.
GSAP, Lenis, Fontsource typography, the mixed-media hero system, and the current
navigation styling are intentional parts of the architecture.

## Design system (source of truth: `src/styles/global.css`)

- **Colors** — Deep Green `#183B2E`, Lime `#C8E869`, Lavender `#B8A7E6`, Cream `#F6F4EC`, Charcoal `#1D1D1F`, Sunflower `#FFD100` (all exposed as Tailwind/CSS vars, e.g. `var(--color-deep-green)`).
- **Type** — Fraunces (display/emotion), Sora (headings), Inter (body), JetBrains Mono (labels), Caveat (accent). Self-hosted via Fontsource.
- **Reusable classes** — `.section-head/.section-title/.section-sub`, `.btn` (+ `--lime/--ghost/--on-dark`), `.program-card`, `.stat`, `.step`, `.info-card`, `.checklist`, `.faq`, `.pullquote`, `.callout`, `.figure`, `.form`, `.grid-2/3/4`, surfaces (`.surface-green` etc.).

## Adding illustrations

Drop a white-background PNG in `src/assets/illustrations/`, import it, and pass to `<Figure>`:

```astro
import art from '../assets/illustrations/your-art.png';
<Figure src={art} alt="…" tone="cream" />   {/* tone: cream | lavender | lime */}
```

The `multiply` blend dissolves the white background into a brand-tinted panel — no boxy frame.

---

## Before launch — verify these claims

1. **Founder story** — add Gaby's approved personal paragraph in her own words.
2. **Team** — confirm every displayed name, role, license, credential, biography,
   and portrait with the clinician before publication.
3. **Program claims** — verify CARF status, PHP hours, age-track structure, family
   session cadence, Parent DBT Skills Group availability, and lead-therapist DBT credentials.
4. **Insurance** — confirm every payer contract before naming a plan as in-network.
5. **Privacy** — `src/pages/privacy.astro` is a website privacy policy, not the HIPAA
   Notice of Privacy Practices. Have legal review before launch.

The admissions form posts only after explicit submission. Do not restore abandoned-
form capture or send form fields, teen ages, insurance selections, or messages to analytics.

## Deploy (Vercel)

Push to a Git repo and import in Vercel — it auto-detects Astro. `vercel.json` adds
caching + security headers. Set the production domain to `www.jtreehealth.com`
(update `site:` in `astro.config.mjs` if different). Sitemap + robots.txt are generated.

## Roadmap (not yet done)

- **Pages CMS pilot** — Home, About, Team, and Learning Hub now use typed, Git-backed content files. See `docs/CMS_TEAM_GUIDE.md` for the team workflow.
- Expand the CMS to the remaining service and admissions pages after the pilot workflow is approved.
- More photography across inner pages (currently illustration + type led).

## Accessibility & performance

WCAG-AA contrast (brand-approved pairings), visible focus states, `prefers-reduced-motion`
honored, semantic landmarks, labelled forms. Images are responsive WebP via `astro:assets`;
static output keeps Lighthouse high.

<!-- vercel-git-connect smoke test 2026-07-17T17:53:37Z -->
