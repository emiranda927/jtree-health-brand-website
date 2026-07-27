import { defineCollection } from 'astro:content';
import { glob } from 'astro/loaders';
import { z } from 'astro/zod';

const seoSchema = z.object({
  title: z.string().min(20).max(70),
  description: z.string().min(50).max(170),
});

const textPairSchema = z.object({
  title: z.string().min(1),
  body: z.string().min(1),
});

const homePage = defineCollection({
  loader: glob({ pattern: 'home.json', base: './src/content/pages' }),
  schema: z.object({
    seo: seoSchema,
    hero: z.object({
      lineOne: z.string().min(1),
      lineTwo: z.string().min(1),
      lineThree: z.string().min(1),
      ledeOne: z.string().min(1),
      ledeTwo: z.string().min(1),
      primaryCta: z.string().min(1),
      secondaryCta: z.string().min(1),
    }),
    trustFacts: z.array(z.string().min(1)).length(4),
    manifesto: z.object({
      title: z.string().min(1),
      paragraphs: z.array(z.string().min(1)).length(3),
      primaryCta: z.string().min(1),
      secondaryCta: z.string().min(1),
    }),
    pathways: z.object({
      eyebrow: z.string(), // may be empty — the treatment section can render without an eyebrow
      title: z.string().min(1),
      cardCta: z.string().min(1),
      allCta: z.string().min(1),
      conditions: z.array(textPairSchema).length(6),
    }),
    programs: z.object({
      eyebrow: z.string().min(1),
      title: z.string().min(1),
      intro: z.string().min(1),
      php: z.object({
        tag: z.string().min(1),
        title: z.string().min(1),
        schedule: z.string().min(1),
        body: z.string().min(1),
        features: z.array(z.string().min(1)).min(1).max(8),
        cta: z.string().min(1),
      }),
      iop: z.object({
        tag: z.string().min(1),
        title: z.string().min(1),
        schedule: z.string().min(1),
        body: z.string().min(1),
        features: z.array(z.string().min(1)).min(1).max(8),
        cta: z.string().min(1),
      }),
      insuranceNote: z.string().min(1),
    }),
    measure: z.object({
      eyebrow: z.string().min(1),
      title: z.string().min(1),
      intro: z.string().min(1),
      points: z.array(textPairSchema).length(3),
      cta: z.string().min(1),
    }),
    process: z.object({
      eyebrow: z.string().min(1),
      title: z.string().min(1),
      steps: z.array(textPairSchema).length(3),
    }),
    location: z.object({
      eyebrow: z.string().min(1),
      title: z.string().min(1),
      intro: z.string().min(1),
      directionsCta: z.string().min(1),
    }),
    closing: z.object({
      eyebrow: z.string().min(1),
      title: z.string().min(1),
      body: z.string().min(1),
      primaryCta: z.string().min(1),
    }),
  }),
});

const aboutPage = defineCollection({
  loader: glob({ pattern: 'about.json', base: './src/content/pages' }),
  schema: z.object({
    seo: seoSchema,
    hero: z.object({
      title: z.string().min(1),
      emphasis: z.string().min(1),
      sub: z.string().min(1),
      primaryCta: z.string().min(1),
      secondaryCta: z.string().min(1),
      facts: z.array(textPairSchema).length(3),
    }),
    mission: z.object({
      eyebrow: z.string().min(1),
      title: z.string().min(1),
      lead: z.string().min(1),
      paragraphs: z.array(z.string().min(1)).length(2),
    }),
    founder: z.object({
      eyebrow: z.string().min(1),
      name: z.string().min(1),
      role: z.string().min(1),
      description: z.string().min(1),
      teamCta: z.string().min(1),
    }),
    history: z.object({
      eyebrow: z.string().min(1),
      title: z.string().min(1),
      paragraphs: z.array(z.string().min(1)).length(3),
    }),
    values: z.object({
      eyebrow: z.string().min(1),
      title: z.string().min(1),
      items: z.array(z.object({
        name: z.string().min(1),
        body: z.string().min(1),
      })).min(1).max(8),
    }),
    programFacts: z.object({
      eyebrow: z.string().min(1),
      title: z.string().min(1),
      intro: z.string().min(1),
      items: z.array(z.object({
        label: z.string().min(1),
        detail: z.string().min(1),
      })).length(4),
    }),
    closing: z.object({
      eyebrow: z.string().min(1),
      title: z.string().min(1),
      body: z.string().min(1),
      primaryCta: z.string().min(1),
    }),
  }),
});

