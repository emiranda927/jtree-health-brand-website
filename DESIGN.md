# Joshua Tree Health — Digital Visual System

This document translates Nicole's final brand files into rules for the website. The official RGB exports in the delivered design package are the source of truth. Existing site colors or earlier mockups do not override these values.

## Brand position

Joshua Tree Health should feel bold, human, and distinctive while retaining the calm authority of a mental-health provider. The visual system combines real-world credibility with expressive illustration and handmade materiality.

The central idea is **real people and desert resilience, interpreted through an expressive hand**.

## Official digital palette

| Token | Hex | Primary role |
| --- | --- | --- |
| Forest | `#1E3D34` | Primary text, logo, dark surfaces, clinical anchor |
| Deep Sage | `#2C5F52` | Secondary dark surface, supporting UI |
| Mist Sage | `#8FBFB0` | Quiet panels, illustration support, soft contrast |
| Cyber Lime | `#B8E04A` | Energy, teen-facing emphasis, selected CTAs |
| Digital Lavender | `#A89FD8` | Emotional softness, editorial accents |
| Light Cream | `#FFFAF3` | Default page ground |

Pure white may be used inside practical UI such as form fields, but Light Cream is the default brand background. Avoid orange, terracotta, and red as brand accents.

The spot illustrations contain slightly shifted production colors. Preserve those colors as part of the artwork; do not recolor them to force exact token matches.

## Typography

- **Batica Sans** — primary display face. Use for large, welcoming page and section headlines. Its rounded irregularity adds warmth without becoming childish.
- **Misoka Heavy** — expressive utility face. Use for eyebrows, navigation, buttons, short subheads, card titles, and compact moments of emphasis. Keep it concise and predominantly uppercase; long passages become noisy and weaken credibility.
- **Fraunces SuperSoft** — body and editorial face. Use for paragraphs, supporting copy, pull quotes, and story-led moments. Its serif structure gives the expressive display system a calmer, more credible counterweight.

The hierarchy is consistent across every page: Batica introduces the idea, Misoka labels and activates it, and Fraunces explains it. Misoka should punctuate the system rather than dominate the page.

## Three visual registers

### 1. Photography and mixed-media collage

This is the primary emotional language. Use real people, real environments, Joshua tree photography, watercolor strokes, cut-paper compositions, tape, halo marks, and restrained line art.

- Best for: homepage, About, culture, parent-facing stories, recruitment.
- Build collage responsively from separate layers when the composition must change across breakpoints.
- Do not distribute every decorative ingredient equally. A composition should have one focal image and one primary paint gesture, supported by a small cluster of line art, tape, or halo marks.
- Keep faces and calls to action clear of decorative overlays.

### 2. Spot illustrations

Use the eight delivered spot illustrations to explain emotions, treatments, therapeutic ideas, and major pathways quickly.

- Best for: What We Treat, program explanations, therapy modalities, Learning Hub, and major wayfinding moments.
- Present them at meaningful scale. Do not reduce them to tiny icons.
- Give each illustration open space; avoid placing them inside generic bordered cards by default.
- The delivered file labeled “Mindfulness / Medication” is treated as **Mindfulness / Meditation** in the website library.

### 3. Decorative graphic language

Halos, squiggles, graphic elements, watercolor strokes, and tapes provide continuity.

- Use as punctuation and connective tissue, not filler. The system should feel visibly expressive, so line art should recur near headlines, imagery, links, and section transitions while preserving clear reading space.
- Repeat a small, recognizable subset across the site rather than using every available mark.
- Forest is the default line color. Lime and lavender are emphasis colors.
- Decorative graphics must be `aria-hidden` and must not carry meaning by themselves.
- Do not use the combined `squiggles/lavender.svg` or `squiggles/forest.svg` sheets in production compositions. Their intended text-crossing behavior needs dedicated, individually exported assets before it can be implemented cleanly.

## Underlines

- Never underline or highlight large display headings. At that scale, the stroke collides with wrapped letterforms and reads like a strikethrough.
- Reserve underlines for links, compact labels, and occasional short subheads where the line can sit clearly below one line of text.
- Parent, referral, clinical, and safety registers should rely on hierarchy and spacing rather than decorative underlining.

## Density by audience

| Audience/context | Treatment |
| --- | --- |
| Teen-facing | More expressive composition, larger spot illustrations, selective lime, visible hand-drawn marks |
| Parent-facing | Photography-led, moderate collage, generous space, calmer color balance |
| Clinical/referral | Strong typography, restrained decoration, more Forest and Cream, concise proof and process |
| Crisis/safety | Minimal decoration, direct hierarchy, maximum legibility |

This is one identity at different volumes, not separate brands.

## Layout principles

- Favor asymmetric editorial compositions over repeated equal-width card grids.
- Use generous negative space to keep expressive assets from feeling juvenile or chaotic.
- Let illustrations interact with section boundaries where safe, but preserve readable text columns.
- Use Light Cream as the default ground, with Forest and Deep Sage as deliberate anchor sections.
- On mobile, simplify the collage rather than shrinking the full desktop composition.

## Motion

- Motion should clarify layering: slow image drift, restrained reveal, or a squiggle drawing into place.
- Animate only `transform`, `opacity`, or true SVG stroke properties.
- Do not animate raster masks or large watercolor textures continuously.
- Respect `prefers-reduced-motion`; all content must remain complete without animation.

## Accessibility and credibility

- Body copy must meet WCAG AA contrast.
- Lime is not a body-text color on Cream.
- Do not place type over visually active artwork without a stable contrast surface.
- Photography should feel candid and specific, not like generic wellness stock imagery.
- Clinical claims, outcomes, accreditation, and safety language should use the restrained register.

## Definition of success

When the wordmark is covered, the combination of palette, illustration hand, collage material, and recurring graphic marks should still feel recognizably Joshua Tree Health. At the same time, a parent or clinician should be able to scan programs, insurance, safety, and admission information without visual interference.
