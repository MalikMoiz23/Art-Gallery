<?php
require_once __DIR__ . '/config.php';

$page_title = 'The collection';
$page_desc  = 'Every work currently held at Nuqta — painting, miniature, calligraphy, print, textile, sculpture and ceramics — with prices, dimensions and availability.';

$works = all_artworks();
$cats  = categories();
$range = price_range();

$statuses = [
    'all'       => 'Everything',
    'available' => 'Available',
    'reserved'  => 'Reserved',
    'sold'      => 'Collected',
];

include __DIR__ . '/includes/head.php';
?>

<main id="main">

  <section class="shell pb-14" style="padding-top:calc(var(--header-h) + 5rem)">
    <p class="label-xs text-muted flex items-center gap-3 mb-6" data-reveal="right">
      <span class="num">01</span><span class="w-8 h-px bg-line"></span>The collection
    </p>

    <div class="grid lg:grid-cols-12 gap-8 items-end">
      <h1 class="display d-hero lg:col-span-7" data-split="lines" data-reveal="fade">Everything on the wall</h1>
      <p class="lede lg:col-span-5 max-w-[44ch]" data-reveal="up" data-reveal-delay="240">
        <?= count($works) ?> works by <?= count(all_artists()) ?> artists, from <?= e(money_short($range[0])) ?> to <?= e(money_short($range[1])) ?>.
        Framing is included wherever a work is listed as framed. Nothing here is a print of something else unless it says so.
      </p>
    </div>
  </section>

  <!-- ======================================================= toolbar -->
  <section class="sticky z-40 bg-paper/90 backdrop-blur-xl rule-t rule-b" style="top:var(--header-h)">
    <div class="shell py-4">
      <div class="flex flex-col gap-4">

        <div class="flex flex-wrap items-center gap-3">
          <div class="relative flex-1 min-w-[200px] max-w-sm">
            <svg class="absolute left-0 top-1/2 -translate-y-1/2 text-muted" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
              <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>
            </svg>
            <input type="search" data-search placeholder="Search title, artist, medium…"
                   aria-label="Search the collection"
                   class="w-full bg-transparent border-0 border-b border-b-[var(--hair-strong)] focus:border-b-char outline-none py-2.5 pl-6 text-sm placeholder:text-muted rounded-none transition-colors">
          </div>

          <div class="flex items-center gap-3 ml-auto">
            <span class="label-xs text-ink num whitespace-nowrap" data-result-count><?= count($works) ?> works</span>
            <label class="label-xs text-muted flex items-center gap-2">
              <span class="hidden sm:inline">Sort</span>
              <select data-sort aria-label="Sort the collection"
                      class="bg-transparent border border-[var(--hair)] rounded-none px-3 py-2 text-xs text-ink outline-none focus:border-char cursor-pointer transition-colors">
                <option value="curated">Curated order</option>
                <option value="price-desc">Price, high to low</option>
                <option value="price-asc">Price, low to high</option>
                <option value="year-desc">Newest first</option>
                <option value="title">Title, A–Z</option>
              </select>
            </label>
          </div>
        </div>

        <div class="flex gap-2.5 overflow-x-auto lg:flex-wrap lg:overflow-visible pb-1 -mb-1" style="scrollbar-width:none">
          <button type="button" class="pill" data-filter="all" data-filter-group="cat" aria-pressed="true">All disciplines</button>
          <?php foreach ($cats as $name => $count): ?>
            <button type="button" class="pill" data-filter="<?= e($name) ?>" data-filter-group="cat" aria-pressed="false">
              <?= e($name) ?> <span class="num opacity-55 ml-1"><?= (int) $count ?></span>
            </button>
          <?php endforeach; ?>

          <span class="w-px bg-[var(--hair)] mx-1 shrink-0"></span>

          <?php foreach ($statuses as $value => $label): ?>
            <button type="button" class="pill" data-filter="<?= e($value) ?>" data-filter-group="status" aria-pressed="<?= $value === 'all' ? 'true' : 'false' ?>">
              <?= e($label) ?>
            </button>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================================================== grid -->
  <section class="shell py-14 sm:py-20">
    <div class="masonry" data-grid>
      <?php foreach ($works as $i => $work): ?>
        <div data-reveal="up" data-reveal-delay="<?= ($i % 4) * 70 ?>">
          <?php $index = $i; $eager = $i < 3; include __DIR__ . '/components/artwork-card.php'; ?>
        </div>
      <?php endforeach; ?>
    </div>

    <div data-grid-empty hidden class="py-24 text-center">
      <p class="display d-1">Nothing matches that</p>
      <p class="prose-note mt-4 max-w-[38ch] mx-auto">Try a different discipline, or clear the search. If you are looking for something specific, ask us — a good deal of the collection is in storage.</p>
      <a href="<?= e(wa_link('Hello Nuqta, I am looking for a particular kind of work. Can you help?')) ?>" target="_blank" rel="noopener" class="btn mt-8"><span>Tell us what you want</span></a>
    </div>
  </section>

  <!-- ========================================================== note -->
  <section class="shell pb-24 sm:pb-32">
    <div class="grid sm:grid-cols-3 gap-8 pt-10 rule-t" data-reveal-group="90">
      <div data-reveal="up">
        <p class="label-xs text-muted mb-3">Prices are prices</p>
        <p class="prose-note text-sm">What you see is the gallery price and it is what you pay. We do not run a two-tier system for people who ask twice.</p>
      </div>
      <div data-reveal="up">
        <p class="label-xs text-muted mb-3">Seven-day hold</p>
        <p class="prose-note text-sm">Any available work can be held for a week at no cost while you think, measure the wall, or argue with someone about it.</p>
      </div>
      <div data-reveal="up">
        <p class="label-xs text-muted mb-3">Fourteen-day return</p>
        <p class="prose-note text-sm">If it does not suit the room, send it back undamaged within fourteen days of delivery for a full refund.</p>
      </div>
    </div>
  </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
