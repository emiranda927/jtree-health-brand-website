# Joshua Tree Health — Website

Premium, photography-led marketing site for Joshua Tree Health, an adolescent
(ages 10–17) trauma-informed PHP & IOP mental-health program in Apex, NC.

Built with **Astro 5 + Tailwind v4 + GSAP + Lenis**. Static output, deploys to **Vercel**.

---

## Quick start

```bash
npm install
npm run dev      # local dev at http://localhost:4321
npm run build    # static build → ./dist
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
  pages/                    # one .astro per route (14 pages)
  styles/global.css         # design tokens + reusable component classes
  lib/motion.ts             # Lenis smooth-scroll + GSAP reveals (respects reduced-motion)
  assets/                   # optimized via astro:assets (imported images only)
public/brand/               # logos, favicons (served as-is)
```

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

## ⚠️ Before launch — wire these up

1. **Form submissions** — the Admissions and Contact forms currently validate and
   redirect to `/thank-you` as a demo. Wire them to the existing `jtree-form-api`:
   see the `INTEGRATION` comment in `src/pages/admissions.astro` and `src/pages/contact.astro`
   (POST the `FormData` to your endpoint, then redirect).
2. **Founder's story** — `src/pages/about.astro` contains an on-brand *draft* of the
   origin story. Replace with Gaby's actual words.
3. **Team** — add real clinician bios/photos to the About "team" section.
4. **Privacy** — `src/pages/privacy.astro` is a website privacy policy, not the HIPAA
   Notice of Privacy Practices. Have legal review before launch.
5. **Verify hours / details** — confirm PHP (9–3) and IOP (3–6) hours, phone, address.
6. **OG image** — set a real social-share image (currently falls back to the logo).

## Deploy (Vercel)

Push to a Git repo and import in Vercel — it auto-detects Astro. `vercel.json` adds
caching + security headers. Set the production domain to `www.jtreehealth.com`
(update `site:` in `astro.config.mjs` if different). Sitemap + robots.txt are generated.

## Roadmap (not yet done)

- **TinaCMS** — planned, so non-developers can edit copy/stats/images. Not yet wired.
- More photography across inner pages (currently illustration + type led).

## Accessibility & performance

WCAG-AA contrast (brand-approved pairings), visible focus states, `prefers-reduced-motion`
honored, semantic landmarks, labelled forms. Images are responsive WebP via `astro:assets`;
static output keeps Lighthouse high.

<!-- vercel-git-connect smoke test 2026-07-17T17:53:37Z -->