const teamPage = defineCollection({
  loader: glob({ pattern: 'team.json', base: './src/content/pages' }),
  schema: z.object({
    seo: seoSchema,
    hero: z.object({
      title: z.string().min(1),
      sub: z.string().min(1),
    }),
    leadership: z.object({
      eyebrow: z.string().min(1),
      title: z.string().min(1),
      members: z.array(z.object({
        name: z.string().min(1),
        credentials: z.string().min(1),
        title: z.string().min(1),
      })).min(1),
    }),
    clinical: z.object({
      eyebrow: z.string().min(1),
      title: z.string().min(1),
      intro: z.string().min(1),
      members: z.array(z.object({
        name: z.string().min(1),
        credentials: z.string().min(1),
        title: z.string().min(1),
      })).min(1),
    }),
    closing: z.object({
      title: z.string().min(1),
      body: z.string().min(1),
      primaryCta: z.string().min(1),
    }),
  }),
});

const learningHubPage = defineCollection({
  loader: glob({ pattern: 'learning-hub.json', base: './src/content/pages' }),
  schema: z.object({
    seo: seoSchema,
    hero: z.object({
      eyebrow: z.string().min(1),
      title: z.string().min(1),
      sub: z.string().min(1),
      primaryCta: z.string().min(1),
    }),
    resources: z.object({
      eyebrow: z.string().min(1),
      title: z.string().min(1),
      intro: z.string().min(1),
      items: z.array(z.object({
        title: z.string().min(1),
        body: z.string().min(1),
        href: z.string().startsWith('/'),
      })).min(4).max(6),
      itemCta: z.string().min(1),
    }),
    closing: z.object({
      title: z.string().min(1),
      body: z.string().min(1),
      primaryCta: z.string().min(1),
    }),
  }),
});

/*
 * News — time-bound announcements and events.
 *
 * One file per item. `kind` decides the JSON-LD: 'event' emits schema.org Event
 * (one per session, since sessions are booked individually), 'announcement'
 * emits Article. Dates are plain ISO date strings; times are local to the clinic
 * (America/New_York) and are stamped onto the ISO 8601 values at render time.
 *
 * Anything date-gated (the homepage callout, upcoming vs past) is resolved at
 * BUILD time — the site is static, so the nightly Vercel cron in vercel.json is
 * what actually makes these roll over. Without it, dates freeze at last deploy.
 */
const isoDate = z.string().regex(/^\d{4}-\d{2}-\d{2}$/, 'Use YYYY-MM-DD');
const isoTime = z.string().regex(/^\d{2}:\d{2}$/, 'Use HH:MM (24h)');

const news = defineCollection({
  loader: glob({ pattern: '*.json', base: './src/content/news' }),
  schema: z.object({
    kind: z.enum(['event', 'announcement']),
    title: z.string().min(1),
    seo: seoSchema,
    // Shown on the index card and used as the meta/schema description.
    summary: z.string().min(1),
    // Drives ordering on the index and the upcoming/past split.
    publishDate: isoDate,
    // Last day the item is treated as current. For an event series this is the
    // final session; after it passes the item moves to "Past" and the homepage
    // callout stops rendering.
    endDate: isoDate,
    // Homepage callout. Omit `homeUntil` to fall back to `endDate`.
    featureOnHome: z.boolean().default(false),
    homeUntil: isoDate.optional(),
    callout: z.object({
      eyebrow: z.string().min(1),
      title: z.string().min(1),
      body: z.string().min(1),
      cta: z.string().min(1),
    }).optional(),
    hero: z.object({
      eyebrow: z.string().min(1),
      sub: z.string().min(1),
    }),
    intro: z.array(z.string().min(1)).min(1),
    // Event-only fields.
    event: z.object({
      startTime: isoTime,
      endTime: isoTime,
      priceLabel: z.string().min(1),
      price: z.number().nonnegative(),
      priceCurrency: z.string().length(3).default('USD'),
      registrationUrl: z.string().url(),
      registrationLabel: z.string().min(1),
      locationName: z.string().min(1),
      streetAddress: z.string().min(1),
      addressLocality: z.string().min(1),
      addressRegion: z.string().min(1),
      postalCode: z.string().min(1),
      details: z.array(textPairSchema).min(1),
      sessions: z.array(z.object({
        date: isoDate,
        topic: z.string().min(1),
        // Optional deep link to the clinical page that covers this topic.
        href: z.string().startsWith('/').optional(),
      })).min(1),
    }).optional(),
    // Optional printable asset (the referral-partner flier).
    download: z.object({
      label: z.string().min(1),
      href: z.string().startsWith('/'),
    }).optional(),
    closing: z.object({
      title: z.string().min(1),
      body: z.string().min(1),
      primaryCta: z.string().min(1),
    }),
  }).refine((d) => d.kind !== 'event' || d.event, {
    message: 'kind: "event" requires an `event` block',
    path: ['event'],
  }),
});

export const collections = { homePage, aboutPage, teamPage, learningHubPage, news };
