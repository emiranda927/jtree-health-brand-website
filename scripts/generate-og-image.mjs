import sharp from 'sharp';
import path from 'node:path';
import { readFile } from 'node:fs/promises';

const root = process.cwd();
const asset = (...parts) => path.join(root, 'public', ...parts);

const canvas = { width: 1200, height: 630 };
const colors = {
  cream: '#FFFAF3',
  forest: '#1E3D34',
  sage: '#1D4F42',
  mist: '#8FBFB0',
  lime: '#B8E04A',
  lavender: '#A89FD8',
};

const [baticaBold, fraunces] = await Promise.all([
  readFile(asset('brand-v2', 'fonts', 'batica-sans-bold.ttf')),
  readFile(asset('brand-v2', 'fonts', 'fraunces-supersoft-regular.ttf')),
]);

const logo = await sharp(asset('brand-v2', 'logos', 'primary-forest.svg'))
  .trim()
  .resize({ width: 420 })
  .png()
  .toBuffer();

const character = await sharp(asset('brand-v2', 'spots', 'happy.svg'))
  .trim()
  .resize({ width: 520 })
  .png()
  .toBuffer();

const collage = await sharp(asset('brand-v2', 'collage', 'joshua-tree-cut-collage.webp'))
  .trim()
  .resize({ width: 475 })
  .modulate({ saturation: 0.12, brightness: 1.02 })
  .png()
  .toBuffer();

const trail = await sharp(asset('brand-v2', 'squiggles', 'assorted.svg'))
  .resize({ width: 680 })
  .extract({ left: 0, top: 0, width: 680, height: 630 })
  .png()
  .toBuffer();

const caption = Buffer.from(`
  <svg width="500" height="150" viewBox="0 0 500 150" xmlns="http://www.w3.org/2000/svg">
    <style>
      @font-face {
        font-family: 'Batica Sans';
        font-weight: 700;
        src: url(data:font/ttf;base64,${baticaBold.toString('base64')});
      }
      @font-face {
        font-family: 'Fraunces';
        font-weight: 400;
        src: url(data:font/ttf;base64,${fraunces.toString('base64')});
      }
    </style>
    <text x="0" y="42" fill="${colors.forest}" font-family="Batica Sans, Arial, sans-serif"
      font-size="34" font-weight="700">Adolescent Mental Health</text>
    <text x="0" y="93" fill="${colors.sage}" font-family="Fraunces, Georgia, serif"
      font-size="27">Structured care. Personal growth.</text>
    <rect x="0" y="122" width="260" height="7" rx="3.5" fill="${colors.lime}"/>
  </svg>
`);

await sharp({
  create: {
    width: canvas.width,
    height: canvas.height,
    channels: 4,
    background: colors.cream,
  },
})
  .composite([
    { input: collage, left: 870, top: 345 },
    { input: trail, left: 390, top: 0 },
    { input: character, left: 660, top: 105 },
    { input: logo, left: 66, top: 145 },
    { input: caption, left: 74, top: 330 },
  ])
  .jpeg({ quality: 94, chromaSubsampling: '4:4:4' })
  .toFile(asset('og-image.jpg'));

console.log('Generated public/og-image.jpg (1200×630)');
