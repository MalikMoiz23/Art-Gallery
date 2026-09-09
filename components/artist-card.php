<?php
/**
 * Artist card. The mark is typographic rather than photographic — the gallery
 * does not publish artist portraits, and a monogram sits better beside work.
 * Expects: $artist. Optional: $index.
 */

$works = works_by_artist($artist['slug']);
$index = $index ?? 0;
$strip = array_slice($works, 0, 3);
?>
<article class="group relative rule-t pt-8" data-reveal="up" data-reveal-delay="<?= (int) $index * 70 ?>">
  <a href="artist.php?slug=<?= e($artist['slug']) ?>" class="grid sm:grid-cols-[auto_minmax(0,1fr)_auto] gap-6 sm:gap-10 items-start max-w-[1000px]">

    <!-- Typographic mark. The gallery publishes no artist portraits. -->
    <div class="relative w-[104px] h-[104px] sm:w-[128px] sm:h-[128px] shrink-0 overflow-hidden"
         style="background:var(--char)">
      <span class="absolute inset-0 grid place-items-center display leading-none text-[2.9rem] sm:text-[3.6rem] text-white/90 group-hover:scale-110 transition-transform duration-[1.1s] ease-[cubic-bezier(.16,1,.3,1)]">
        <?= e($artist['initials']) ?>
      </span>
      <span class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700"
            style="background:radial-gradient(120% 90% at 20% 10%, rgb(255 255 255 / .18), transparent 62%)"></span>
      <span class="absolute bottom-2 right-2.5 label-xs text-white/55 num"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
    </div>

    <div class="min-w-0">
      <h3 class="display d-2 leading-none group-hover:text-muted transition-colors duration-500"><?= e($artist['name']) ?></h3>
      <p class="label-xs text-ink mt-3"><?= e($artist['discipline']) ?></p>
      <p class="prose-note text-sm mt-3 max-w-[52ch] italic"><?= e($artist['statement']) ?></p>
      <p class="label-xs text-muted mt-4">
        <?= e($artist['city']) ?>
        <span class="text-ink mx-2">·</span>
        b. <?= (int) $artist['born'] ?>
        <span class="text-ink mx-2">·</span>
        Represented since <?= (int) $artist['represented'] ?>
      </p>
    </div>

    <div class="flex sm:flex-col gap-2 shrink-0">
      <?php foreach ($strip as $work): $m = media($work['img']); ?>
        <span class="media w-16 sm:w-20" style="aspect-ratio:1/1">
          <?= picture($work['img'], $work['title'], '', false) ?>
        </span>
      <?php endforeach; ?>
      <span class="hidden sm:block label-xs text-muted mt-1 text-center"><?= count($works) ?> works</span>
    </div>
  </a>
</article>
