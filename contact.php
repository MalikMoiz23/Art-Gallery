<?php
require_once __DIR__ . '/config.php';

$page_title = 'Contact';
$page_desc  = 'Reach Nuqta in Islamabad — WhatsApp, phone, email, or the door on Kohsar Block, Tuesday to Saturday.';

$subjects = [
    'A work in the collection',
    'Framing and delivery',
    'Collection advisory',
    'Submitting my work',
    'Press and image requests',
    'Something else',
];

include __DIR__ . '/includes/head.php';
?>

<main id="main">

  <section class="shell pb-14" style="padding-top:calc(var(--header-h) + 5rem)">
    <p class="label-xs text-brass-600 flex items-center gap-3 mb-6" data-reveal="right">
      <span class="num">04</span><span class="w-8 h-px bg-brass-600/50"></span>Contact
    </p>

    <div class="grid lg:grid-cols-12 gap-8 items-end">
      <h1 class="display d-hero lg:col-span-7" data-split="lines" data-reveal="fade">Talk to a person</h1>
      <p class="lede lg:col-span-5 max-w-[44ch]" data-reveal="up" data-reveal-delay="240">
        There is no ticketing system and no chatbot. WhatsApp reaches the front desk during opening hours
        and is answered by whoever is standing nearest to it.
      </p>
    </div>
  </section>

  <!-- ==================================================== quick rows -->
  <section class="shell pb-20">
    <div class="grid sm:grid-cols-3 gap-px rule-t rule-b" data-reveal-group="90">
      <a href="<?= e(wa_link()) ?>" target="_blank" rel="noopener" class="group p-7 sm:p-8 sm:border-r sm:border-r-[var(--hair)]" data-reveal="up" data-no-veil data-cursor="tight">
        <p class="label-xs text-brass-600 mb-4">Fastest</p>
        <p class="display d-3 group-hover:text-brass-500 transition-colors">WhatsApp</p>
        <p class="label-xs text-paper-500 mt-3 num"><?= e(PHONE_DISPLAY) ?></p>
        <p class="prose-note text-sm mt-4">Usually answered within the hour, Tuesday to Saturday.</p>
      </a>

      <a href="mailto:<?= e(EMAIL) ?>" class="group p-7 sm:p-8 sm:border-r sm:border-r-[var(--hair)]" data-reveal="up" data-no-veil data-cursor="tight">
        <p class="label-xs text-brass-600 mb-4">For anything on paper</p>
        <p class="display d-3 group-hover:text-brass-500 transition-colors">Email</p>
        <p class="label-xs text-paper-500 mt-3"><?= e(EMAIL) ?></p>
        <p class="prose-note text-sm mt-4">Condition reports, invoices, valuations and submissions.</p>
      </a>

      <a href="<?= e(map_link()) ?>" target="_blank" rel="noopener" class="group p-7 sm:p-8" data-reveal="up" data-no-veil data-cursor="tight">
        <p class="label-xs text-brass-600 mb-4">Best of all</p>
        <p class="display d-3 group-hover:text-brass-500 transition-colors">Come in</p>
        <p class="label-xs text-paper-500 mt-3"><?= e(ADDRESS_LINE_1) ?>, <?= e(ADDRESS_LINE_2) ?></p>
        <p class="prose-note text-sm mt-4">Free entry, no booking, nobody will follow you around.</p>
      </a>
    </div>
  </section>

  <!-- ========================================================= form -->
  <section class="shell pb-24 sm:pb-32">
    <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-start">

      <div class="lg:col-span-7">
        <form data-simple-form novalidate class="rule-t rule-b bg-ink-850 p-6 sm:p-9">
          <h2 class="display d-2 mb-2">Write to us</h2>
          <p class="prose-note text-sm mb-8 max-w-[46ch]">
            Fill this in and it prepares a message for you to send — that way you keep a copy of what you asked.
          </p>

          <div class="grid sm:grid-cols-2 gap-6">
            <div class="field">
              <input type="text" name="name" id="c_name" required placeholder=" " autocomplete="name">
              <label for="c_name">Your name</label>
              <span class="field-error"></span>
            </div>
            <div class="field">
              <input type="email" name="email" id="c_email" required placeholder=" " autocomplete="email" data-validate="email">
              <label for="c_email">Email</label>
              <span class="field-error"></span>
            </div>
            <div class="field sm:col-span-2">
              <select name="subject" id="c_subject" required>
                <option value="">Choose one</option>
                <?php foreach ($subjects as $subject): ?>
                  <option value="<?= e($subject) ?>"><?= e($subject) ?></option>
                <?php endforeach; ?>
              </select>
              <label for="c_subject">What is it about</label>
              <span class="field-error"></span>
            </div>
            <div class="field sm:col-span-2">
              <textarea name="message" id="c_message" required placeholder=" " style="min-height:150px"></textarea>
              <label for="c_message">Your message</label>
              <span class="field-error"></span>
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-4 mt-9 pt-7 rule-t">
            <button type="submit" class="btn" data-magnet="7"><span>Prepare my message</span></button>
            <p class="label-xs text-paper-500">No newsletter sign-up hidden in here.</p>
          </div>
        </form>

        <div data-form-done hidden class="rule-t rule-b bg-ink-850 p-7 sm:p-10">
          <span class="w-12 h-12 grid place-items-center rounded-full bg-brass-500 text-ink-900 mb-7" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m4 12.5 5 5L20 6.5"/></svg>
          </span>
          <h2 class="display d-2">Ready to send</h2>
          <p class="prose-note mt-4 max-w-[44ch]">Choose how it goes out. Either way it reaches the same desk.</p>
          <div class="grid sm:grid-cols-2 gap-3 mt-8">
            <a class="btn" href="<?= e(wa_link()) ?>" target="_blank" rel="noopener" data-wa-form data-no-veil><span>Send on WhatsApp</span></a>
            <a class="btn btn-ghost" href="mailto:<?= e(EMAIL) ?>" data-no-veil><span>Send by email</span></a>
          </div>
        </div>
      </div>

      <aside class="lg:col-span-5 grid gap-10">
        <div class="rule-t pt-7" data-reveal="up">
          <p class="label-xs text-brass-600 mb-5">Opening hours</p>
          <ul class="grid gap-2.5">
            <?php foreach (OPENING_HOURS as $days => $hours): ?>
              <li class="flex justify-between gap-4 pb-2.5 rule-b">
                <span class="text-sm text-paper-300"><?= e($days) ?></span>
                <span class="text-sm num text-paper-400"><?= e($hours) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
          <p class="prose-note text-sm mt-5">Closed on public holidays and for two weeks each August while the walls are repainted.</p>
        </div>

        <div class="rule-t pt-7" data-reveal="up">
          <p class="label-xs text-brass-600 mb-5">Follow the work</p>
          <ul class="grid gap-3">
            <?php foreach (SOCIALS as $social): ?>
              <li class="flex items-baseline justify-between gap-4 pb-2.5 rule-b">
                <a href="<?= e($social['url']) ?>" class="ul-draw text-sm"><?= e($social['label']) ?></a>
                <span class="label-xs text-paper-500"><?= e($social['handle']) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="rule-t pt-7" data-reveal="up">
          <p class="label-xs text-brass-600 mb-5">Press</p>
          <p class="prose-note text-sm">
            High-resolution images, artist statements and installation photographs are available on request.
            Please credit the artist and the gallery.
          </p>
          <a href="mailto:<?= e(EMAIL) ?>?subject=Press%20request" class="btn btn-ghost btn-sm mt-5" data-no-veil><span>Request press images</span></a>
        </div>
      </aside>
    </div>
  </section>

  <!-- ========================================================== faq -->
  <section class="shell pb-24 sm:pb-32">
    <div class="grid lg:grid-cols-12 gap-10 lg:gap-16">
      <div class="lg:col-span-4">
        <h2 class="display d-1" data-split="lines" data-reveal="fade">Before you write</h2>
        <p class="prose-note text-sm mt-6 max-w-[34ch]" data-reveal="up" data-reveal-delay="120">Half of what people email about is already answered here.</p>
      </div>
      <div class="lg:col-span-8" data-accordion>
        <?php foreach (FAQ as $i => $item): ?>
          <div class="acc-item" data-reveal="up" data-reveal-delay="<?= $i * 60 ?>">
            <button type="button" class="acc-btn" aria-expanded="false">
              <span class="label-xs text-paper-500 num shrink-0"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
              <span class="display d-3 flex-1"><?= e($item['q']) ?></span>
              <span class="acc-sign text-brass-500" aria-hidden="true"></span>
            </button>
            <div class="acc-panel"><div><p class="prose-note pb-7 sm:pl-[3.6rem] max-w-[64ch]"><?= e($item['a']) ?></p></div></div>
          </div>
        <?php endforeach; ?>
        <div class="rule-t"></div>
      </div>
    </div>
  </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
