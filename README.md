# Nuqta — contemporary art gallery

Front-end for a fictional contemporary art gallery in Islamabad. Eight represented
artists, 25 works, across painting, contemporary miniature, calligraphy, woodblock
print, textile and sculpture.

This is **front-end only**. There is no database, no admin, no payment processing and
no server-side form handling. Every "submit" prepares a WhatsApp or email message for
the visitor to send themselves, so they keep a copy of what they asked for.

## Stack

| Layer | Choice | Why |
| --- | --- | --- |
| Templating | PHP 8.1+ | Partials and data loops without a build step or a framework |
| Styles | Tailwind CSS v4 (compiled) + one hand-written stylesheet | No CDN, no production warning, works offline |
| Behaviour | Vanilla JS, three files, no dependencies | Nothing to audit, nothing to update |
| Fonts | Self-hosted Fraunces + Manrope (variable, woff2) | No font CDN, no layout shift |

No runtime dependencies are fetched from the network. Node is needed only to rebuild
the CSS.

## Palette

Seven neutrals, no accent colour. Interface weight comes from type and space, not hue.

| Token | Hex | Used for |
| --- | --- | --- |
| `paper` | `#FFFFFF` | cards, panels, the mat behind every work |
| `mist` | `#DFE0DB` | default section background |
| `sand` | `#C2BCB4` | hero and accent bands |
| `ink` | `#171917` | body copy and headings |
| `char` | `#282A25` | filled buttons, footer, closing panel |
| `muted` | `#666662` | meta, captions, secondary copy |
| `line` | `#898884` | hairlines and borders |

Defined once in `src/input.css` under `@theme`, which generates `bg-mist`, `text-muted`,
`border-line` and so on. `assets/css/app.css` mirrors them as CSS variables for the
hand-written components.

## Running it

```bash
php -S localhost:8000        # then open http://localhost:8000
```

PHP 8.1 or newer. Nothing else is required to view the site — `assets/css/tailwind.css`
is committed.

To change styles, rebuild the Tailwind bundle:

```bash
npm install
npm run css          # one-off, minified
npm run css:watch    # rebuild on change
```

## Deploying the demo

The PHP here is templating only, so every route has exactly one output and the
whole site pre-renders to flat HTML. That is what lets it sit on a static host.

```bash
npm run build        # -> dist/  (40 pages + assets)
npm run serve:dist   # preview the export at localhost:8010
```

`scripts/build-static.mjs` starts a throwaway `php -S`, fetches every route,
rewrites the links and writes `dist/`. Routes are flattened rather than nested —
`artwork.php?slug=x` becomes `artwork-x.html` — so every page keeps the same
relative depth and the existing `assets/...` links resolve without a `<base>` tag.
It stamps `data-static` on `<html>`; the handful of links JavaScript builds at
runtime (the enquiry drawer, the cart rows) read that flag and follow the same
scheme. The build fails loudly if any page still points at a `.php` URL.

Pushing to `main` triggers `.github/workflows/pages.yml`, which runs that build
and publishes `dist/` to GitHub Pages.

The workflow enables Pages itself on first run. If your account or org blocks
that, set *Settings → Pages → Build and deployment → Source* to **GitHub
Actions** once by hand and re-run the workflow.

Query strings survive the export, so `gallery.html?category=Print` and
`booking.html?work=one-chance` still work — both are read on the client.

## Layout

