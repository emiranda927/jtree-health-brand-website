import { existsSync, readFileSync, readdirSync, statSync } from 'node:fs';
import { join, relative, resolve, sep } from 'node:path';

const root = resolve(process.cwd());
const dist = join(root, 'dist');
const failures = [];
const pages = [];

if (!existsSync(dist)) {
  console.error('SEO audit requires a build. Run npm run build first.');
  process.exit(1);
}

function walk(dir) {
  return readdirSync(dir).flatMap((name) => {
    const path = join(dir, name);
    return statSync(path).isDirectory() ? walk(path) : [path];
  });
}

function routeFor(file) {
  const path = relative(dist, file).split(sep).join('/');
  if (path === 'index.html') return '/';
  if (path.endsWith('/index.html')) return `/${path.slice(0, -'/index.html'.length)}/`;
  return `/${path}`;
}

function matches(html, pattern) {
  return [...html.matchAll(pattern)];
}

function attr(tag, name) {
  const match = tag.match(new RegExp(`\\s${name}=["']([^"']*)["']`, 'i'));
  return match?.[1] ?? null;
}

function add(route, message) {
  failures.push(`${route}: ${message}`);
}

for (const file of walk(dist).filter((file) => file.endsWith('.html'))) {
  const html = readFileSync(file, 'utf8');
  const route = routeFor(file);
  const titleTags = matches(html, /<title(?:\s[^>]*)?>([\s\S]*?)<\/title>/gi);
  const descriptions = matches(html, /<meta\s+[^>]*name=["']description["'][^>]*>/gi);
  const canonicals = matches(html, /<link\s+[^>]*rel=["']canonical["'][^>]*>/gi);
  const h1s = matches(html, /<h1(?:\s[^>]*)?>[\s\S]*?<\/h1>/gi);
  const robotsTag = matches(html, /<meta\s+[^>]*name=["']robots["'][^>]*>/gi)[0]?.[0] ?? '';
  const robots = attr(robotsTag, 'content') ?? '';
  const indexable = !/\bnoindex\b/i.test(robots) && route !== '/404.html';

  if (titleTags.length !== 1) add(route, `expected one title, found ${titleTags.length}`);
  if (descriptions.length !== 1) add(route, `expected one meta description, found ${descriptions.length}`);
  if (canonicals.length !== 1) add(route, `expected one canonical, found ${canonicals.length}`);
  if (h1s.length !== 1) add(route, `expected one H1, found ${h1s.length}`);

  if (canonicals.length === 1) {
    const href = attr(canonicals[0][0], 'href');
    if (!href?.startsWith('https://www.jtreehealth.com/')) add(route, `invalid production canonical: ${href ?? 'missing href'}`);
  }

  for (const [tag] of matches(html, /<img\s+[^>]*>/gi)) {
    if (!/\salt(?:\s|=|>)/i.test(tag)) add(route, `image missing alt attribute: ${tag.slice(0, 100)}`);
  }

  for (const [, raw] of matches(html, /<script\s+[^>]*type=["']application\/ld\+json["'][^>]*>([\s\S]*?)<\/script>/gi)) {
    try { JSON.parse(raw); } catch (error) { add(route, `invalid JSON-LD: ${error.message}`); }
  }

  pages.push({ file, html, route, indexable, title: titleTags[0]?.[1]?.trim() ?? '', description: attr(descriptions[0]?.[0] ?? '', 'content') ?? '' });
}

const indexablePages = pages.filter((page) => page.indexable);
for (const key of ['title', 'description']) {
  const owners = new Map();
  for (const page of indexablePages) {
    const value = page[key];
    if (!value) add(page.route, `empty ${key}`);
    else if (owners.has(value)) add(page.route, `duplicate ${key} also used by ${owners.get(value)}`);
    else owners.set(value, page.route);
  }
}

function targetExists(pathname) {
  let decoded;
  try { decoded = decodeURIComponent(pathname); } catch { decoded = pathname; }
  if (decoded === '/') return existsSync(join(dist, 'index.html'));
  const clean = decoded.replace(/^\//, '').replace(/\/$/, '');
  return existsSync(join(dist, clean)) || existsSync(join(dist, `${clean}.html`)) || existsSync(join(dist, clean, 'index.html'));
}

for (const page of pages) {
  for (const [, href] of matches(page.html, /<a\s+[^>]*href=["']([^"']+)["'][^>]*>/gi)) {
    if (!href.startsWith('/') || href.startsWith('//')) continue;
    const pathname = href.split(/[?#]/)[0];
    if (pathname && !targetExists(pathname)) add(page.route, `broken internal link: ${href}`);
  }
}

const sitemapFiles = walk(dist).filter((file) => /sitemap-\d+\.xml$/.test(file));
const sitemapRoutes = new Set();
for (const file of sitemapFiles) {
  const xml = readFileSync(file, 'utf8');
  for (const [, url] of matches(xml, /<loc>(https:\/\/www\.jtreehealth\.com[^<]*)<\/loc>/g)) {
    sitemapRoutes.add(new URL(url).pathname);
  }
}
for (const page of indexablePages) {
  if (!sitemapRoutes.has(page.route)) add(page.route, 'indexable page missing from sitemap');
}
for (const route of sitemapRoutes) {
  const page = pages.find((candidate) => candidate.route === route);
  if (!page) add(route, 'sitemap URL has no built page');
  else if (!page.indexable) add(route, 'noindex page appears in sitemap');
}

if (failures.length) {
  console.error(`SEO audit failed with ${failures.length} issue(s):`);
  failures.forEach((failure) => console.error(`- ${failure}`));
  process.exit(1);
}

console.log(`SEO audit passed for ${pages.length} built pages (${indexablePages.length} indexable).`);
