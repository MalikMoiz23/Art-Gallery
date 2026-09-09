<?php
require_once __DIR__ . '/config.php';

$page_title = 'Your enquiry list';
$page_desc  = 'The works you are holding. Nothing is charged here — send the list and the gallery replies with availability, framing and delivery.';

include __DIR__ . '/includes/head.php';
?>

<main id="main">

  <section class="shell pb-12" style="padding-top:calc(var(--header-h) + 5rem)">
    <p class="label-xs text-muted flex items-center gap-3 mb-6" data-reveal="right">
      <span class="num">05</span><span class="w-8 h-px bg-line"></span>Enquiry list
    </p>

    <div class="grid lg:grid-cols-12 gap-8 items-end">
      <h1 class="display d-hero lg:col-span-7" data-split="lines" data-reveal="fade">What you are holding</h1>
      <p class="lede lg:col-span-5 max-w-[42ch]" data-reveal="up" data-reveal-delay="240">
        This is not a checkout. There is no card field and no total to pay. Send us the list and a person
        replies with availability, condition, framing and what delivery would cost.
      </p>
    </div>
  </section>

  <section class="shell pb-24 sm:pb-32">
    <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-start">

      <div class="lg:col-span-7">
        <div class="flex items-baseline justify-between gap-4 pb-4 rule-b">
          <span class="label-xs text-muted" data-cart-count-text>Empty</span>
          <button type="button" class="label-xs text-muted hover:text-ink transition-colors" data-cart-clear data-cart-filled-toggle hidden>Clear the list</button>
        </div>

        <!-- Rendered from localStorage by app.js. -->
        <div data-cart-page></div>

        <div data-cart-empty-toggle class="py-20 text-center">
          <p class="display d-1">Nothing held yet</p>
          <p class="prose-note mt-5 max-w-[40ch] mx-auto">
            Add a work from the collection and it will wait here. Unique pieces can be held for seven days
            at no cost while you decide.
          </p>
          <div class="flex flex-wrap justify-center gap-3 mt-9">
            <a href="gallery.php" class="btn" data-magnet="8"><span>Browse the collection</span></a>
            <a href="artists.php" class="btn btn-ghost" data-magnet="8"><span>Start with the artists</span></a>
          </div>
        </div>
      </div>

      <aside class="lg:col-span-5 lg:sticky" style="top:calc(var(--header-h) + 1.5rem)" data-cart-filled-toggle hidden>
        <div class="rule-t rule-b bg-paper p-7 sm:p-8">
          <div class="flex items-baseline justify-between gap-4">
            <span class="label-xs text-muted">Indicative total</span>
            <span class="display d-2 num text-ink" data-cart-total>₨ 0</span>
          </div>
          <p class="prose-note text-sm mt-4">
            Framing is included on works listed as framed. Delivery within Islamabad and Rawalpindi is free;
            anywhere else in Pakistan is quoted before you commit.
          </p>

          <div class="grid gap-2.5 mt-8">
            <a class="btn w-full" href="<?= e(wa_link()) ?>" target="_blank" rel="noopener" data-wa-enquiry>
              <span>Send the list on WhatsApp</span>
            </a>
            <a href="booking.php" class="btn btn-ghost w-full"><span>Book a viewing instead</span></a>
            <a href="mailto:<?= e(EMAIL) ?>?subject=Enquiry%20from%20the%20website" class="btn btn-ghost w-full"><span>Email the gallery</span></a>
          </div>

          <dl class="mt-8 pt-6 rule-t grid gap-4">
            <div class="flex gap-4">
              <dt class="label-xs text-ink num shrink-0">01</dt>
              <dd class="text-sm prose-note">You send the list. Nothing is charged and nothing is committed.</dd>
            </div>
            <div class="flex gap-4">
              <dt class="label-xs text-ink num shrink-0">02</dt>
              <dd class="text-sm prose-note">We confirm availability and put a seven-day hold on anything unique.</dd>
            </div>
            <div class="flex gap-4">
              <dt class="label-xs text-ink num shrink-0">03</dt>
              <dd class="text-sm prose-note">You come and stand in front of it, or we send condition photographs.</dd>
            </div>
            <div class="flex gap-4">
              <dt class="label-xs text-ink num shrink-0">04</dt>
              <dd class="text-sm prose-note">Payment happens in the gallery or by transfer, in full or across three to six months.</dd>
            </div>
          </dl>
        </div>

        <p class="label-xs text-muted mt-5 leading-relaxed">
          The list lives in this browser only. It is not sent anywhere until you send it.
        </p>
      </aside>
    </div>
  </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
