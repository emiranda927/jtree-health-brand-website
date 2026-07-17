# Product

## Register

brand

## Users

**Primary: parents/guardians of teens ages 10–17, in or near Apex, NC.** They arrive scared, exhausted, and skeptical. Many have already been let down by other care — pediatricians who shrugged, schools that escalated, intake calls that felt cold. They're researching late at night on a phone, deciding whether to trust this clinic with their child. The job they're trying to get done is: *"Tell me, in plain language, whether this place can actually help my kid — and if so, give me a low-friction way to start."*

**Secondary: teens ages 10–17.** They land here because a parent shared the link, or because they searched themselves. They've been let down by adults before. They are hyper-attuned to anything that feels condescending, performative, or marketed-at. Pages they read (`/for-teens/`, `/what-we-treat/`) must speak with a lower-pressure register — never push them to call.

**Tertiary: clinical referrers** (pediatricians, school counselors, therapists). They scan for credentials (CARF, in-network insurances, modalities) within seconds and bounce or refer.

## Product Purpose

JTree Health is an adolescent PHP/IOP mental health clinic in Apex, NC offering Partial Hospitalization, Intensive Outpatient, and Medication Management for teens 10–17. The site is **a brand site + lead-capture funnel**, not an app. The single conversion goal is: a parent fills out the inquiry form on `/admissions/`. Everything else exists to earn that submission.

Success looks like:

- A parent who'd never heard of JTree finishes a homepage scroll and feels relief — *"this place sounds like real people, not a hospital."*
- A teen lands on `/for-teens/` and reads more than two screens before bouncing.
- A pediatrician opens `/programs/` on a weekday, finds the modalities + insurances + hours, and refers without needing to call.
- The form on `/admissions/` posts to `api.jtreehealth.com/api/inquiry`, GA4 fires `inquiry_submitted` once on `/thank-you/`, and the lead reaches Resend + Ritten/Sheets.

## Brand Personality

**Three words: warm, grounded, hopeful.**

The voice is plain, direct, human. It speaks to a parent who is overwhelmed and skeptical, and to a teen who has been let down by adults. It is trauma-informed: it assumes the reader is in pain, and it does not moralize, perform, or sell. It is clinically credible without being clinical-sounding.

**Voice anchors from the brand kit:**

- "Grow in a shape that is entirely your own."
- "Here to help you grow through it."
- "It's okay to not be okay."
- "You don't have to do this alone."
- "Suburban. Real. Supportive."

**Emotional goals (in order):**

1. **Calm** — the page should lower a parent's heart rate. Generous breathing room, quiet motion, no shouting.
2. **Trust** — clinical credibility (CARF, in-network insurances, modalities, hours) is present and easy to find, but never the loudest thing on the page.
3. **Recognition** — a teen sees themselves in the language and imagery. A parent recognizes the *kind* of care being offered without needing a glossary.
4. **Hope** — quiet optimism. The brand believes growth is possible and personal; the design carries that without slogans.

**Emotional bans:**

- Never urgent ("Get help NOW!"), never sales-y, never corporate ("world-class," "cutting-edge," "premier," "transformative").
- Never clinical jargon as a flex. Modalities (DBT, CBT, etc.) appear when relevant, with a one-line plain-English gloss.
- Never gravitas-as-aesthetic — no funereal dark-stock-photography, no "we understand your pain" stock empathy.

## Anti-references

The hardest anti-reference is **anything that has already been built for this category**. Mental health clinic sites have a strong template gravity — JTree must escape it.

**Stay-away list (specific competitors / categories):**

- **Brightpathbh.com** — direct local competitor in Apex/NC. Avoid their palette (sage + burnt orange + bright blue + lavender + mustard yellow) and their layout idioms. JTree's palette and feel must read as *clearly different* at a glance.
- **Salmahealth.com / Seromentalhealth.com** — muted-and-calming-for-adults. Old-fashioned imagery, "wave" iconography from beach-locations, stock-photo trust signals. JTree is for teens, not adults; we read younger, more honest, less corporate.
- **Embarkbh.com / Charliehealth.com / Hazel.co** — competent but generic adolescent-mental-health template. Soft pastels, hero photo of a smiling teen, three-card grid of services, founder-quote band, footer-CTA. JTree must not collapse into this layout.
- **Generic SaaS / hospital sites** — three icon-and-text cards, hero gradient, glass cards, big rounded buttons in primary color. Past Claude Code iterations on this project drifted into this; that is the failure mode to actively avoid.

**Aspirational lane (do feel like these):**

