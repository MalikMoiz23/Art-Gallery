<?php
require_once __DIR__ . '/config.php';

$page_title = 'Contemporary art gallery in Islamabad';
$page_desc  = 'Nuqta represents eight Pakistani artists across painting, miniature, calligraphy, print, textile and sculpture. Currently showing Warm Ground by Sana Farooqi.';

$featured  = featured_works(6);
$hero_work = find_artwork('nightfall-ravi');
$hero_side = array_filter([find_artwork('the-listener'), find_artwork('one-letter-standing')]);
$cats      = categories();
$range     = price_range();

include __DIR__ . '/includes/head.php';
?>

<main id="main">

  <!-- ============================================================ hero -->
  <section class="bg-sand" style="padding-top:calc(var(--header-h) + clamp(2rem, 4vw, 3.5rem))">
    <div class="shell pb-14 sm:pb-20">
      <div class="grid lg:grid-cols-12 gap-x-12 gap-y-14 items-center">

        <div class="lg:col-span-7">
          <a href="gallery.php" class="inline-flex flex-wrap items-center gap-x-3 gap-y-1 mb-7 group" data-reveal="right">
            <span class="relative flex w-2 h-2">
              <span class="absolute inset-0 rounded-full bg-char animate-pulse-ring"></span>
              <span class="relative w-2 h-2 rounded-full bg-char"></span>
            </span>
            <span class="label-xs group-hover:text-muted transition-colors">Now showing — <?= e(EXHIBITION_CURRENT['title']) ?></span>
            <span class="label-xs text-muted num">until <?= e(EXHIBITION_CURRENT['to']) ?></span>
          </a>

          <h1 class="display d-hero" data-split="chars" data-reveal="fade">Stand closer.</h1>

          <p class="lede mt-7 max-w-[46ch] text-char/75" data-reveal="up" data-reveal-delay="380">
            Eight artists, <?= count(all_artworks()) ?> works on the wall this season, and a room on Kohsar Block
            where nobody hurries you.
          </p>

          <div class="flex flex-wrap items-center gap-3 mt-9" data-reveal="up" data-reveal-delay="480">
            <a href="gallery.php" class="btn" data-magnet="7"><span>See the collection</span></a>
            <a href="booking.php" class="btn btn-ghost" data-magnet="7"><span>Book a viewing</span></a>
          </div>
        </div>

        <div class="lg:col-span-5" data-reveal="up" data-reveal-delay="220">
          <?php if ($hero_work): $hm = media($hero_work['img']); ?>
            <div class="flex flex-col-reverse lg:flex-row lg:items-end gap-3 sm:gap-4 max-w-[420px] mx-auto lg:max-w-[480px] lg:ml-auto lg:mr-0">

              <!-- Two supporting works, a row beneath on phones and a column beside on desktop. -->
              <div class="flex lg:flex-col gap-3 sm:gap-4 lg:w-[27%] shrink-0">
                <?php foreach ($hero_side as $side): $sm = media($side['img']); ?>
                  <a href="artwork.php?slug=<?= e($side['slug']) ?>"
                     class="block flex-1 lg:flex-none min-w-0"
                     aria-label="<?= e($side['title']) ?> by <?= e(artist_name($side['artist'])) ?>">
                    <div class="frame frame-sm">
                      <div class="frame-mat">
                        <div class="media" style="aspect-ratio:<?= (int) $sm['w'] ?>/<?= (int) $sm['h'] ?>">
                          <?= picture($side['img'], $side['title'] . ' by ' . artist_name($side['artist'])) ?>
                        </div>
                      </div>
                    </div>
                  </a>
                <?php endforeach; ?>
              </div>

              <a href="artwork.php?slug=<?= e($hero_work['slug']) ?>" class="block flex-1 min-w-0">
                <div class="frame">
                  <div class="frame-mat">
                    <div class="media" style="aspect-ratio:<?= (int) $hm['w'] ?>/<?= (int) $hm['h'] ?>">
                      <?= picture($hero_work['img'], $hero_work['title'] . ' by ' . artist_name($hero_work['artist']), '', true) ?>
                    </div>
                  </div>
                </div>
                <div class="flex items-baseline justify-between gap-4 mt-4">
                  <div class="min-w-0">
                    <p class="display d-4"><?= e($hero_work['title']) ?></p>
                    <p class="label-xs text-muted mt-1.5"><?= e(artist_name($hero_work['artist'])) ?> · <?= (int) $hero_work['year'] ?></p>
                  </div>
                  <p class="num text-sm shrink-0"><?= e(money_short($hero_work['price'])) ?></p>
                </div>
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <dl class="grid grid-cols-2 md:grid-cols-4 gap-x-8 gap-y-7 mt-4 pt-10 rule-t" data-reveal-group="80">
        <?php foreach (STATS as $stat): ?>
          <div data-reveal="up">
            <dt class="display d-2 num"><span data-count="<?= (int) $stat['value'] ?>">0</span><?= e($stat['suffix']) ?></dt>
            <dd class="label-xs text-muted mt-2 leading-relaxed"><?= e($stat['label']) ?></dd>
          </div>
        <?php endforeach; ?>
      </dl>
    </div>
  </section>

  <!-- =================================================== exhibition -->
  <section class="bg-mist py-20 sm:py-28">
    <div class="shell grid lg:grid-cols-12 gap-10 lg:gap-16 items-center">

      <div class="lg:col-span-6 order-2 lg:order-1">
        <p class="label-xs text-muted flex items-center gap-3 mb-5" data-reveal="right">
          <span class="num">01</span><span class="w-7 h-px bg-line"></span>This season
        </p>
        <h2 class="display d-1" data-split="lines" data-reveal="fade"><?= e(EXHIBITION_CURRENT['title']) ?></h2>
        <p class="ital d-3 mt-3 text-muted" data-reveal="up" data-reveal-delay="140"><?= e(EXHIBITION_CURRENT['subtitle']) ?></p>
        <p class="lede mt-6 max-w-[48ch]" data-reveal="up" data-reveal-delay="200"><?= e(EXHIBITION_CURRENT['blurb']) ?></p>

        <dl class="grid grid-cols-3 gap-6 mt-9 pt-7 rule-t" data-reveal-group="70">
          <div data-reveal="up">
            <dt class="label-xs text-muted mb-1.5">Dates</dt>
            <dd class="text-sm num"><?= e(EXHIBITION_CURRENT['from']) ?> – <?= e(EXHIBITION_CURRENT['to']) ?></dd>
          </div>
          <div data-reveal="up">
            <dt class="label-xs text-muted mb-1.5">Room</dt>
            <dd class="text-sm"><?= e(EXHIBITION_CURRENT['room']) ?></dd>
          </div>
          <div data-reveal="up">
            <dt class="label-xs text-muted mb-1.5">Entry</dt>
            <dd class="text-sm">Free, no booking</dd>
          </div>
        </dl>

        <div class="flex flex-wrap gap-3 mt-8" data-reveal="up" data-reveal-delay="240">
          <a href="artist.php?slug=sana-farooqi" class="btn btn-ghost"><span>About the artist</span></a>
          <a href="gallery.php?category=Painting" class="btn btn-ghost"><span>See the paintings</span></a>
        </div>

        <p class="text-sm text-muted mt-9 pt-6 rule-t max-w-[52ch]" data-reveal="up">
          <span class="label-xs text-ink">Next — <?= e(EXHIBITION_NEXT['from']) ?></span><br>
          <?= e(EXHIBITION_NEXT['title']) ?>. <?= e(EXHIBITION_NEXT['subtitle']) ?>.
        </p>
      </div>

      <div class="lg:col-span-6 order-1 lg:order-2" data-reveal="clip">
        <div class="media" style="aspect-ratio:4/3">
          <?= picture(EXHIBITION_CURRENT['img'], 'The main room during ' . EXHIBITION_CURRENT['title'], '', false, 'data-parallax="0.05"') ?>
        </div>
        <p class="label-xs text-muted mt-3">The main room, F-7 Markaz</p>
      </div>
    </div>
  </section>

  <!-- ================================================ selected works -->
  <section class="bg-paper py-20 sm:py-28">
    <div class="shell">
      <?php $sh = [
          'index' => '02',
          'kicker' => 'Selected works',
          'title' => 'Six to start with',
          'note' => 'Prices run from ' . money_short($range[0]) . ' to ' . money_short($range[1]) . '. Every work can be seen in person before you decide anything.',
          'link' => 'gallery.php',
          'link_label' => 'All ' . count(all_artworks()) . ' works',
      ]; include __DIR__ . '/components/section-head.php'; ?>

      <div class="masonry">
        <?php foreach ($featured as $i => $work): ?>
          <div data-reveal="up" data-reveal-delay="<?= ($i % 3) * 80 ?>">
            <?php $index = $i; $eager = false; include __DIR__ . '/components/artwork-card.php'; ?>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="flex justify-center mt-14">
        <a href="gallery.php" class="btn btn-ghost" data-magnet="7"><span>Browse the whole collection</span></a>
      </div>
    </div>
  </section>

  <!-- =================================================== disciplines -->
  <section class="bg-mist py-20 sm:py-28">
    <div class="shell">
      <?php $sh = [
          'index' => '03',
          'kicker' => 'What we handle',
          'title' => 'Six disciplines, one wall',
      ]; include __DIR__ . '/components/section-head.php'; ?>

      <div class="grid sm:grid-cols-2 gap-x-12" data-reveal-group="60">
        <?php $ci = 0; foreach ($cats as $name => $count): $ci++; ?>
          <a href="gallery.php?category=<?= urlencode($name) ?>"
             class="group flex items-baseline justify-between gap-6 py-5 rule-t" data-reveal="up">
            <span class="flex items-baseline gap-4 min-w-0">
              <span class="label-xs text-muted num shrink-0"><?= str_pad((string) $ci, 2, '0', STR_PAD_LEFT) ?></span>
              <span class="display d-3 group-hover:translate-x-1 transition-transform duration-500"><?= e($name) ?></span>
            </span>
            <span class="label-xs text-muted num shrink-0"><?= str_pad((string) $count, 2, '0', STR_PAD_LEFT) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
      <div class="rule-t"></div>
    </div>
  </section>

  <!-- ======================================================= artists -->
  <section class="bg-paper py-20 sm:py-28">
    <div class="shell">
      <?php $sh = [
          'index' => '04',
          'kicker' => 'Represented',
          'title' => 'Eight artists, and no more',
          'note' => 'A deliberate ceiling. It means anyone at the desk can answer a real question about any work in the building without going away to check.',
          'link' => 'artists.php',
          'link_label' => 'All artists',
      ]; include __DIR__ . '/components/section-head.php'; ?>

      <div class="grid gap-8">
        <?php foreach (array_slice(all_artists(), 0, 4) as $i => $artist): ?>
          <?php $index = $i; include __DIR__ . '/components/artist-card.php'; ?>
        <?php endforeach; ?>
        <div class="rule-t pt-8">
          <a href="artists.php" class="btn btn-ghost" data-magnet="7"><span>Meet the other four</span></a>
        </div>
      </div>
    </div>
  </section>

  <!-- ======================================================= services -->
  <section class="bg-sand py-20 sm:py-28">
    <div class="shell">
      <?php $sh = [
          'index' => '05',
          'kicker' => 'What you get',
          'title' => 'Everything leaves the building properly',
          'note' => 'Framing is a workshop at the back of the gallery, not an upsell. Anything you buy arrives with a condition record you could hand to an insurer.',
      ]; include __DIR__ . '/components/section-head.php'; ?>

      <div class="grid sm:grid-cols-2 gap-x-12 gap-y-9" data-reveal-group="80">
        <?php foreach (SERVICES as $service): ?>
          <div class="pt-6 rule-t" data-reveal="up">
            <div class="flex items-baseline gap-4">
              <span class="label-xs text-muted num"><?= e($service['index']) ?></span>
              <h3 class="display d-3"><?= e($service['title']) ?></h3>
            </div>
            <p class="text-sm text-char/75 leading-relaxed mt-3 max-w-[46ch]"><?= e($service['body']) ?></p>
            <p class="label-xs text-muted mt-3"><?= e($service['meta']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ================================================== testimonials -->
  <section class="bg-mist py-20 sm:py-28">
    <div class="shell">
      <?php $sh = [
          'index' => '06',
          'kicker' => 'From the visitors book',
          'title' => 'What people say afterwards',
      ]; include __DIR__ . '/components/section-head.php'; ?>

      <div class="grid md:grid-cols-3 gap-8" data-reveal-group="90">
        <?php foreach (array_slice(TESTIMONIALS, 0, 3) as $t): ?>
          <figure class="bg-paper p-7 sm:p-8" data-reveal="up">
            <blockquote class="ital d-3 leading-snug">“<?= e($t['quote']) ?>”</blockquote>
            <figcaption class="mt-6 pt-5 rule-t">
              <p class="text-sm font-semibold"><?= e($t['name']) ?></p>
              <p class="label-xs text-muted mt-1"><?= e($t['role']) ?></p>
            </figcaption>
          </figure>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- =========================================================== faq -->
  <section class="bg-paper py-20 sm:py-28">
    <div class="shell grid lg:grid-cols-12 gap-10 lg:gap-16">
      <div class="lg:col-span-4">
        <p class="label-xs text-muted flex items-center gap-3 mb-5" data-reveal="right">
          <span class="num">07</span><span class="w-7 h-px bg-line"></span>Practical
        </p>
        <h2 class="display d-1" data-split="lines" data-reveal="fade">Questions we get asked</h2>
        <p class="lede mt-5 max-w-[34ch]" data-reveal="up" data-reveal-delay="140">
          Anything not here, message us. Someone at the front desk answers, usually within the hour.
        </p>
        <a href="<?= e(wa_link()) ?>" target="_blank" rel="noopener" class="btn btn-ghost mt-7"><span>Ask on WhatsApp</span></a>
      </div>

      <div class="lg:col-span-8" data-accordion>
        <?php foreach (FAQ as $i => $item): ?>
          <div class="acc-item" data-reveal="up" data-reveal-delay="<?= $i * 50 ?>">
            <button type="button" class="acc-btn" aria-expanded="false">
              <span class="label-xs text-muted num shrink-0"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
              <span class="display d-3 flex-1"><?= e($item['q']) ?></span>
              <span class="acc-sign" aria-hidden="true"></span>
            </button>
            <div class="acc-panel">
              <div><p class="prose-note text-sm pb-6 sm:pl-[3.2rem] max-w-[62ch]"><?= e($item['a']) ?></p></div>
            </div>
          </div>
        <?php endforeach; ?>
        <div class="rule-t"></div>
      </div>
    </div>
  </section>

  <!-- =========================================================== cta -->
  <section class="bg-char text-mist py-20 sm:py-28">
    <div class="shell text-center">
      <p class="label-xs text-white/45 mb-6" data-reveal="fade">The door is on Kohsar Block</p>
      <h2 class="display d-1 text-white max-w-[16ch] mx-auto" data-split="lines" data-reveal="fade">Come and look properly.</h2>
      <p class="text-mist/70 leading-relaxed mt-6 max-w-[44ch] mx-auto" data-reveal="up" data-reveal-delay="180">
        Ninety minutes, the room to yourself, anything in storage brought up on request.
        No obligation to buy at the end of it.
      </p>
      <div class="flex flex-wrap justify-center gap-3 mt-9" data-reveal="up" data-reveal-delay="260">
        <a href="booking.php" class="btn btn-invert" data-magnet="8"><span>Book a viewing</span></a>
        <a href="<?= e(wa_link()) ?>" target="_blank" rel="noopener" class="btn btn-invert-ghost" data-magnet="8"><span>Message the gallery</span></a>
      </div>
    </div>
  </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
