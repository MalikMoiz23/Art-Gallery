# Nuqta — contemporary art gallery

Front-end for a fictional contemporary art gallery in Islamabad. Eight represented
artists, 26 works, across painting, contemporary miniature, calligraphy, woodblock
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

## Layout

```
config.php              constants + view helpers (money, picture, wa_link, lookups)
data/
  artworks.php          the collection
  artists.php           represented artists
  site.php              exhibitions, services, FAQ, booking options
includes/
  head.php              <head>, preloader, grain, scroll rail
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
  js/motion.js          scroll engine — reveal, parallax, split text, pinned track
  js/cart.js            enquiry list state (localStorage)
  js/app.js             interface wiring — chrome, drawer, filters, booking flow
  img/manifest.json     intrinsic size + LQIP blur seed per image
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
all behave. One `requestAnimationFrame` loop drives parallax, the pinned horizontal
track and the scroll-velocity skew; reveals use `IntersectionObserver`.

Reveals never clip the observed element. A fully clipped element has an empty
intersection rectangle, so it would never fire its own observer — the `clip` and `wipe`
variants animate a curtain overlay instead.

**Enquiry list.** Held in `localStorage` under one key. Unique works are capped at a
quantity of one; editioned prints go up to three. Nothing is sent anywhere until the
visitor presses the WhatsApp button.

**Layering.** `assets/css/app.css` is wrapped in Tailwind's `components` layer, which is
declared before `utilities`. Left unlayered it would outrank every utility, and
`hidden sm:grid` on a `.icon-btn` could never hide it.

**Reduced motion.** `prefers-reduced-motion: reduce` drops the preloader, the custom
cursor and the film grain, and reveals all content immediately. The site is fully usable
with JavaScript disabled — a `<noscript>` block neutralises every hidden-by-default state.

**Responsive.** The pinned horizontal gallery section degrades to a snap-scrolling rail
below 1024px using the same markup.

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
