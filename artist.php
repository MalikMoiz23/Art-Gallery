<?php
require_once __DIR__ . '/config.php';

$artist = find_artist(query('slug'));

if (!$artist) {
    http_response_code(404);
    $missing = 'artist';
    include __DIR__ . '/404.php';
    exit;
}

$works     = works_by_artist($artist['slug']);
$available = array_values(array_filter($works, fn ($w) => $w['status'] === 'available'));
$others    = array_values(array_filter(all_artists(), fn ($a) => $a['slug'] !== $artist['slug']));

$page_title = $artist['name'];
$page_desc  = $artist['name'] . ' — ' . $artist['discipline'] . ', ' . $artist['city'] . '. ' . $artist['statement'];

include __DIR__ . '/includes/head.php';
?>

<main id="main">

  <!-- ============================================================ hero -->
  <section class="relative overflow-hidden" style="padding-top:calc(var(--header-h) + 4rem)">
    <div class="absolute inset-0 -z-10 opacity-[0.22]" aria-hidden="true"
         style="background:radial-gradient(70% 55% at 22% 0%, <?= e($artist['accent']) ?>, transparent 62%)"></div>

    <div class="shell pb-16">
      <nav class="label-xs text-paper-500 flex items-center gap-2 mb-10" aria-label="Breadcrumb">
        <a href="index.php" class="ul-draw">Home</a><span class="text-brass-500">/</span>
        <a href="artists.php" class="ul-draw">Artists</a><span class="text-brass-500">/</span>
        <span class="text-paper-300"><?= e($artist['name']) ?></span>
      </nav>

      <div class="grid lg:grid-cols-12 gap-10 items-end">
        <div class="lg:col-span-8">
          <p class="label-xs text-brass-500 mb-6" data-reveal="right"><?= e($artist['discipline']) ?></p>
          <h1 class="display d-hero" data-split="lines" data-reveal="fade"><?= e($artist['name']) ?></h1>
          <p class="ital d-2 mt-8 max-w-[34ch] text-paper-200" data-reveal="up" data-reveal-delay="260">“<?= e($artist['statement']) ?>”</p>
        </div>

        <div class="lg:col-span-4 flex lg:justify-end" data-reveal="scale" data-reveal-delay="200">
          <div class="relative w-40 h-40 sm:w-52 sm:h-52 overflow-hidden shrink-0"
               style="background:linear-gradient(150deg, <?= e($artist['accent']) ?> 0%, #12100e 88%)">
            <span class="absolute inset-0 grid place-items-center display leading-none text-[4.5rem] sm:text-[6rem] text-paper-50/90"><?= e($artist['initials']) ?></span>
            <span class="absolute inset-0" style="background:radial-gradient(120% 90% at 20% 8%, rgb(248 245 239 / .2), transparent 58%)"></span>
          </div>
        </div>
      </div>

      <dl class="grid grid-cols-2 sm:grid-cols-4 gap-6 mt-14 pt-8 rule-t" data-reveal-group="80">
        <div data-reveal="up">
          <dt class="label-xs text-paper-500 mb-2">Based in</dt>
          <dd class="display d-4"><?= e($artist['city']) ?></dd>
        </div>
        <div data-reveal="up">
          <dt class="label-xs text-paper-500 mb-2">Born</dt>
          <dd class="display d-4 num"><?= (int) $artist['born'] ?></dd>
        </div>
        <div data-reveal="up">
          <dt class="label-xs text-paper-500 mb-2">With us since</dt>
          <dd class="display d-4 num"><?= (int) $artist['represented'] ?></dd>
        </div>
        <div data-reveal="up">
          <dt class="label-xs text-paper-500 mb-2">Works held</dt>
          <dd class="display d-4 num"><?= count($works) ?> <span class="text-sm text-paper-500">(<?= count($available) ?> available)</span></dd>
        </div>
      </dl>
    </div>
  </section>

  <!-- ============================================================ bio -->
  <section class="shell py-20 sm:py-28">
    <div class="grid lg:grid-cols-12 gap-10 lg:gap-16">
      <div class="lg:col-span-7">
        <p class="label-xs text-brass-600 mb-7" data-reveal="right">Practice</p>
        <?php foreach ($artist['bio'] as $i => $para): ?>
          <p class="<?= $i === 0 ? 'lede' : 'prose-note mt-6' ?>" data-reveal="up" data-reveal-delay="<?= $i * 120 ?>"><?= e($para) ?></p>
        <?php endforeach; ?>

        <figure class="mt-12 pt-8 rule-t" data-reveal="up">
          <blockquote class="ital d-3 max-w-[40ch]">“<?= e($artist['press'][0]) ?>”</blockquote>
          <figcaption class="label-xs text-brass-500 mt-4">— <?= e($artist['press'][1]) ?></figcaption>
        </figure>
      </div>

      <div class="lg:col-span-5">
        <p class="label-xs text-brass-600 mb-7" data-reveal="right">Selected exhibitions</p>
        <ol class="grid" data-reveal-group="80">
          <?php foreach ($artist['exhibitions'] as $show): ?>
            <li class="flex gap-6 py-5 rule-t" data-reveal="up">
              <span class="label-xs text-brass-500 num shrink-0 pt-1"><?= (int) $show[0] ?></span>
              <span>
                <span class="display d-4 block"><?= e($show[1]) ?></span>
                <span class="label-xs text-paper-500 mt-1.5 block"><?= e($show[2]) ?></span>
              </span>
            </li>
          <?php endforeach; ?>
          <li class="rule-t"></li>
        </ol>

        <div class="mt-9" data-reveal="up">
          <a href="<?= e(wa_link('Hello Nuqta, I would like to know more about ' . $artist['name'] . '\'s work.')) ?>"
             target="_blank" rel="noopener" class="btn btn-ghost w-full" data-no-veil><span>Ask about this artist</span></a>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================================================== works -->
  <section class="shell pb-24 sm:pb-32">
    <?php $sh = [
        'index' => '·',
        'kicker' => 'In the building',
        'title' => count($works) . ' works by ' . $artist['name'],
        'note' => 'Everything held here, including works already collected. Available pieces can be brought up from storage for a private viewing.',
        'link' => 'booking.php',
        'link_label' => 'Book a viewing',
    ]; include __DIR__ . '/components/section-head.php'; ?>

    <div class="masonry">
      <?php foreach ($works as $i => $work): ?>
        <div data-reveal="up" data-reveal-delay="<?= ($i % 3) * 80 ?>">
          <?php $index = $i; $eager = $i === 0; include __DIR__ . '/components/artwork-card.php'; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ======================================================== next up -->
  <section class="shell pb-24 sm:pb-32">
    <p class="label-xs text-paper-500 mb-8" data-reveal="right">Other artists</p>
    <div class="flex flex-wrap gap-x-3 gap-y-4" data-reveal-group="60">
      <?php foreach ($others as $other): ?>
        <a href="artist.php?slug=<?= e($other['slug']) ?>"
           class="group flex items-center gap-3 pr-5 py-2 rule-t flex-1 min-w-[240px]" data-reveal="up" data-cursor="tight">
          <span class="w-10 h-10 shrink-0 grid place-items-center display text-sm"
                style="background:linear-gradient(150deg, <?= e($other['accent']) ?>, #12100e)"><?= e($other['initials']) ?></span>
          <span class="min-w-0">
            <span class="block display d-4 truncate group-hover:text-brass-500 transition-colors"><?= e($other['name']) ?></span>
            <span class="block label-xs text-paper-500 mt-0.5 truncate"><?= e($other['discipline']) ?></span>
          </span>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
