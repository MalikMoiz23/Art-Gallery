<?php
require_once __DIR__ . '/config.php';

$page_title = 'Contemporary art gallery in Islamabad';
$page_desc  = 'Nuqta represents eight Pakistani artists across painting, miniature, calligraphy, print, textile and sculpture. Currently showing Warm Ground by Sana Farooqi.';

$featured  = featured_works(7);
$hero_work = find_artwork('nightfall-ravi');
$hero_side = [find_artwork('the-listener'), find_artwork('crane-ascending')];
$cats      = categories();
$range     = price_range();

include __DIR__ . '/includes/head.php';
?>

<main id="main">

  <!-- ============================================================ hero -->
  <section class="relative min-h-[100svh] flex flex-col justify-end overflow-hidden" style="padding-top:calc(var(--header-h) + 3rem)">

    <div class="absolute inset-0 -z-10" aria-hidden="true">
      <div class="media absolute inset-[-14%]" data-parallax="0.1">
        <?= picture(EXHIBITION_CURRENT['img'], '', '', true) ?>
      </div>
      <div class="absolute inset-0 hero-scrim"></div>
    </div>

    <div class="shell pb-10 sm:pb-14">
      <div class="grid lg:grid-cols-12 gap-x-10 gap-y-14 items-end">

        <div class="lg:col-span-7">
          <a href="gallery.php" class="inline-flex items-center gap-3 mb-8 group" data-reveal="right">
            <span class="relative flex w-2 h-2">
              <span class="absolute inset-0 rounded-full bg-brass-500 animate-pulse-ring"></span>
              <span class="relative w-2 h-2 rounded-full bg-brass-500"></span>
            </span>
            <span class="label-xs text-paper-300 group-hover:text-brass-500 transition-colors">
              Now showing — <?= e(EXHIBITION_CURRENT['title']) ?>
            </span>
            <span class="label-xs text-paper-500 num">until <?= e(EXHIBITION_CURRENT['to']) ?></span>
          </a>

          <h1 class="display d-hero" data-split="chars" data-reveal="fade">Stand closer.</h1>

          <p class="lede mt-8 max-w-[48ch]" data-reveal="up" data-reveal-delay="420">
            Eight artists. <?= count(all_artworks()) ?> works on the wall this season. A room on Kohsar Block where nobody hurries you,
            and nobody mentions a price until you ask.
          </p>

          <div class="flex flex-wrap items-center gap-3 mt-10" data-reveal="up" data-reveal-delay="540">
            <a href="gallery.php" class="btn" data-magnet="8"><span>See the collection</span></a>
            <a href="booking.php" class="btn btn-ghost" data-magnet="8"><span>Book a private viewing</span></a>
          </div>

          <dl class="grid grid-cols-2 sm:grid-cols-4 gap-x-6 gap-y-5 mt-14 pt-8 rule-t" data-reveal-group="90">
            <?php foreach (STATS as $stat): ?>
              <div data-reveal="up">
                <dt class="display d-3 text-brass-500 num"><span data-count="<?= (int) $stat['value'] ?>">0</span><?= e($stat['suffix']) ?></dt>
                <dd class="label-xs text-paper-500 mt-2 leading-relaxed"><?= e($stat['label']) ?></dd>
              </div>
            <?php endforeach; ?>
          </dl>
        </div>

        <!-- Hero cluster: one work on the wall, two smaller at different depths. -->
        <div class="lg:col-span-5 relative spot" data-reveal="scale" data-reveal-delay="300">
          <?php if ($hero_work): $hm = media($hero_work['img']); ?>
            <a href="artwork.php?slug=<?= e($hero_work['slug']) ?>" class="block relative z-10 max-w-[420px] mx-auto lg:ml-auto lg:mr-0" data-cursor="View work" data-parallax="0.05">
              <div class="frame" data-tilt="4">
                <div class="tilt-inner">
                  <div class="frame-mat">
                    <div class="media" style="aspect-ratio:<?= (int) $hm['w'] ?>/<?= (int) $hm['h'] ?>">
                      <?= picture($hero_work['img'], $hero_work['title'] . ' by ' . artist_name($hero_work['artist']), '', true) ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="flex items-baseline justify-between gap-4 mt-4">
                <div>
                  <p class="display d-4"><?= e($hero_work['title']) ?></p>
                  <p class="label-xs text-paper-500 mt-1.5"><?= e(artist_name($hero_work['artist'])) ?> · <?= (int) $hero_work['year'] ?></p>
                </div>
                <p class="num text-sm text-brass-500"><?= e(money_short($hero_work['price'])) ?></p>
              </div>
            </a>
          <?php endif; ?>

          <?php foreach ($hero_side as $i => $side): if (!$side) continue; $sm = media($side['img']); ?>
            <a href="artwork.php?slug=<?= e($side['slug']) ?>"
               class="hidden xl:block absolute z-20 <?= $i === 0 ? '-left-3 bottom-12 w-[100px]' : '-left-8 top-32 w-[104px]' ?>"
               data-cursor="tight" data-parallax="<?= $i === 0 ? '0.16' : '-0.12' ?>" aria-label="<?= e($side['title']) ?>">
              <div class="frame frame-sm">
                <div class="frame-mat">
                  <div class="media grade" style="aspect-ratio:<?= (int) $sm['w'] ?>/<?= (int) $sm['h'] ?>">
                    <?= picture($side['img'], $side['title'], '', false) ?>
                  </div>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="shell pb-6 flex items-center gap-4 label-xs text-paper-500" aria-hidden="true">
      <span class="w-px h-10 bg-gradient-to-b from-brass-500/70 to-transparent"></span>
      <span>Scroll</span>
    </div>
  </section>

  <!-- ======================================================== ticker -->
  <div class="rule-t rule-b py-4 marquee-mask bg-ink-900">
    <div class="marquee animate-marquee" data-marquee>
      <span class="flex items-center gap-8 pr-8 label-xs whitespace-nowrap">
        <span class="text-brass-500"><?= e(EXHIBITION_CURRENT['title']) ?></span>
        <span class="text-paper-500"><?= e(EXHIBITION_CURRENT['subtitle']) ?></span>
        <span class="text-paper-500 num"><?= e(EXHIBITION_CURRENT['from']) ?> – <?= e(EXHIBITION_CURRENT['to']) ?></span>
        <span class="text-paper-500"><?= e(EXHIBITION_CURRENT['room']) ?></span>
        <span class="text-brass-500">Free entry</span>
        <span class="text-paper-500">Tue – Sat, 11:00 – 19:00</span>
        <span class="text-brass-500">✳</span>
      </span>
    </div>
  </div>

  <!-- =================================================== exhibition -->
  <section class="shell py-24 sm:py-32">
    <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-center">

      <div class="lg:col-span-6 order-2 lg:order-1">
        <p class="label-xs text-brass-600 flex items-center gap-3 mb-6" data-reveal="right">
          <span class="num">01</span><span class="w-8 h-px bg-brass-600/50"></span>This season
        </p>
        <h2 class="display d-1" data-split="lines" data-reveal="fade"><?= e(EXHIBITION_CURRENT['title']) ?></h2>
        <p class="ital d-3 text-brass-500 mt-4" data-reveal="up" data-reveal-delay="140"><?= e(EXHIBITION_CURRENT['subtitle']) ?></p>
        <p class="lede mt-7 max-w-[50ch]" data-reveal="up" data-reveal-delay="220"><?= e(EXHIBITION_CURRENT['blurb']) ?></p>

        <dl class="grid sm:grid-cols-3 gap-6 mt-10 pt-8 rule-t" data-reveal-group="80">
          <div data-reveal="up">
            <dt class="label-xs text-paper-500 mb-2">Dates</dt>
            <dd class="text-sm num"><?= e(EXHIBITION_CURRENT['from']) ?> – <?= e(EXHIBITION_CURRENT['to']) ?></dd>
          </div>
          <div data-reveal="up">
            <dt class="label-xs text-paper-500 mb-2">Room</dt>
            <dd class="text-sm"><?= e(EXHIBITION_CURRENT['room']) ?></dd>
          </div>
          <div data-reveal="up">
            <dt class="label-xs text-paper-500 mb-2">Entry</dt>
            <dd class="text-sm">Free, no booking</dd>
          </div>
        </dl>

        <div class="flex flex-wrap gap-3 mt-9" data-reveal="up" data-reveal-delay="260">
          <a href="artist.php?slug=sana-farooqi" class="btn btn-ghost"><span>About the artist</span></a>
          <a href="gallery.php?category=Painting" class="btn btn-ghost"><span>See the paintings</span></a>
        </div>
      </div>

      <div class="lg:col-span-6 order-1 lg:order-2" data-reveal="clip">
        <div class="media grade" style="aspect-ratio:4/3">
          <?= picture(EXHIBITION_CURRENT['img'], 'The main room during ' . EXHIBITION_CURRENT['title'], '', false, 'data-parallax="0.06"') ?>
        </div>
        <p class="label-xs text-paper-500 mt-3">The main room, F-7 Markaz</p>
      </div>
    </div>
  </section>

  <!-- ================================================ featured works -->
  <section class="relative" data-pin style="--pin-tail:10vw">
    <div class="pin-vp">
      <div class="w-full">
        <div class="shell mb-10 lg:mb-0 lg:absolute lg:top-20 lg:left-0 lg:right-0 z-10 pointer-events-none">
          <div class="flex items-end justify-between gap-8 pointer-events-auto">
            <div>
              <p class="label-xs text-brass-600 flex items-center gap-3 mb-5">
                <span class="num">02</span><span class="w-8 h-px bg-brass-600/50"></span>Selected works
              </p>
              <h2 class="display d-1 max-w-[16ch]">Seven to start with</h2>
            </div>
            <a href="gallery.php" class="group hidden sm:flex items-center gap-3 text-paper-500 hover:text-brass-500 transition-colors" data-magnet="6">
              <span class="label-xs">All <?= count(all_artworks()) ?> works</span>
              <span class="w-10 h-10 rounded-full border border-current grid place-items-center group-hover:rotate-45 transition-transform duration-700" aria-hidden="true">→</span>
            </a>
          </div>
        </div>

        <div class="pin-track lg:pt-64">
          <?php foreach ($featured as $i => $work): $m = media($work['img']); ?>
            <a href="artwork.php?slug=<?= e($work['slug']) ?>"
               class="group relative w-[74vw] sm:w-[46vw] lg:w-[27vw] xl:w-[23vw] shrink-0"
               data-cursor="View work">
              <div class="frame">
                <div class="frame-mat<?= $i % 3 === 1 ? ' is-dark' : '' ?>">
                  <div class="media media-zoom grade" style="aspect-ratio:<?= (int) $m['w'] ?>/<?= (int) $m['h'] ?>">
                    <?= picture($work['img'], $work['title'] . ' by ' . artist_name($work['artist'])) ?>
                  </div>
                </div>
              </div>
              <div class="flex items-baseline justify-between gap-4 mt-4">
                <div class="min-w-0">
                  <p class="display d-4 group-hover:text-brass-500 transition-colors duration-500"><?= e($work['title']) ?></p>
                  <p class="label-xs text-paper-500 mt-1.5 truncate"><?= e(artist_name($work['artist'])) ?></p>
                </div>
                <p class="num text-sm text-brass-500 shrink-0"><?= e(money_short($work['price'])) ?></p>
              </div>
            </a>
          <?php endforeach; ?>

          <div class="w-[74vw] sm:w-[36vw] lg:w-[22vw] shrink-0 flex items-center">
            <div>
              <p class="display d-2 max-w-[14ch]">And nineteen more on the wall.</p>
              <a href="gallery.php" class="btn mt-7"><span>Browse everything</span></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- =================================================== disciplines -->
  <section class="shell py-24 sm:py-32">
    <?php $sh = [
        'index' => '03',
        'kicker' => 'What we handle',
        'title' => 'Six disciplines, one wall',
        'note' => 'Prices run from ' . money_short($range[0]) . ' to ' . money_short($range[1]) . '. Every work is available to see in person before you decide anything.',
        'link' => 'gallery.php',
        'link_label' => 'Open the collection',
    ]; include __DIR__ . '/components/section-head.php'; ?>

    <div>
      <?php $ci = 0; foreach ($cats as $name => $count):
          $sample = null;
          foreach (all_artworks() as $w) { if ($w['category'] === $name) { $sample = $w; break; } }
          $ci++; ?>
        <a href="gallery.php?category=<?= urlencode($name) ?>"
           class="group relative flex items-baseline justify-between gap-6 py-7 sm:py-9 rule-t overflow-hidden"
           data-reveal="up" data-reveal-delay="<?= $ci * 60 ?>" data-cursor="tight">
          <span class="flex items-baseline gap-5 sm:gap-8 min-w-0">
            <span class="label-xs text-paper-500 num shrink-0"><?= str_pad((string) $ci, 2, '0', STR_PAD_LEFT) ?></span>
            <span class="display d-2 group-hover:text-brass-500 group-hover:translate-x-2 transition-all duration-700"><?= e($name) ?></span>
          </span>

          <span class="flex items-center gap-6 shrink-0">
            <span class="label-xs text-paper-500 num"><?= str_pad((string) $count, 2, '0', STR_PAD_LEFT) ?> works</span>
            <span class="text-paper-500 group-hover:text-brass-500 group-hover:translate-x-1 transition-all duration-500" aria-hidden="true">→</span>
          </span>

          <?php if ($sample): ?>
            <span class="pointer-events-none hidden md:block absolute right-[22%] top-1/2 w-36 lg:w-44 z-10
                         opacity-0 -translate-y-1/2 -rotate-6 scale-90
                         group-hover:opacity-100 group-hover:rotate-2 group-hover:scale-100
                         transition-all duration-700 ease-[cubic-bezier(.16,1,.3,1)]" aria-hidden="true">
              <span class="frame block">
                <span class="frame-mat block">
                  <span class="media block" style="aspect-ratio:4/5">
                    <?= picture($sample['img'], '', '', false) ?>
                  </span>
                </span>
              </span>
            </span>
          <?php endif; ?>
        </a>
      <?php endforeach; ?>
      <div class="rule-t"></div>
    </div>
  </section>

  <!-- ======================================================= artists -->
  <section class="shell pb-24 sm:pb-32">
    <?php $sh = [
        'index' => '04',
        'kicker' => 'Represented',
        'title' => 'Eight people we argue with regularly',
        'note' => 'We represent few artists on purpose. It means we can answer any question you have about any work in the building without going to look it up.',
        'link' => 'artists.php',
        'link_label' => 'All artists',
    ]; include __DIR__ . '/components/section-head.php'; ?>

    <div class="grid gap-10">
      <?php foreach (array_slice(all_artists(), 0, 4) as $i => $artist): ?>
        <?php $index = $i; include __DIR__ . '/components/artist-card.php'; ?>
      <?php endforeach; ?>
      <div class="rule-t pt-8">
        <a href="artists.php" class="btn btn-ghost" data-magnet="7"><span>Meet the other four</span></a>
      </div>
    </div>
  </section>

  <!-- ======================================================= framing -->
  <section class="plaster text-ink-900 py-24 sm:py-32 overflow-hidden">
    <div class="shell grid lg:grid-cols-12 gap-10 lg:gap-16 items-center">
      <div class="lg:col-span-5" data-reveal="wipe" style="--curtain:#F1ECE3">
        <div class="media" style="aspect-ratio:4/5">
          <?= picture(FRAMING_NOTE['img'], 'A framed work on the gallery wall', '', false, 'data-parallax="0.07"') ?>
        </div>
      </div>

      <div class="lg:col-span-7">
        <p class="label-xs text-clay-600 flex items-center gap-3 mb-6" data-reveal="right">
          <span class="num">05</span><span class="w-8 h-px bg-clay-600/40"></span>The workshop
        </p>
        <h2 class="display d-1 text-ink-900" data-split="lines" data-reveal="fade"><?= e(FRAMING_NOTE['title']) ?></h2>
        <p class="text-ink-700 leading-relaxed mt-7 max-w-[54ch]" data-reveal="up" data-reveal-delay="140"><?= e(FRAMING_NOTE['body']) ?></p>

        <div class="grid sm:grid-cols-2 gap-x-10 gap-y-8 mt-12" data-reveal-group="90">
          <?php foreach (SERVICES as $service): ?>
            <div class="pt-6 border-t border-ink-900/15" data-reveal="up">
              <div class="flex items-baseline gap-4">
                <span class="label-xs text-clay-600 num"><?= e($service['index']) ?></span>
                <h3 class="display d-4 text-ink-900"><?= e($service['title']) ?></h3>
              </div>
              <p class="text-sm text-ink-700/85 leading-relaxed mt-3"><?= e($service['body']) ?></p>
              <p class="label-xs text-ink-600/70 mt-3"><?= e($service['meta']) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- ================================================== testimonials -->
  <section class="py-24 sm:py-32 overflow-hidden">
    <div class="shell">
      <?php $sh = [
          'index' => '06',
          'kicker' => 'From the visitors book',
          'title' => 'What people say afterwards',
      ]; include __DIR__ . '/components/section-head.php'; ?>
    </div>

    <div class="rail" data-cursor="Drag">
      <?php foreach (TESTIMONIALS as $i => $t): ?>
        <figure class="w-[80vw] sm:w-[52vw] lg:w-[34vw] shrink-0 p-8 sm:p-10 rule-t rule-b border-l border-l-brass-500/40 bg-ink-850 glow relative"
                data-reveal="up" data-reveal-delay="<?= $i * 90 ?>">
          <span class="display text-brass-500/30 text-6xl leading-none block mb-4" aria-hidden="true">“</span>
          <blockquote class="ital d-3 leading-snug"><?= e($t['quote']) ?></blockquote>
          <figcaption class="mt-7 pt-5 rule-t">
            <p class="text-sm font-semibold"><?= e($t['name']) ?></p>
            <p class="label-xs text-paper-500 mt-1"><?= e($t['role']) ?></p>
          </figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ======================================================== next up -->
  <section class="shell pb-24 sm:pb-32">
    <div class="relative rule-t rule-b py-14 sm:py-20 overflow-hidden" data-reveal="fade">
      <div class="grid lg:grid-cols-12 gap-8 items-center">
        <div class="lg:col-span-7">
          <p class="label-xs text-brass-600 mb-5">Opening <?= e(EXHIBITION_NEXT['from']) ?></p>
          <h2 class="display d-1 max-w-[22ch]" data-split="lines" data-reveal="fade"><?= e(EXHIBITION_NEXT['title']) ?></h2>
          <p class="ital text-brass-500 mt-4 d-4"><?= e(EXHIBITION_NEXT['subtitle']) ?></p>
        </div>
        <div class="lg:col-span-5">
          <p class="prose-note" data-reveal="up" data-reveal-delay="120"><?= e(EXHIBITION_NEXT['blurb']) ?></p>
          <p class="label-xs text-paper-500 mt-6 num"><?= e(EXHIBITION_NEXT['from']) ?> – <?= e(EXHIBITION_NEXT['to']) ?></p>
          <a href="booking.php" class="btn btn-ghost mt-7" data-magnet="7"><span>Ask for an invitation</span></a>
        </div>
      </div>
    </div>
  </section>

  <!-- =========================================================== faq -->
  <section class="shell pb-24 sm:pb-32">
    <div class="grid lg:grid-cols-12 gap-10 lg:gap-16">
      <div class="lg:col-span-4">
        <p class="label-xs text-brass-600 flex items-center gap-3 mb-6" data-reveal="right">
          <span class="num">07</span><span class="w-8 h-px bg-brass-600/50"></span>Practical
        </p>
        <h2 class="display d-1" data-split="lines" data-reveal="fade">Questions we get asked</h2>
        <p class="prose-note text-sm mt-6 max-w-[36ch]" data-reveal="up" data-reveal-delay="140">
          Anything not here, message us. Someone at the front desk will answer, usually within the hour.
        </p>
        <a href="<?= e(wa_link()) ?>" target="_blank" rel="noopener" class="btn btn-ghost mt-7" data-no-veil><span>Ask on WhatsApp</span></a>
      </div>

      <div class="lg:col-span-8" data-accordion>
        <?php foreach (FAQ as $i => $item): ?>
          <div class="acc-item" data-reveal="up" data-reveal-delay="<?= $i * 60 ?>">
            <button type="button" class="acc-btn" aria-expanded="false">
              <span class="label-xs text-paper-500 num shrink-0"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
              <span class="display d-3 flex-1"><?= e($item['q']) ?></span>
              <span class="acc-sign text-brass-500" aria-hidden="true"></span>
            </button>
            <div class="acc-panel">
              <div>
                <p class="prose-note pb-7 pl-0 sm:pl-[3.6rem] max-w-[64ch]"><?= e($item['a']) ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <div class="rule-t"></div>
      </div>
    </div>
  </section>

  <!-- =========================================================== cta -->
  <section class="relative overflow-hidden">
    <div class="shell py-24 sm:py-36 text-center relative spot">
      <p class="label-xs text-brass-600 mb-7" data-reveal="fade">The door is on Kohsar Block</p>
      <h2 class="display d-hero vel-skew" data-split="lines" data-reveal="fade">Come and look properly.</h2>
      <p class="lede mt-8 max-w-[46ch] mx-auto" data-reveal="up" data-reveal-delay="200">
        Ninety minutes, the room to yourself, and anything in storage brought up on request. There is no obligation to buy at the end of it.
      </p>
      <div class="flex flex-wrap justify-center gap-3 mt-11" data-reveal="up" data-reveal-delay="300">
        <a href="booking.php" class="btn" data-magnet="9"><span>Book a viewing</span></a>
        <a href="<?= e(wa_link()) ?>" target="_blank" rel="noopener" class="btn btn-ghost" data-no-veil data-magnet="9"><span>Message the gallery</span></a>
      </div>
      <p class="label-xs text-paper-500 mt-10 num"><?= e(ADDRESS_LINE_1) ?> · <?= e(ADDRESS_LINE_2) ?></p>
    </div>
  </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
