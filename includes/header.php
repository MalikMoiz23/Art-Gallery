<header class="site-header">
  <div class="shell flex items-center justify-between gap-6" style="height:var(--header-h)">

    <a href="index.php" class="flex items-baseline gap-2 shrink-0" aria-label="<?= e(SITE_FULL) ?>, home">
      <span class="display text-[1.55rem] leading-none tracking-[-0.03em]"><?= e(SITE_NAME) ?></span>
      <span class="w-[7px] h-[7px] rounded-full bg-char mb-[3px]"></span>
      <span class="hidden xl:inline label-xs text-muted ml-2 pl-3 rule-l">Est. <?= e((string) SITE_EST) ?> · Islamabad</span>
    </a>

    <nav class="hidden lg:flex items-center gap-9" aria-label="Primary">
      <?php foreach (NAV as $item): ?>
        <a href="<?= e($item['file']) ?>"
           class="group flex items-baseline gap-2 text-sm <?= is_current($item['file']) ? 'text-ink' : 'text-ink' ?>"
           <?= is_current($item['file']) ? 'aria-current="page"' : '' ?>>
          <span class="label-xs text-muted group-hover:text-muted transition-colors"><?= e($item['index']) ?></span>
          <span class="swap font-medium tracking-wide">
            <span><?= e($item['label']) ?></span>
            <span aria-hidden="true"><?= e($item['label']) ?></span>
          </span>
        </a>
      <?php endforeach; ?>
    </nav>

    <div class="flex items-center gap-2 sm:gap-3">
      <a href="<?= e(wa_link()) ?>" target="_blank" rel="noopener" class="icon-btn hidden sm:grid" aria-label="Message us on WhatsApp">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.46 1.32 4.96L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91C21.96 6.45 17.5 2 12.04 2Zm5.8 14.03c-.24.68-1.4 1.3-1.93 1.35-.53.05-1.03.24-3.47-.72-2.94-1.16-4.77-4.2-4.91-4.4-.14-.2-1.15-1.53-1.15-2.92 0-1.39.73-2.07 1-2.36.26-.29.57-.36.76-.36.19 0 .38 0 .55.01.18.01.41-.07.64.49.24.58.79 1.97.86 2.11.07.15.12.32.02.51-.09.2-.19.32-.38.5-.19.19-.29.29-.41.48-.12.19-.05.36.05.55.1.19.55.96 1.19 1.55.81.76 1.5 1.01 1.71 1.11.2.1.32.08.44-.05.12-.14.53-.62.67-.83.14-.22.29-.17.48-.1.19.07 1.22.58 1.43.68.21.1.35.15.4.24.05.09.05.53-.19 1.21Z"/></svg>
      </a>

      <button type="button" class="icon-btn relative" data-drawer-open aria-label="Open enquiry list">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
          <path d="M4 7h16l-1.3 12.2a2 2 0 0 1-2 1.8H7.3a2 2 0 0 1-2-1.8L4 7Z"/>
          <path d="M8.5 7V5.6A3.6 3.6 0 0 1 12 2a3.6 3.6 0 0 1 3.5 3.6V7"/>
        </svg>
        <span class="badge num" data-cart-badge>0</span>
      </button>

      <a href="booking.php" class="btn btn-ghost btn-sm hidden md:inline-flex" data-magnet="6"><span>Book a viewing</span></a>

      <button type="button" class="icon-btn lg:hidden" data-menu-toggle aria-expanded="false" aria-label="Open menu" aria-controls="menu-sheet">
        <span class="grid gap-[5px]">
          <span class="block w-[17px] h-[1px] bg-current"></span>
          <span class="block w-[17px] h-[1px] bg-current"></span>
        </span>
      </button>
    </div>
  </div>
</header>

<div class="menu-sheet" id="menu-sheet">
  <div class="shell flex flex-col justify-between py-8 h-full" style="padding-top:calc(var(--header-h) + 2rem)">
    <nav class="grid gap-1" aria-label="Mobile">
      <?php foreach (NAV as $i => $item): ?>
        <a href="<?= e($item['file']) ?>" class="m-item flex items-baseline gap-4 py-2 rule-b">
          <span class="label-xs text-ink"><?= e($item['index']) ?></span>
          <span class="display d-1"><?= e($item['label']) ?></span>
        </a>
      <?php endforeach; ?>
      <a href="cart.php" class="m-item flex items-baseline gap-4 py-2 rule-b">
        <span class="label-xs text-ink">05</span>
        <span class="display d-1">Enquiry list</span>
      </a>
    </nav>

    <div class="m-item grid sm:grid-cols-3 gap-6 pt-8">
      <div>
        <p class="label-xs text-muted mb-2">Visit</p>
        <p class="text-sm"><?= e(ADDRESS_LINE_1) ?><br><?= e(ADDRESS_LINE_2) ?></p>
      </div>
      <div>
        <p class="label-xs text-muted mb-2">Talk</p>
        <p class="text-sm num"><?= e(PHONE_DISPLAY) ?><br><?= e(EMAIL) ?></p>
      </div>
      <div class="flex sm:justify-end items-start">
        <a href="<?= e(wa_link()) ?>" target="_blank" rel="noopener" class="btn btn-ghost btn-sm"><span>WhatsApp us</span></a>
      </div>
    </div>
  </div>
</div>
