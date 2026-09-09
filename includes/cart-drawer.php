<div class="drawer-scrim" aria-hidden="true"></div>

<aside class="drawer" aria-hidden="true" aria-label="Enquiry list">

  <div class="flex items-center justify-between gap-4 px-6 py-5 rule-b">
    <div>
      <p class="display d-3 leading-none">Your enquiry</p>
      <p class="label-xs text-muted mt-2" data-cart-count-text>Empty</p>
    </div>
    <button type="button" class="icon-btn" data-drawer-close aria-label="Close enquiry list">
      <svg width="15" height="15" viewBox="0 0 15 15" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><path d="M2 2l11 11M13 2L2 13"/></svg>
    </button>
  </div>

  <div class="flex-1 overflow-y-auto px-6" data-drawer-body></div>

  <div class="px-6 py-5 rule-t bg-paper" data-cart-filled-toggle hidden>
    <div class="flex items-baseline justify-between gap-4">
      <span class="label-xs text-muted">Indicative total</span>
      <span class="num display d-3 text-ink" data-cart-total>₨ 0</span>
    </div>
    <p class="text-xs text-muted mt-2 leading-relaxed">Nothing is charged here. Send the list and we reply with availability, framing and delivery.</p>
    <div class="grid gap-2.5 mt-5">
      <a class="btn" href="<?= e(wa_link()) ?>" target="_blank" rel="noopener" data-wa-enquiry>
        <span>
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.46 1.32 4.96L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22c5.46 0 9.91-4.45 9.91-9.91C21.96 6.45 17.5 2 12.04 2Zm5.8 14.03c-.24.68-1.4 1.3-1.93 1.35-.53.05-1.03.24-3.47-.72-2.94-1.16-4.77-4.2-4.91-4.4-.14-.2-1.15-1.53-1.15-2.92 0-1.39.73-2.07 1-2.36.26-.29.57-.36.76-.36h.55c.18.01.41-.07.64.49.24.58.79 1.97.86 2.11.07.15.12.32.02.51-.09.2-.19.32-.38.5-.19.19-.29.29-.41.48-.12.19-.05.36.05.55.1.19.55.96 1.19 1.55.81.76 1.5 1.01 1.71 1.11.2.1.32.08.44-.05.12-.14.53-.62.67-.83.14-.22.29-.17.48-.1.19.07 1.22.58 1.43.68.21.1.35.15.4.24.05.09.05.53-.19 1.21Z"/></svg>
          Send on WhatsApp
        </span>
      </a>
      <div class="grid grid-cols-2 gap-2.5">
        <a href="cart.php" class="btn btn-ghost flex-1"><span>Review list</span></a>
        <button type="button" class="btn btn-ghost" data-cart-clear><span>Clear</span></button>
      </div>
    </div>
  </div>
</aside>
