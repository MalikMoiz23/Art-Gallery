/**
 * Pre-renders the PHP site into a folder of flat HTML files.
 *
 * The PHP here is templating only — no database, no server-side form handling —
 * so every route has exactly one output and can be served as a static file.
 * That is what lets the demo sit on GitHub Pages.
 *
 *   node scripts/build-static.mjs            # -> dist/
 *   PHP_BIN=/path/to/php node scripts/...    # if php is not on PATH
 *
 * Routes are flattened rather than nested (artwork-<slug>.html, not
 * artwork/<slug>/index.html) so every page keeps the same relative depth and
 * the existing assets/... links keep resolving without a <base> tag.
 */

import { spawn, execFileSync } from 'node:child_process';
import fs from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const ROOT = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const OUT = path.join(ROOT, 'dist');
const PHP = process.env.PHP_BIN || 'php';
const PORT = Number(process.env.PORT || 8791);
const BASE = `http://127.0.0.1:${PORT}/`;

const STATIC_PAGES = ['index', 'gallery', 'artists', 'cart', 'booking', 'contact', '404'];

/* --------------------------------------------------------------- helpers */

const say = (...a) => console.log(...a);

function phpJson(code) {
  const out = execFileSync(PHP, ['-r', code], { cwd: ROOT, encoding: 'utf8' });
  return JSON.parse(out);
}

async function waitForServer(timeoutMs = 15000) {
  const deadline = Date.now() + timeoutMs;
  while (Date.now() < deadline) {
    try {
      const r = await fetch(BASE + 'index.php');
      if (r.ok) return;
    } catch { /* not up yet */ }
    await new Promise(r => setTimeout(r, 200));
  }
  throw new Error('PHP dev server did not come up on port ' + PORT);
}

async function copyDir(from, to) {
  await fs.mkdir(to, { recursive: true });
  for (const entry of await fs.readdir(from, { withFileTypes: true })) {
    const src = path.join(from, entry.name);
    const dst = path.join(to, entry.name);
    if (entry.isDirectory()) await copyDir(src, dst);
    else await fs.copyFile(src, dst);
  }
}

/* ------------------------------------------------------------- rewriting */

/** artwork.php?slug=x -> artwork-x.html, gallery.php?category=y -> gallery.html?category=y */
function mapHref(href) {
  const m = href.match(/^([a-z0-9-]+)\.php(?:\?(.*))?$/i);
  if (!m) return href;
  const [, page, query = ''] = m;

  const slug = /(?:^|&)slug=([^&]+)/.exec(query);
  if (slug && (page === 'artwork' || page === 'artist')) {
    return `${page}-${decodeURIComponent(slug[1])}.html`;
  }
  // Everything else keeps its query string; the JS reads it from location.search.
  return `${page}.html${query ? '?' + query : ''}`;
}

function rewrite(html) {
  // Tell the runtime it is running as a flat static build.
  html = html.replace(/<html\s/i, '<html data-static="1" ');

  // Only touch same-site .php targets; leave wa.me, mailto: and tel: alone.
  return html.replace(/\b(href|action)="([^"]+)"/g, (whole, attr, value) => {
    if (/^(https?:|mailto:|tel:|#|data:)/i.test(value)) return whole;
    return `${attr}="${mapHref(value)}"`;
  });
}

/* ------------------------------------------------------------------ main */

say('· reading routes from the data layer');
const slugs = phpJson(
  "require 'config.php';" +
  "echo json_encode(['works' => array_column(ARTWORKS, 'slug'), 'artists' => array_column(ARTISTS, 'slug')]);"
);

const routes = [
  ...STATIC_PAGES.map(p => ({ url: `${p}.php`, file: `${p}.html` })),
  ...slugs.works.map(s => ({ url: `artwork.php?slug=${encodeURIComponent(s)}`, file: `artwork-${s}.html` })),
  ...slugs.artists.map(s => ({ url: `artist.php?slug=${encodeURIComponent(s)}`, file: `artist-${s}.html` })),
];

say(`· ${routes.length} routes (${slugs.works.length} works, ${slugs.artists.length} artists)`);

say('· starting php -S on port ' + PORT);
const server = spawn(PHP, ['-S', `127.0.0.1:${PORT}`, '-t', '.'], { cwd: ROOT, stdio: 'ignore' });
let failed = null;

try {
  await waitForServer();

  await fs.rm(OUT, { recursive: true, force: true });
  await fs.mkdir(OUT, { recursive: true });

  let bytes = 0;
  for (const route of routes) {
    const res = await fetch(BASE + route.url);
    // 404.php is expected to answer 404; everything else must be 200.
    const okStatus = route.file === '404.html' ? [200, 404] : [200];
    if (!okStatus.includes(res.status)) {
      throw new Error(`${route.url} returned ${res.status}`);
    }
    const html = rewrite(await res.text());
    if (/\.php["?]/.test(html)) {
      const leftover = (html.match(/[a-z0-9-]+\.php[^"]*/gi) || []).slice(0, 3);
      throw new Error(`${route.file} still links to PHP: ${leftover.join(', ')}`);
    }
    await fs.writeFile(path.join(OUT, route.file), html);
    bytes += Buffer.byteLength(html);
  }
  say(`· wrote ${routes.length} pages (${(bytes / 1024).toFixed(0)} KB of HTML)`);

  await copyDir(path.join(ROOT, 'assets'), path.join(OUT, 'assets'));
  say('· copied assets');

  // Stops GitHub Pages running the output through Jekyll.
  await fs.writeFile(path.join(OUT, '.nojekyll'), '');

  say(`\nBuilt to dist/ — preview with:  ${PHP} -S localhost:8010 -t dist`);
} catch (err) {
  failed = err;
} finally {
  server.kill();
}

if (failed) {
  console.error('\nBuild failed:', failed.message);
  process.exit(1);
}
