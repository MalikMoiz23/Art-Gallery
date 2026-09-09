<?php
/**
 * Also included directly by artwork.php / artist.php when a slug does not
 * resolve, so it must stand on its own and must not assume it is the entry file.
 */

require_once __DIR__ . '/config.php';

$missing = $missing ?? 'page';

$copy = [
    'work'   => ['Nothing hanging there', 'That work is not in the collection — it may have been collected and taken down. These are on the wall now.'],
    'artist' => ['No such artist here', 'We represent eight artists and that is not one of them. Here they are.'],
    'page'   => ['Wrong door', 'There is nothing at that address. The collection is through here.'],
];
[$heading, $blurb] = $copy[$missing] ?? $copy['page'];

$page_title = $heading;
$page_desc  = $blurb;
$suggest    = featured_works(3);

if (!headers_sent()) {
    http_response_code(404);
}

include __DIR__ . '/includes/head.php';
?>

<main id="main">
  <section class="shell min-h-[72svh] flex flex-col justify-center" style="padding-top:calc(var(--header-h) + 4rem)">
    <p class="label-xs text-brass-600 mb-6 num" data-reveal="right">Error 404</p>
    <h1 class="display d-hero max-w-[18ch]" data-split="lines" data-reveal="fade"><?= e($heading) ?></h1>
    <p class="lede mt-8 max-w-[46ch]" data-reveal="up" data-reveal-delay="220"><?= e($blurb) ?></p>

    <div class="flex flex-wrap gap-3 mt-10" data-reveal="up" data-reveal-delay="300">
      <a href="gallery.php" class="btn" data-magnet="8"><span>The collection</span></a>
      <a href="artists.php" class="btn btn-ghost" data-magnet="8"><span>The artists</span></a>
      <a href="index.php" class="btn btn-ghost" data-magnet="8"><span>Home</span></a>
    </div>
  </section>

  <section class="shell py-20 sm:py-28">
    <p class="label-xs text-paper-500 mb-10" data-reveal="right">While you are here</p>
    <div class="grid sm:grid-cols-3 gap-8 sm:gap-10" data-reveal-group="90">
      <?php foreach ($suggest as $i => $work): ?>
        <div data-reveal="up">
          <?php $index = $i; $eager = false; include __DIR__ . '/components/artwork-card.php'; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
