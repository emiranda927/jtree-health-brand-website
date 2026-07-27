import { getCollection, type CollectionEntry } from 'astro:content';

export type NewsEntry = CollectionEntry<'news'>;

/*
 * Everything here resolves at BUILD time. The site is statically generated, so
 * "upcoming" only advances when something rebuilds — see the nightly cron in
 * vercel.json. Comparisons are done on YYYY-MM-DD strings, which sort
 * lexicographically and sidestep timezone drift entirely.
 */
export function buildDate(): string {
  return new Date().toISOString().slice(0, 10);
}

/** IANA offset for the clinic's timezone on a given date, e.g. "-04:00". */
export function clinicOffset(isoDate: string): string {
  const parts = new Intl.DateTimeFormat('en-US', {
    timeZone: 'America/New_York',
    timeZoneName: 'longOffset',
  }).formatToParts(new Date(`${isoDate}T12:00:00Z`));
  const name = parts.find((p) => p.type === 'timeZoneName')?.value ?? 'GMT-05:00';
  return name.replace('GMT', '') || '-05:00';
}

/** "2026-07-28" -> "Jul 28". Parsed as UTC so the day never shifts. */
export function formatShortDate(isoDate: string): string {
  return new Intl.DateTimeFormat('en-US', {
    month: 'short',
    day: 'numeric',
    timeZone: 'UTC',
  }).format(new Date(`${isoDate}T00:00:00Z`));
}

/** "2026-07-28" -> "Tuesday, July 28, 2026". */
export function formatLongDate(isoDate: string): string {
  return new Intl.DateTimeFormat('en-US', {
    weekday: 'long',
    month: 'long',
    day: 'numeric',
    year: 'numeric',
    timeZone: 'UTC',
  }).format(new Date(`${isoDate}T00:00:00Z`));
}

/** "17:00" -> "5:00 PM". */
export function formatTime(time: string): string {
  const [h, m] = time.split(':').map(Number);
  const suffix = h >= 12 ? 'PM' : 'AM';
  const hour = h % 12 === 0 ? 12 : h % 12;
  return `${hour}:${String(m).padStart(2, '0')} ${suffix}`;
}

export function isPast(entry: NewsEntry, today = buildDate()): boolean {
  return entry.data.endDate < today;
}

/** Sessions that haven't happened yet, in date order. */
export function upcomingSessions(entry: NewsEntry, today = buildDate()) {
  return (entry.data.event?.sessions ?? []).filter((s) => s.date >= today);
}

/** All news, newest first, split into current and past. */
export async function getNews(today = buildDate()) {
  const all = (await getCollection('news')).sort((a, b) =>
    b.data.publishDate.localeCompare(a.data.publishDate),
  );
  return {
    current: all.filter((e) => !isPast(e, today)),
    past: all.filter((e) => isPast(e, today)),
  };
}

/** The single item eligible for the homepage callout, if any. */
export async function homeCallout(today = buildDate()) {
  const { current } = await getNews(today);
  return current.find(
    (e) =>
      e.data.featureOnHome &&
      e.data.callout &&
      (e.data.homeUntil ?? e.data.endDate) >= today,
  );
}

/**
 * One schema.org Event per session. Google does not support `eventSchedule`,
 * and these sessions are booked individually, so a separate Event per date is
 * the shape Google asks for:
 * https://developers.google.com/search/docs/appearance/structured-data/event
 *
 * Sessions share one URL rather than getting leaf pages, which is a deliberate
 * trade: no rich-result guarantee, but no twelve thin pages either.
 */
export function eventJsonLd(entry: NewsEntry, pageUrl: string) {
  const e = entry.data.event;
  if (!e) return [];
  return e.sessions.map((session) => {
    const offset = clinicOffset(session.date);
    return {
      '@type': 'Event',
      name: `${entry.data.title}: ${session.topic}`,
      description: `${session.topic}. Part of the ${entry.data.title} series for parents and caregivers of teens and preteens.`,
      startDate: `${session.date}T${e.startTime}:00${offset}`,
      endDate: `${session.date}T${e.endTime}:00${offset}`,
      eventStatus: 'https://schema.org/EventScheduled',
      eventAttendanceMode: 'https://schema.org/OfflineEventAttendanceMode',
      url: pageUrl,
      isAccessibleForFree: e.price === 0,
      location: {
        '@type': 'Place',
        name: e.locationName,
        address: {
          '@type': 'PostalAddress',
          streetAddress: e.streetAddress,
          addressLocality: e.addressLocality,
          addressRegion: e.addressRegion,
          postalCode: e.postalCode,
          addressCountry: 'US',
        },
      },
      organizer: { '@id': 'https://www.jtreehealth.com/#organization' },
      performer: { '@id': 'https://www.jtreehealth.com/#organization' },
      offers: {
        '@type': 'Offer',
        price: e.price,
        priceCurrency: e.priceCurrency,
        url: e.registrationUrl,
        availability: 'https://schema.org/InStock',
        validFrom: `${entry.data.publishDate}T00:00:00${clinicOffset(entry.data.publishDate)}`,
      },
    };
  });
}
