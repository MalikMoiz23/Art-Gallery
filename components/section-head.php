<?php
/**
 * Section header. Expects $sh = ['index','kicker','title','note','link','link_label'].
 */

$sh = array_merge([
    'index' => '',
    'kicker' => '',
    'title' => '',
    'note' => '',
    'link' => '',
    'link_label' => '',
], $sh ?? []);
?>
<div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 lg:gap-12 mb-12 sm:mb-16">
  <div class="max-w-[44ch]">
    <?php if ($sh['kicker'] !== ''): ?>
      <p class="label-xs text-muted flex items-center gap-3 mb-5" data-reveal="right">
        <?php if ($sh['index'] !== ''): ?><span class="num"><?= e($sh['index']) ?></span><span class="w-7 h-px bg-line"></span><?php endif; ?>
        <?= e($sh['kicker']) ?>
      </p>
    <?php endif; ?>

    <h2 class="display d-1" data-split="lines" data-reveal="fade"><?= e($sh['title']) ?></h2>

    <?php if ($sh['note'] !== ''): ?>
      <p class="lede mt-5" data-reveal="up" data-reveal-delay="120"><?= e($sh['note']) ?></p>
    <?php endif; ?>
  </div>

  <?php if ($sh['link'] !== ''): ?>
    <a href="<?= e($sh['link']) ?>" class="group inline-flex items-center gap-3 shrink-0 self-start lg:self-auto" data-reveal="left">
      <span class="label-xs"><?= e($sh['link_label']) ?></span>
      <span class="w-9 h-9 rounded-full border border-line grid place-items-center transition-colors duration-500 group-hover:bg-char group-hover:text-white group-hover:border-char" aria-hidden="true">→</span>
    </a>
  <?php endif; ?>
</div>
