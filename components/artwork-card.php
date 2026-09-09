<?php
/**
 * Collection card.
 * Expects: $work. Optional: $index (for the default sort), $eager (first row).
 */

$m       = media($work['img']);
$status  = status_meta($work['status']);
$artist_label = artist_name($work['artist']);
$index   = $index ?? 0;
$eager   = $eager ?? false;
$sold    = $work['status'] === 'sold';

$haystack = strtolower(implode(' ', [
    $work['title'], $artist_label, $work['category'], $work['medium'],
    (string) $work['year'], implode(' ', $work['tags']),
]));
?>
<article class="group relative glow"
         data-card
         data-index="<?= (int) $index ?>"
         data-category="<?= e($work['category']) ?>"
         data-status="<?= e($work['status']) ?>"
         data-price="<?= (int) $work['price'] ?>"
         data-year="<?= (int) $work['year'] ?>"
         data-title="<?= e($work['title']) ?>"
         data-haystack="<?= e($haystack) ?>">

  <a href="artwork.php?slug=<?= e($work['slug']) ?>" class="block">
    <div class="frame" data-tilt="3.5">
      <div class="tilt-inner">
        <div class="frame-mat">
          <div class="media media-zoom" style="aspect-ratio:<?= (int) $m['w'] ?>/<?= (int) $m['h'] ?>">
            <?= picture($work['img'], $work['title'] . ' by ' . $artist_label, '', $eager) ?>
          </div>
        </div>
      </div>
    </div>
  </a>

  <div class="flex items-start justify-between gap-4 mt-5">
    <div class="min-w-0">
      <a href="artwork.php?slug=<?= e($work['slug']) ?>" class="display d-4 block leading-tight group-hover:text-muted transition-colors duration-500">
        <?= e($work['title']) ?>
      </a>
      <a href="artist.php?slug=<?= e($work['artist']) ?>" class="text-sm text-muted hover:text-ink transition-colors mt-1 inline-block">
        <?= e($artist_label) ?>
      </a>
      <p class="label-xs text-muted mt-2"><?= e($work['category']) ?> · <?= (int) $work['year'] ?></p>
    </div>

    <div class="text-right shrink-0">
      <p class="num text-sm <?= $sold ? 'text-muted line-through' : 'text-ink' ?>"><?= e(money_short($work['price'])) ?></p>
      <span class="chip chip-<?= e($status['tone']) ?> mt-2"><span class="chip-dot"></span><?= e($status['label']) ?></span>
    </div>
  </div>

  <?php if (!$sold): ?>
    <button type="button"
            class="btn btn-ghost btn-sm w-full mt-4 opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 focus-visible:opacity-100 focus-visible:translate-y-0 transition-all duration-500"
            data-add
            data-label="Add to enquiry"
            data-slug="<?= e($work['slug']) ?>"
            data-title="<?= e($work['title']) ?>"
            data-artist="<?= e($artist_label) ?>"
            data-price="<?= (int) $work['price'] ?>"
            data-thumb="<?= e($work['img']) ?>"
            data-edition="<?= e($work['edition']) ?>"
            data-unique="<?= str_starts_with($work['edition'], 'Edition') ? '0' : '1' ?>">
      <span data-add-label>Add to enquiry</span>
    </button>
  <?php else: ?>
    <p class="label-xs text-muted mt-4 py-2.5 text-center rule-t">In a private collection</p>
  <?php endif; ?>
</article>
