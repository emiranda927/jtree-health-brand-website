/**
 * Regenerates the favicon set from the forest submark.
 *
 * Google's favicon crawler wants a square icon whose size is a multiple of 48px
 * and checks /favicon.ico at the root as a fallback, so we emit 48/96/144 PNGs
 * plus a multi-resolution .ico alongside the existing 32/64 browser icons.
 *
 *   node scripts/generate-favicons.mjs
 */
import sharp from 'sharp';
import path from 'node:path';
import { writeFile } from 'node:fs/promises';

const root = process.cwd();
const asset = (...parts) => path.join(root, 'public', ...parts);

const cream = { r: 255, g: 250, b: 243, alpha: 1 };

// Rasterize the vector submark once at a comfortable multiple of the largest
// icon, then downscale from that master so every size stays crisp.
const source = await sharp(asset('brand-v2', 'logos', 'submark-forest.svg'))
  .resize({ width: 1024 })
  .png()
  .toBuffer();

// Padding keeps the mark off the edge once Search crops the icon to a circle.
// sharp honors a single resize per pipeline and always extends afterwards, so
// the inner size and the padding have to add up to the target on their own.
const render = (size) => {
  const pad = Math.round(size * 0.08);
  const inner = size - pad * 2;

  return sharp(source)
    .trim()
    .resize(inner, inner, { fit: 'contain', background: cream })
    .extend({ top: pad, bottom: pad, left: pad, right: pad, background: cream })
    .flatten({ background: cream })
    .png({ compressionLevel: 9 })
    .toBuffer();
};

/**
 * Packs PNG buffers into an .ico. Modern browsers, Google, and Windows all read
 * PNG-compressed ICO entries, so no BMP conversion is needed.
 */
const buildIco = (images) => {
  const header = Buffer.alloc(6);
  header.writeUInt16LE(0, 0); // reserved
  header.writeUInt16LE(1, 2); // type: icon
  header.writeUInt16LE(images.length, 4);

  let offset = 6 + images.length * 16;
  const entries = images.map(({ size, data }) => {
    const entry = Buffer.alloc(16);
    entry.writeUInt8(size >= 256 ? 0 : size, 0); // width (0 means 256)
    entry.writeUInt8(size >= 256 ? 0 : size, 1); // height
    entry.writeUInt8(0, 2); // palette count
    entry.writeUInt8(0, 3); // reserved
    entry.writeUInt16LE(1, 4); // color planes
    entry.writeUInt16LE(32, 6); // bits per pixel
    entry.writeUInt32LE(data.length, 8);
    entry.writeUInt32LE(offset, 12);
    offset += data.length;
    return entry;
  });

  return Buffer.concat([header, ...entries, ...images.map((i) => i.data)]);
};

const sizes = [32, 48, 96, 144, 180];
const rendered = Object.fromEntries(
  await Promise.all(sizes.map(async (size) => [size, await render(size)])),
);

await Promise.all([
  ...[32, 48, 96, 144].map((size) =>
    writeFile(asset('brand-v2', 'logos', `favicon-${size}.png`), rendered[size]),
  ),
  writeFile(asset('brand-v2', 'logos', 'apple-touch-icon.png'), rendered[180]),
  writeFile(
    asset('favicon.ico'),
    buildIco([48, 96].map((size) => ({ size, data: rendered[size] }))),
  ),
]);

console.log('Wrote favicon-32/48/96/144.png, apple-touch-icon.png, favicon.ico');
