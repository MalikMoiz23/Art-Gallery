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
    'tone' => 'dark',
], $sh ?? []);

$muted = $sh['tone'] === 'light' ? 'text-ink-600' : 'text-paper-500';
$body  = $sh['tone'] === 'light' ? 'text-ink-700' : '';
?>
<div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-12 sm:mb-16">
  <div class="max-w-[46ch]">
    <?php if ($sh['kicker'] !== ''): ?>
      <p class="label-xs text-brass-600 flex items-center gap-3 mb-5" data-reveal="right">
        <?php if ($sh['index'] !== ''): ?><span class="num"><?= e($sh['index']) ?></span><span class="w-8 h-px bg-brass-600/50"></span><?php endif; ?>
        <?= e($sh['kicker']) ?>
      </p>
    <?php endif; ?>

    <h2 class="display d-1" data-split="lines" data-reveal="fade"><?= e($sh['title']) ?></h2>

    <?php if ($sh['note'] !== ''): ?>
      <p class="lede mt-6 <?= e($body) ?>" data-reveal="up" data-reveal-delay="120"><?= e($sh['note']) ?></p>
    <?php endif; ?>
  </div>

  <?php if ($sh['link'] !== ''): ?>
    <a href="<?= e($sh['link']) ?>" class="group flex items-center gap-3 shrink-0 <?= e($muted) ?> hover:text-brass-500 transition-colors" data-reveal="left" data-magnet="6">
      <span class="label-xs"><?= e($sh['link_label']) ?></span>
      <span class="w-10 h-10 rounded-full border border-current grid place-items-center group-hover:rotate-45 transition-transform duration-700" aria-hidden="true">→</span>
    </a>
  <?php endif; ?>
</div>
