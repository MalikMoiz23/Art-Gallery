<?php
/**
 * Site-wide constants and view helpers.
 * Every page includes this first; it also loads the data layer.
 */

declare(strict_types=1);

const SITE_NAME     = 'Nuqta';
const SITE_FULL     = 'Nuqta Contemporary';
const SITE_TAGLINE  = 'Contemporary art gallery — Islamabad';
const SITE_EST      = 2011;

const WHATSAPP_NUMBER = '923000000000';   // digits only, international format
const PHONE_DISPLAY   = '+92 300 0000000';
const EMAIL           = 'hello@nuqta.gallery';
const ADDRESS_LINE_1  = 'Studio 6, Kohsar Block';
const ADDRESS_LINE_2  = 'F-7 Markaz, Islamabad 44000';
const MAP_QUERY       = 'F-7 Markaz, Islamabad, Pakistan';

const CURRENCY_SYMBOL = '₨';
const CURRENCY_CODE   = 'PKR';

const OPENING_HOURS = [
    'Tuesday – Saturday' => '11:00 – 19:00',
    'Sunday'             => '13:00 – 18:00',
    'Monday'             => 'By appointment',
];

const SOCIALS = [
    ['label' => 'Instagram', 'handle' => '@nuqta.gallery', 'url' => '#'],
    ['label' => 'Facebook',  'handle' => '/nuqtagallery',  'url' => '#'],
    ['label' => 'Behance',   'handle' => '/nuqta',         'url' => '#'],
];

require_once __DIR__ . '/data/artists.php';
require_once __DIR__ . '/data/artworks.php';
require_once __DIR__ . '/data/site.php';

/* ------------------------------------------------------------------ output */

/** Escape for HTML text and attribute context. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Format a rupee amount, e.g. 685000 -> "₨ 685,000". */
function money(int $amount): string
{
    return CURRENCY_SYMBOL . ' ' . number_format($amount);
}

/** Compact rupee amount for tight spaces, e.g. "₨ 6.85 L". */
function money_short(int $amount): string
{
    if ($amount >= 10000000) {
        return CURRENCY_SYMBOL . ' ' . rtrim(rtrim(number_format($amount / 10000000, 2), '0'), '.') . ' Cr';
    }
    if ($amount >= 100000) {
        return CURRENCY_SYMBOL . ' ' . rtrim(rtrim(number_format($amount / 100000, 2), '0'), '.') . ' L';
    }
    return money($amount);
}

/** Pre-filled WhatsApp deep link. */
function wa_link(string $message = ''): string
{
    $message = $message !== '' ? $message : 'Hello ' . SITE_NAME . ', I would like to know more about the current collection.';
    return 'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . rawurlencode($message);
}

function map_link(): string
{
    return 'https://maps.google.com/?q=' . rawurlencode(MAP_QUERY);
}

/* ------------------------------------------------------------------- media */

/**
 * Intrinsic size plus LQIP blur seed for a downloaded asset.
 * Kept out of the hand-written data files so those stay readable.
 */
function media(string $file): array
{
    static $index = null;

    if ($index === null) {
        $index = [];
        $raw = @file_get_contents(__DIR__ . '/assets/img/manifest.json');
        if ($raw !== false) {
            foreach ((array) json_decode($raw, true) as $bucket) {
                foreach ((array) $bucket as $item) {
                    $index[$item['file']] = $item;
                }
            }
        }
    }

    return $index[$file] ?? ['w' => 1000, 'h' => 1250, 'lqip' => '', 'ratio' => 0.8];
}

function img_src(string $file): string
{
    return 'assets/img/' . $file;
}

/**
 * Progressive <img>: correct intrinsic ratio, LQIP background, lazy decode.
 * $priority marks the one above-the-fold image that must not be lazy.
 */
function picture(string $file, string $alt, string $class = '', bool $priority = false, string $extra = ''): string
{
    $m = media($file);
    $style = $m['lqip'] !== ''
        ? "background-image:url('{$m['lqip']}');background-size:cover;background-position:center;"
        : 'background-color:#1c1917;';

    return sprintf(
        '<img src="%s" alt="%s" width="%d" height="%d" class="%s" style="%s" %s %s data-img>',
        e(img_src($file)),
        e($alt),
        (int) $m['w'],
        (int) $m['h'],
        e($class),
        $style,
        $priority ? 'loading="eager" fetchpriority="high" decoding="sync"' : 'loading="lazy" decoding="async"',
        $extra
    );
}

/* -------------------------------------------------------------------- data */

function all_artworks(): array
{
    return ARTWORKS;
}

function all_artists(): array
{
    return ARTISTS;
}

function find_artwork(string $slug): ?array
{
    foreach (ARTWORKS as $work) {
        if ($work['slug'] === $slug) {
            return $work;
        }
    }
    return null;
}

function find_artist(string $slug): ?array
{
    foreach (ARTISTS as $artist) {
        if ($artist['slug'] === $slug) {
            return $artist;
        }
    }
    return null;
}

function artist_name(string $slug): string
{
    $artist = find_artist($slug);
    return $artist['name'] ?? 'Unattributed';
}

function works_by_artist(string $slug): array
{
    return array_values(array_filter(ARTWORKS, fn ($w) => $w['artist'] === $slug));
}

function featured_works(int $limit = 6): array
{
    return array_slice(array_values(array_filter(ARTWORKS, fn ($w) => !empty($w['featured']))), 0, $limit);
}

/** Same category first, then anything else by the same hand. */
function related_works(array $work, int $limit = 4): array
{
    $pool = array_filter(ARTWORKS, fn ($w) => $w['slug'] !== $work['slug']);
    usort($pool, function ($a, $b) use ($work) {
        $score = fn ($w) => ($w['category'] === $work['category'] ? 2 : 0) + ($w['artist'] === $work['artist'] ? 3 : 0);
        return $score($b) <=> $score($a);
    });
    return array_slice(array_values($pool), 0, $limit);
}

function categories(): array
{
    $counts = [];
    foreach (ARTWORKS as $work) {
        $counts[$work['category']] = ($counts[$work['category']] ?? 0) + 1;
    }
    ksort($counts);
    return $counts;
}

function price_range(): array
{
    $prices = array_column(ARTWORKS, 'price');
    return [min($prices), max($prices)];
}

/** Status chip copy and colour token. */
function status_meta(string $status): array
{
    return [
        'available' => ['label' => 'Available',  'tone' => 'brass'],
        'reserved'  => ['label' => 'Reserved',   'tone' => 'clay'],
        'sold'      => ['label' => 'Collected',  'tone' => 'muted'],
    ][$status] ?? ['label' => ucfirst($status), 'tone' => 'muted'];
}

/** Marks the active nav item. */
function is_current(string $file): bool
{
    return basename($_SERVER['SCRIPT_NAME'] ?? '') === $file;
}

/** Read a query-string value without notices. */
function query(string $key, string $default = ''): string
{
    $value = $_GET[$key] ?? $default;
    return is_string($value) ? trim($value) : $default;
}
