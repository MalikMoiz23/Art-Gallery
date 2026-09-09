<?php
require_once __DIR__ . '/config.php';

$work = find_artwork(query('slug'));

if (!$work) {
    http_response_code(404);
    $missing = 'work';
    include __DIR__ . '/404.php';
    exit;
}

$artist  = find_artist($work['artist']);
$m       = media($work['img']);
$status  = status_meta($work['status']);
$sold    = $work['status'] === 'sold';
$isPrint = str_starts_with($work['edition'], 'Edition');
$related = related_works($work, 4);
$siblings = array_values(array_filter(works_by_artist($work['artist']), fn ($w) => $w['slug'] !== $work['slug']));

// Position in the collection, for the prev/next rail.
$all = all_artworks();
$pos = 0;
foreach ($all as $i => $w) {
    if ($w['slug'] === $work['slug']) { $pos = $i; break; }
}
$prev = $all[($pos - 1 + count($all)) % count($all)];
$next = $all[($pos + 1) % count($all)];

$specs = [
    'Artist'     => $artist['name'] ?? '—',
    'Year'       => (string) $work['year'],
    'Medium'     => $work['medium'],
    'Dimensions' => $work['dimensions'],
    'Edition'    => $work['edition'],
    'Framing'    => $work['framed'] ? 'Framed in our workshop, museum glazing' : 'Unframed — framing quoted on request',
    'Signature'  => $work['signature'],
    'Reference'  => 'NQ-' . str_pad((string) ($pos + 1), 3, '0', STR_PAD_LEFT) . '-' . $work['year'],
];

$page_title = $work['title'] . ' — ' . ($artist['name'] ?? '');
$page_desc  = $work['title'] . ', ' . $work['year'] . '. ' . $work['medium'] . ', ' . $work['dimensions'] . '. ' . strip_tags($work['story']);

$waMessage = 'Hello Nuqta, I am interested in "' . $work['title'] . '" by ' . ($artist['name'] ?? '') .
    ' (' . $work['dimensions'] . ', ' . money($work['price']) . '). Is it available, and could I see it in person?';

include __DIR__ . '/includes/head.php';
?>