```
config.php              constants + view helpers (money, picture, wa_link, lookups)
data/
  artworks.php          the collection
  artists.php           represented artists
  site.php              exhibitions, services, FAQ, booking options
includes/
  head.php              <head>, scroll-progress rail, skip link
  header.php            fixed header + full-screen mobile menu
  footer.php            footer, then the drawer/WhatsApp partials and scripts
  cart-drawer.php       slide-in enquiry list
  whatsapp.php          floating button with pre-filled prompts
components/
  artwork-card.php      collection card (also carries the filter data attributes)
  artist-card.php       typographic monogram card
  section-head.php      section header with index, kicker, split heading
src/input.css           Tailwind entry: @theme tokens, keyframes, custom utilities
assets/
  css/app.css           hand-written layer: motion, framed artwork, chrome
  css/tailwind.css      built output (committed)
  js/motion.js          scroll engine — reveal, parallax, split text, velocity skew
  js/cart.js            enquiry list state (localStorage)
  js/app.js             interface wiring — chrome, drawer, filters, booking flow
  img/manifest.json     intrinsic size + LQIP blur seed per image
scripts/
  build-static.mjs      pre-renders every route into dist/ for static hosting
index.php gallery.php artwork.php artists.php artist.php
cart.php booking.php contact.php 404.php
```

`artwork.php`, `artist.php` and `booking.php` read a `?slug=` / `?work=` query string
and fall back to `404.php` when it does not resolve.

## Configuration

Everything a real gallery would need to change lives at the top of `config.php`:

```php
const WHATSAPP_NUMBER = '923000000000';   // digits only, international format
const PHONE_DISPLAY   = '+92 300 0000000';
const EMAIL           = 'hello@nuqta.gallery';
const ADDRESS_LINE_1  = 'Studio 6, Kohsar Block';
```

The WhatsApp number is referenced once and flows to every deep link, including the
ones built in JavaScript (it is passed through `data-wa` on `<html>`).

Artwork titles, prices, dimensions and stories are edited directly in
`data/artworks.php`. Image dimensions and blur placeholders are **not** stored there —
`media()` reads them from `assets/img/manifest.json` so the data files stay readable.

## How a few things work

**Progressive images.** Every `<img>` is emitted by `picture()` with its true intrinsic
width and height, so nothing shifts as images arrive. The LQIP blur from the manifest
is painted as a CSS background underneath, and the image fades in over it.

**Motion.** Native scrolling is kept deliberately — no transform-based smooth-scroll
wrapper — so `position: sticky`, anchor links, find-in-page and the mobile address bar
all behave. One `requestAnimationFrame` loop drives parallax and the scroll-velocity
skew; reveals use `IntersectionObserver`. There is no loading screen and no page-
transition wipe: pages simply appear.

Reveals never clip the observed element. A fully clipped element has an empty
intersection rectangle, so it would never fire its own observer — the `clip` and `wipe`
variants animate a curtain overlay instead.

**Enquiry list.** Held in `localStorage` under one key. Unique works are capped at a
quantity of one; editioned prints go up to three. Nothing is sent anywhere until the
visitor presses the WhatsApp button.

**Layering.** `assets/css/app.css` is wrapped in Tailwind's `components` layer, which is
declared before `utilities`. Left unlayered it would outrank every utility, and
`hidden sm:grid` on a `.icon-btn` could never hide it.

**Reduced motion.** `prefers-reduced-motion: reduce` reveals all content immediately and
stills every transition. The site is fully usable with JavaScript disabled — a
`<noscript>` block neutralises every hidden-by-default state.

**Responsive.** Verified at 320, 375, 414, 600, 768, 834, 1024, 1280, 1440 and 1920px:
no horizontal overflow, and the gallery filter row scrolls sideways only on narrow
screens, wrapping once there is room for it.

## Image credits

All photography is public domain (CC0) from the
[Art Institute of Chicago open-access collection](https://www.artic.edu/open-access/open-access-images).

**The gallery, the artists, the titles, the prices and the exhibition history are all
invented** for this front-end. The CC0 images stand in as visuals only — they are not
works by the named artists, and nothing here should be read as a real listing.

Fonts: [Fraunces](https://fonts.google.com/specimen/Fraunces) and
[Manrope](https://fonts.google.com/specimen/Manrope), both SIL Open Font License.

## Known limits

- Forms validate and compose a message; they do not POST anywhere.
- The enquiry list is per-browser and is lost when site data is cleared.
- Prices, availability and the exhibition calendar are static values in `data/`.
