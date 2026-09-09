<?php
/**
 * Opens the document and paints the fixed chrome.
 * Set $page_title and $page_desc before including.
 */

$page_title = $page_title ?? SITE_FULL;
$page_desc  = $page_desc ?? 'A contemporary art gallery in Islamabad representing eight artists across painting, miniature, calligraphy, print, textile and sculpture.';
$body_class = $body_class ?? '';
?>
<!DOCTYPE html>
<html lang="en" data-wa="<?= e(WHATSAPP_NUMBER) ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($page_title) ?> — <?= e(SITE_NAME) ?></title>
<meta name="description" content="<?= e($page_desc) ?>">
<meta name="theme-color" content="#090807">
<meta property="og:title" content="<?= e($page_title) ?> — <?= e(SITE_NAME) ?>">
<meta property="og:description" content="<?= e($page_desc) ?>">
<meta property="og:type" content="website">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' fill='%23090807'/%3E%3Ccircle cx='16' cy='16' r='6' fill='%23C8A45C'/%3E%3C/svg%3E">

<link rel="preload" href="assets/fonts/manrope-latin-normal.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="assets/fonts/fraunces-latin-normal.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="assets/css/fonts.css">
<link rel="stylesheet" href="assets/css/tailwind.css">
<link rel="stylesheet" href="assets/css/app.css">

<noscript><style>
  [data-reveal],[data-reveal].is-in{opacity:1!important;transform:none!important;clip-path:none!important}
  .split-line>i,.split-char{opacity:1!important;transform:none!important}
  [data-img]{opacity:1!important;transform:none!important}
  [data-reveal="clip"]::after,[data-reveal="wipe"]::after{display:none!important}
  .preloader{display:none!important}
</style></noscript>
</head>
<body class="is-locked <?= e($body_class) ?>">

<div class="preloader">
  <div class="text-center">
    <p class="display d-1 leading-none"><?= e(SITE_NAME) ?><span class="text-brass-500">.</span></p>
    <p class="label-xs text-paper-500 mt-3 mb-8"><?= e(SITE_TAGLINE) ?></p>
    <div class="pre-bar mx-auto"><i></i></div>
    <p class="label-xs num text-brass-500 mt-4"><span data-pre-count>00</span><span class="text-paper-500">/100</span></p>
  </div>
</div>

<div class="page-veil"></div>
<div class="grain" aria-hidden="true"></div>
<div class="scroll-rail" aria-hidden="true"><i></i></div>

<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[110] focus:bg-paper-50 focus:text-ink-900 focus:px-4 focus:py-2">Skip to content</a>

<?php include __DIR__ . '/header.php'; ?>