- **Allcove.org** — clean, youthful, immediately legible, gen-Z/alpha-appropriate without being childish. Calming-but-credible. The strongest single reference for the *feel*.
- **Eleanorhealth.com** — color theory that balances clinical credibility (dark) with play (warm accent). Swirl/organic decorative motif.
- **Handspringhealth.com** — playful illustrative warmth + functional polish (note: their palette is too pediatric for us; lift the *feel*, not the colors).
- **Radicalhealing.co** — modern editorial register for gen-Z/alpha; we'd dial up clinical credibility from there.
- **Maximatherapy.com** — animation cadence and considered motion (note: too pediatric in tone; reference for *motion*, not content).

**Anti-pattern bans (concrete rules that flow from the above):**

- No CSS/SVG re-creation of collage motifs (torn paper, scribbles, brush strokes, twinkles). Past attempts produced jagged, synthetic results. Use the provided PNGs as positioned decoration only, with restraint (poster density, not feed density).
- No "wave divider between every section" reflex. Section transitions earn their motif only when the change deserves a moment — most don't. Default is generous whitespace, not a divider.
- No three-identical-card grids for Programs/Conditions/Stats. Vary cell shape, scale, and treatment.
- No hero gradient. The hero is the collage moment defined in `DESIGN.md` (cream paper, Batica Sans statement, torn-photo collage window, form-driving CTA). *(Updated 2026-07-17 with the official brand kit; the earlier "single editorial Fraunces statement" hero is superseded.)*
- No glass cards, no `background-clip: text` gradient text, no side-stripe borders.

## Design Principles

1. **Calm before clinical.** Every page must lower a parent's heart rate before it asks for credibility. Whitespace, line length, and motion timing are the first tools, not color and badges.
2. **Plain over polished.** A sentence a 13-year-old understands beats a sentence that signals "we are a serious clinic." If both are possible, pick the plain one and let typography do the dignifying.
3. **Show the brand kit, don't reinterpret it.** The collage, twinkles, mascot, and torn-paper accents are the brand's personality on paper. On the web, use them *as-is* (PNG/SVG) in restrained doses. Do not paraphrase them in CSS.
4. **One emotional moment per page.** Each designed page earns exactly one big editorial Fraunces statement — the moment a visitor pauses. Everywhere else, type is functional and quiet. Spreading emotional moments across every section flattens all of them.
5. **The form is the page.** The site's only conversion is the inquiry form. Designed pages route there. The form itself, on `/admissions/`, must feel calmer and lower-pressure than any other surface — not the loudest thing, the easiest thing.

## Accessibility & Inclusion

**Standard: WCAG 2.1 AA**, plus extra care for two specific user states: a parent in distress, and a teen who's been let down by adults.

**Inherited from `FRONT_END_REQUIREMENTS.md` §9.2:**

- Color contrast — Deep Green on Cream passes; Lime on Cream **does not** for body text (accent + large display only). Lavender on Cream is borderline; verify each instance.
- Keyboard reachability with a visible `:focus-visible` ring on every interactive element.
- `prefers-reduced-motion` — collapse hover transforms, scroll-reveal staggers, and any parallax/scroll-driven motion to instant or near-instant.
- Form fields properly labeled; errors via `aria-describedby` + `role="alert"` on submission failure.
- Skip-to-content link on every page; one `<h1>` per page; no level skips.
- Crisis bar phone numbers ≥44×44px tap targets, `tel:` and `sms:` links.

**Extra care for stressed parents:**

- Reduced cognitive load on the homepage: short paragraphs, line-length capped at 65–75ch, no more than one decision per screen.
- The crisis bar is informational, not anxiety-inducing — phrasing and color must not escalate panic.
- Persistent phone number in the nav (or one tap from nav on mobile) so a parent can stop reading and call at any moment.

**Extra care for teens** *(the standalone `/for-teens/` page was removed in the June 2026 copy guide; this register applies to teen-adjacent content on What We Treat and the program pages)*:

- Lower visual register: less collage, fewer accents, more whitespace.
- Lower-pressure copy: "Talk to someone" / "Here when you're ready" — never "Start the Conversation" or "Call now."
- No tracking pixels, no session replay, no chat widgets — under-18 audience demands strict privacy hygiene.

**Inclusion bans (privacy-as-accessibility):**

- No Meta/TikTok/LinkedIn/Reddit pixel.
- No Hotjar/FullStory/LogRocket/Microsoft Clarity / session replay.
- No PII or PHI in URLs, browser storage, or third-party error reporters.
- Cookie banner is **opt-out by owner directive (2026-07-12)**: analytics run by default, Decline turns them off and persists; ad storage always denied. Do not revert to opt-in.