<main id="main">

  <nav class="shell pb-8 label-xs text-muted flex items-center gap-2 flex-wrap" style="padding-top:calc(var(--header-h) + 2.5rem)" aria-label="Breadcrumb">
    <a href="index.php" class="ul-draw">Home</a><span class="text-ink">/</span>
    <a href="gallery.php" class="ul-draw">Collection</a><span class="text-ink">/</span>
    <a href="gallery.php?category=<?= urlencode($work['category']) ?>" class="ul-draw"><?= e($work['category']) ?></a><span class="text-ink">/</span>
    <span class="text-muted"><?= e($work['title']) ?></span>
  </nav>

  <section class="shell pb-20">
    <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-start">

      <!-- ------------------------------------------------------ image -->
      <div class="lg:col-span-7 lg:sticky" style="top:calc(var(--header-h) + 1.5rem)">
        <button type="button" class="block w-full text-left group relative spot"
                data-zoom="<?= e(img_src($work['img'])) ?>" aria-label="Enlarge <?= e($work['title']) ?>">
          <div class="frame">
            <div class="frame-mat<?= $work['category'] === 'Sculpture & Ceramics' ? ' is-dark' : '' ?>">
              <div class="media" style="aspect-ratio:<?= (int) $m['w'] ?>/<?= (int) $m['h'] ?>">
                <?= picture($work['img'], $work['title'] . ' by ' . ($artist['name'] ?? ''), '', true) ?>
              </div>
            </div>
          </div>
          <span class="absolute bottom-4 right-4 z-10 label-xs px-3 py-1.5 bg-char/85 backdrop-blur text-white opacity-0 group-hover:opacity-100 transition-opacity duration-500">
            Click to enlarge
          </span>
        </button>

        <div class="flex flex-wrap items-center justify-between gap-4 mt-4 label-xs text-muted">
          <span><?= e($work['dimensions']) ?> · <?= e($work['medium']) ?></span>
          <span class="num">Ref <?= e($specs['Reference']) ?></span>
        </div>
      </div>

      <!-- ---------------------------------------------------- details -->
      <div class="lg:col-span-5">
        <div class="flex items-center gap-3 mb-6" data-reveal="right">
          <span class="chip chip-<?= e($status['tone']) ?>"><span class="chip-dot"></span><?= e($status['label']) ?></span>
          <span class="label-xs text-muted"><?= e($work['category']) ?> · <?= (int) $work['year'] ?></span>
        </div>

        <h1 class="display d-1" data-split="lines" data-reveal="fade"><?= e($work['title']) ?></h1>

        <?php if ($artist): ?>
          <a href="artist.php?slug=<?= e($artist['slug']) ?>" class="inline-flex items-center gap-3 mt-5 group" data-reveal="up" data-reveal-delay="140">
            <span class="w-9 h-9 grid place-items-center display text-sm text-white shrink-0"
                  style="background:var(--char)"><?= e($artist['initials']) ?></span>
            <span>
              <span class="block text-sm group-hover:text-muted transition-colors"><?= e($artist['name']) ?></span>
              <span class="block label-xs text-muted mt-0.5"><?= e($artist['discipline']) ?> · <?= e($artist['city']) ?></span>
            </span>
          </a>
        <?php endif; ?>

        <div class="mt-9 pt-7 rule-t" data-reveal="up" data-reveal-delay="200">
          <div class="flex items-end justify-between gap-4">
            <div>
              <p class="label-xs text-muted mb-2">Price</p>
              <p class="display d-2 num <?= $sold ? 'text-muted line-through' : 'text-ink' ?>"><?= e(money($work['price'])) ?></p>
            </div>
            <p class="label-xs text-muted text-right leading-relaxed max-w-[22ch]">
              <?= $work['framed'] ? 'Framing included' : 'Unframed' ?><br>
              Free delivery in Islamabad
            </p>
          </div>

          <div class="grid gap-2.5 mt-8">
            <?php if (!$sold): ?>
              <button type="button" class="btn w-full"
                      data-add data-add-open
                      data-label="Add to enquiry list"
                      data-slug="<?= e($work['slug']) ?>"
                      data-title="<?= e($work['title']) ?>"
                      data-artist="<?= e($artist['name'] ?? '') ?>"
                      data-price="<?= (int) $work['price'] ?>"
                      data-thumb="<?= e($work['img']) ?>"
                      data-edition="<?= e($work['edition']) ?>"
                      data-unique="<?= $isPrint ? '0' : '1' ?>">
                <span data-add-label>Add to enquiry list</span>
              </button>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <a href="<?= e(wa_link($waMessage)) ?>" target="_blank" rel="noopener" class="btn btn-ghost flex-1">
                  <span>Ask on WhatsApp</span>
                </a>
                <a href="booking.php?work=<?= e($work['slug']) ?>" class="btn btn-ghost flex-1"><span>See it in person</span></a>
              </div>
            <?php else: ?>
              <p class="rule-t rule-b py-5 text-center">
                <span class="display d-4 block">This one is in a private collection</span>
                <span class="prose-note text-sm block mt-2">We can tell you what else by <?= e($artist['name'] ?? 'this artist') ?> is available.</span>
              </p>
              <a href="<?= e(wa_link('Hello Nuqta, "' . $work['title'] . '" is sold — what else by ' . ($artist['name'] ?? '') . ' is available?')) ?>"
                 target="_blank" rel="noopener" class="btn btn-ghost w-full"><span>Ask what else is available</span></a>
            <?php endif; ?>
          </div>

          <p class="label-xs text-muted mt-5 leading-relaxed">
            Adding a work charges nothing. We reply with availability, condition and delivery, usually the same day.
          </p>
        </div>

        <!-- specification table -->
        <dl class="mt-10" data-reveal-group="55">
          <?php foreach ($specs as $key => $value): ?>
            <div class="flex items-baseline justify-between gap-6 py-3.5 rule-t" data-reveal="up">
              <dt class="label-xs text-muted shrink-0"><?= e($key) ?></dt>
              <dd class="text-sm text-right<?= $key === 'Reference' ? ' num' : '' ?>"><?= e($value) ?></dd>
            </div>
          <?php endforeach; ?>
          <div class="rule-t"></div>
        </dl>

        <div class="mt-10" data-reveal="up">
          <p class="label-xs text-muted mb-4">On this work</p>
          <p class="prose-note"><?= e($work['story']) ?></p>
        </div>

        <ul class="flex flex-wrap gap-2 mt-7" data-reveal="up">
          <?php foreach ($work['tags'] as $tag): ?>
            <li><a href="gallery.php" class="chip hover:border-char hover:text-ink transition-colors"><?= e($tag) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>

  <!-- ============================================== artist statement -->
  <?php if ($artist): ?>
    <section class="bg-paper py-20 sm:py-28">
      <div class="shell grid lg:grid-cols-12 gap-10 items-center">
        <div class="lg:col-span-7">
          <p class="label-xs text-muted mb-6" data-reveal="right">The artist, in their words</p>
          <blockquote class="display d-1 text-ink max-w-[26ch]" data-split="lines" data-reveal="fade">“<?= e($artist['statement']) ?>”</blockquote>
          <p class="text-muted mt-8 max-w-[56ch] leading-relaxed" data-reveal="up" data-reveal-delay="140"><?= e($artist['bio'][0]) ?></p>
          <a href="artist.php?slug=<?= e($artist['slug']) ?>" class="btn mt-9" data-reveal="up" data-magnet="7"><span>More on <?= e($artist['name']) ?></span></a>
        </div>

        <?php if ($siblings): ?>
          <div class="lg:col-span-5">
            <p class="label-xs text-muted mb-5">Also available by <?= e($artist['name']) ?></p>
            <div class="grid grid-cols-2 gap-4" data-reveal-group="80">
              <?php foreach (array_slice($siblings, 0, 2) as $sib): $sm = media($sib['img']); ?>
                <a href="artwork.php?slug=<?= e($sib['slug']) ?>" class="group" data-reveal="up">
                  <span class="media block" style="aspect-ratio:<?= (int) $sm['w'] ?>/<?= (int) $sm['h'] ?>">
                    <?= picture($sib['img'], $sib['title']) ?>
                  </span>
                  <span class="block display d-4 text-ink mt-3 group-hover:text-muted transition-colors"><?= e($sib['title']) ?></span>
                  <span class="block label-xs text-muted mt-1 num"><?= e(money_short($sib['price'])) ?></span>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php endif; ?>

  <!-- ======================================================= related -->
  <section class="shell py-20 sm:py-28">
    <?php $sh = ['index' => '·', 'kicker' => 'Nearby on the wall', 'title' => 'You might also stop at these', 'link' => 'gallery.php', 'link_label' => 'All works']; include __DIR__ . '/components/section-head.php'; ?>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10" data-reveal-group="90">
      <?php foreach ($related as $i => $rel): ?>
        <div data-reveal="up">
          <?php $work_backup = $work; $work = $rel; $index = $i; $eager = false; include __DIR__ . '/components/artwork-card.php'; $work = $work_backup; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ===================================================== prev/next -->
  <section class="shell pb-24">
    <div class="grid sm:grid-cols-2 gap-px rule-t rule-b">
      <a href="artwork.php?slug=<?= e($prev['slug']) ?>" class="group flex items-center gap-5 py-8 sm:pr-8">
        <span class="text-ink group-hover:-translate-x-1 transition-transform duration-500" aria-hidden="true">←</span>
        <span>
          <span class="label-xs text-muted block mb-1.5">Previous</span>
          <span class="display d-3 group-hover:text-muted transition-colors"><?= e($prev['title']) ?></span>
        </span>
      </a>
      <a href="artwork.php?slug=<?= e($next['slug']) ?>" class="group flex items-center justify-end gap-5 py-8 sm:pl-8 sm:border-l sm:border-l-[var(--hair)] text-right">
        <span>
          <span class="label-xs text-muted block mb-1.5">Next</span>
          <span class="display d-3 group-hover:text-muted transition-colors"><?= e($next['title']) ?></span>
        </span>
        <span class="text-ink group-hover:translate-x-1 transition-transform duration-500" aria-hidden="true">→</span>
      </a>
    </div>
  </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
