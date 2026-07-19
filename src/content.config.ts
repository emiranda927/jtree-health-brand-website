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
      })).length(4),
      itemCta: z.string().min(1),
    }),
    closing: z.object({
      title: z.string().min(1),
      body: z.string().min(1),
      primaryCta: z.string().min(1),
    }),
  }),
});

export const collections = { homePage, aboutPage, teamPage, learningHubPage };
