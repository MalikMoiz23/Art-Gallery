<div class="wa-fab">

  <button type="button" class="wa-trigger" aria-expanded="false" aria-label="Open WhatsApp options" data-cursor="tight">
    <svg width="27" height="27" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.46 1.32 4.96L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91C21.96 6.45 17.5 2 12.04 2Zm5.8 14.03c-.24.68-1.4 1.3-1.93 1.35-.53.05-1.03.24-3.47-.72-2.94-1.16-4.77-4.2-4.91-4.4-.14-.2-1.15-1.53-1.15-2.92 0-1.39.73-2.07 1-2.36.26-.29.57-.36.76-.36.19 0 .38 0 .55.01.18.01.41-.07.64.49.24.58.79 1.97.86 2.11.07.15.12.32.02.51-.09.2-.19.32-.38.5-.19.19-.29.29-.41.48-.12.19-.05.36.05.55.1.19.55.96 1.19 1.55.81.76 1.5 1.01 1.71 1.11.2.1.32.08.44-.05.12-.14.53-.62.67-.83.14-.22.29-.17.48-.1.19.07 1.22.58 1.43.68.21.1.35.15.4.24.05.09.05.53-.19 1.21Z"/></svg>
  </button>

  <div class="wa-card">
    <div class="flex items-start gap-3">
      <span class="w-9 h-9 shrink-0 rounded-full grid place-items-center bg-[#1f9d5b] text-white">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.46 1.32 4.96L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22c5.46 0 9.91-4.45 9.91-9.91C21.96 6.45 17.5 2 12.04 2Z"/></svg>
      </span>
      <div>
        <p class="text-sm font-semibold leading-tight">Nuqta — front desk</p>
        <p class="text-xs text-paper-500 mt-0.5">Replies Tue–Sat, 11:00–19:00 PKT</p>
      </div>
    </div>

    <p class="text-xs prose-note mt-3.5">Ask about a work, request a condition report, or book a private viewing. A person answers, not a bot.</p>

    <div class="grid gap-1.5 mt-4">
      <?php
      $prompts = [
          'Is a work still available?'        => 'Hello Nuqta, is this work still available? ',
          'Book a private viewing'            => 'Hello Nuqta, I would like to book a private viewing.',
          'Ask about framing and delivery'    => 'Hello Nuqta, I have a question about framing and delivery.',
          'Talk to a collection advisor'      => 'Hello Nuqta, I would like to speak to a collection advisor.',
      ];
      foreach ($prompts as $label => $message): ?>
        <a href="<?= e(wa_link($message)) ?>" target="_blank" rel="noopener" data-no-veil
           class="flex items-center justify-between gap-3 px-3 py-2.5 rule-t text-xs text-paper-300 hover:text-brass-500 hover:bg-ink-800 transition-colors">
          <span><?= e($label) ?></span>
          <span aria-hidden="true">→</span>
        </a>
      <?php endforeach; ?>
    </div>

    <p class="label-xs text-paper-500 mt-4 num"><?= e(PHONE_DISPLAY) ?></p>
  </div>
</div>
