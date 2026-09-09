<?php
require_once __DIR__ . '/config.php';

$page_title = 'Book a viewing';
$page_desc  = 'Book a private viewing, a guided walk-through with a curator, a collection advisory session, or ask for a single work to be brought up from storage.';

$preset = query('work');
$works  = all_artworks();

include __DIR__ . '/includes/head.php';
?>

<main id="main">

  <section class="shell pb-14" style="padding-top:calc(var(--header-h) + 5rem)">
    <p class="label-xs text-muted flex items-center gap-3 mb-6" data-reveal="right">
      <span class="num">03</span><span class="w-8 h-px bg-line"></span>Visit
    </p>

    <div class="grid lg:grid-cols-12 gap-8 items-end">
      <h1 class="display d-hero lg:col-span-7" data-split="lines" data-reveal="fade">Have the room</h1>
      <p class="lede lg:col-span-5 max-w-[44ch]" data-reveal="up" data-reveal-delay="240">
        Tuesday to Saturday the door is simply open, no booking needed. Book only if you want the gallery
        closed to everyone else, a curator in the room, or a work carried up from storage.
      </p>
    </div>
  </section>

  <section class="shell pb-24 sm:pb-32">
    <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-start">

      <!-- ------------------------------------------------------- form -->
      <div class="lg:col-span-7">

        <form data-booking novalidate class="rule-t rule-b bg-paper p-6 sm:p-9">

          <div class="flex items-center gap-3 mb-8">
            <div class="flex items-center gap-2">
              <span class="step-dot is-on"></span><span class="step-dot"></span><span class="step-dot"></span>
            </div>
            <div class="flex items-center gap-4 label-xs">
              <span data-step-label class="text-ink">What</span>
              <span class="text-muted">/</span>
              <span data-step-label class="text-muted">When</span>
              <span class="text-muted">/</span>
              <span data-step-label class="text-muted">Who</span>
            </div>
          </div>

          <!-- step 1 -->
          <fieldset class="step-panel is-on">
            <legend class="display d-3 mb-6">What kind of visit?</legend>

            <div class="seg grid sm:grid-cols-2 gap-3" data-field>
              <?php foreach (BOOKING_TYPES as $i => $type): ?>
                <div>
                  <input type="radio" id="type-<?= e($type['value']) ?>" name="visit_type" value="<?= e($type['value']) ?>"
                         data-label="<?= e($type['label']) ?>" <?= $i === 0 ? 'checked' : '' ?> required>
                  <label for="type-<?= e($type['value']) ?>">
                    <span class="display d-4 block"><?= e($type['label']) ?></span>
                    <span class="label-xs text-muted mt-2 block leading-relaxed"><?= e($type['note']) ?></span>
                  </label>
                </div>
              <?php endforeach; ?>
              <span class="field-error sm:col-span-2"></span>
            </div>

            <div class="grid sm:grid-cols-2 gap-6 mt-8">
              <div class="field">
                <select name="visit_work" id="visit_work">
                  <option value="">No particular work</option>
                  <?php foreach ($works as $work): ?>
                    <option value="<?= e($work['slug']) ?>" <?= $preset === $work['slug'] ? 'selected' : '' ?>>
                      <?= e($work['title']) ?> — <?= e(artist_name($work['artist'])) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <label for="visit_work">Work you want to see</label>
                <span class="field-error"></span>
              </div>

              <div class="field">
                <select name="visit_people" id="visit_people">
                  <?php foreach ([1, 2, 3, 4, 5, 6] as $n): ?>
                    <option value="<?= $n ?>" <?= $n === 2 ? 'selected' : '' ?>><?= $n ?> <?= $n === 1 ? 'person' : 'people' ?></option>
                  <?php endforeach; ?>
                </select>
                <label for="visit_people">How many of you</label>
                <span class="field-error"></span>
              </div>
            </div>
          </fieldset>

          <!-- step 2 -->
          <fieldset class="step-panel">
            <legend class="display d-3 mb-6">When suits you?</legend>

            <div class="field max-w-xs">
              <input type="date" name="visit_date" id="visit_date" data-validate="date" required placeholder=" ">
              <label for="visit_date">Preferred date</label>
              <span class="field-error"></span>
            </div>

            <p class="label-xs text-muted mt-3">Tuesday to Saturday, and Sunday afternoons. Mondays are by appointment only.</p>

            <p class="label-xs text-muted mt-9 mb-4">Time</p>
            <div class="seg grid grid-cols-3 sm:grid-cols-4 gap-3" data-field>
              <?php foreach (TIME_SLOTS as $i => $slot): ?>
                <div>
                  <input type="radio" id="slot-<?= $i ?>" name="visit_time" value="<?= e($slot) ?>" <?= $i === 2 ? 'checked' : '' ?> required>
                  <label for="slot-<?= $i ?>" class="text-center num text-sm !py-3"><?= e($slot) ?></label>
                </div>
              <?php endforeach; ?>
              <span class="field-error col-span-3 sm:col-span-4"></span>
            </div>

            <p class="prose-note text-sm mt-7">
              A private viewing runs ninety minutes. If you need longer, say so in the notes and we will simply block out the afternoon.
            </p>
          </fieldset>

          <!-- step 3 -->
          <fieldset class="step-panel">
            <legend class="display d-3 mb-6">Who should we expect?</legend>

            <div class="grid sm:grid-cols-2 gap-6">
              <div class="field">
                <input type="text" name="visit_name" id="visit_name" required placeholder=" " autocomplete="name">
                <label for="visit_name">Your name</label>
                <span class="field-error"></span>
              </div>
              <div class="field">
                <input type="tel" name="visit_phone" id="visit_phone" required placeholder=" " autocomplete="tel" data-validate="tel">
                <label for="visit_phone">Phone or WhatsApp</label>
                <span class="field-error"></span>
              </div>
              <div class="field sm:col-span-2">
                <input type="email" name="visit_email" id="visit_email" required placeholder=" " autocomplete="email" data-validate="email">
                <label for="visit_email">Email</label>
                <span class="field-error"></span>
              </div>
              <div class="field sm:col-span-2">
                <textarea name="visit_notes" id="visit_notes" placeholder=" "></textarea>
                <label for="visit_notes">Anything we should know</label>
                <span class="field-error"></span>
              </div>
            </div>

            <div class="mt-9 pt-7 rule-t">
              <p class="label-xs text-muted mb-5">Your request</p>
              <dl class="grid sm:grid-cols-2 gap-x-8 gap-y-3.5">
                <div class="flex justify-between gap-4 pb-2 rule-b"><dt class="label-xs text-muted">Visit</dt><dd class="text-sm text-right" data-summary="type">—</dd></div>
                <div class="flex justify-between gap-4 pb-2 rule-b"><dt class="label-xs text-muted">Date</dt><dd class="text-sm text-right" data-summary="date">—</dd></div>
                <div class="flex justify-between gap-4 pb-2 rule-b"><dt class="label-xs text-muted">Time</dt><dd class="text-sm text-right num" data-summary="time">—</dd></div>
                <div class="flex justify-between gap-4 pb-2 rule-b"><dt class="label-xs text-muted">Guests</dt><dd class="text-sm text-right num" data-summary="people">—</dd></div>
                <div class="flex justify-between gap-4 pb-2 rule-b sm:col-span-2"><dt class="label-xs text-muted">Work</dt><dd class="text-sm text-right" data-summary="work">—</dd></div>
              </dl>
            </div>
          </fieldset>

          <div class="flex items-center justify-between gap-4 mt-9 pt-7 rule-t">
            <button type="button" class="btn btn-ghost" data-step-prev hidden><span>← Back</span></button>
            <div class="flex gap-3 ml-auto">
              <button type="button" class="btn" data-step-next data-magnet="7"><span>Continue</span></button>
              <button type="submit" class="btn" data-step-submit hidden data-magnet="7"><span>Prepare my request</span></button>
            </div>
          </div>

          <p class="label-xs text-muted mt-6 leading-relaxed">
            This form does not send anything on its own — it prepares a message you send on WhatsApp or by email,
            so you keep a copy of exactly what was asked for.
          </p>
        </form>

        <!-- success -->
        <div data-booking-success hidden class="rule-t rule-b bg-paper p-7 sm:p-10">
          <span class="w-12 h-12 grid place-items-center rounded-full bg-char text-white mb-7" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m4 12.5 5 5L20 6.5"/></svg>
          </span>
          <h2 class="display d-2">Your request is ready</h2>
          <p class="prose-note mt-4 max-w-[46ch]">Send it on WhatsApp and we confirm the slot, usually within the hour during opening times.</p>

          <dl class="grid sm:grid-cols-2 gap-x-8 gap-y-3.5 mt-9">
            <div class="flex justify-between gap-4 pb-2 rule-b"><dt class="label-xs text-muted">Visit</dt><dd class="text-sm text-right" data-summary="type">—</dd></div>
            <div class="flex justify-between gap-4 pb-2 rule-b"><dt class="label-xs text-muted">Date</dt><dd class="text-sm text-right" data-summary="date">—</dd></div>
            <div class="flex justify-between gap-4 pb-2 rule-b"><dt class="label-xs text-muted">Time</dt><dd class="text-sm text-right num" data-summary="time">—</dd></div>
            <div class="flex justify-between gap-4 pb-2 rule-b"><dt class="label-xs text-muted">Guests</dt><dd class="text-sm text-right num" data-summary="people">—</dd></div>
            <div class="flex justify-between gap-4 pb-2 rule-b sm:col-span-2"><dt class="label-xs text-muted">Work</dt><dd class="text-sm text-right" data-summary="work">—</dd></div>
          </dl>

          <div class="grid sm:grid-cols-2 gap-3 mt-9">
            <a class="btn" href="<?= e(wa_link()) ?>" target="_blank" rel="noopener" data-wa-booking><span>Send on WhatsApp</span></a>
            <a class="btn btn-ghost" href="mailto:<?= e(EMAIL) ?>?subject=Viewing%20request"><span>Email instead</span></a>
          </div>

          <a href="gallery.php" class="inline-block label-xs text-muted hover:text-ink transition-colors mt-8">Keep looking at the collection →</a>
        </div>
      </div>

      <!-- ---------------------------------------------------- sidebar -->
      <aside class="lg:col-span-5 grid gap-10 lg:sticky" style="top:calc(var(--header-h) + 1.5rem)">

        <div class="rule-t pt-7" data-reveal="up">
          <p class="label-xs text-muted mb-5">Where</p>
          <address class="not-italic display d-3 leading-snug"><?= e(ADDRESS_LINE_1) ?><br><?= e(ADDRESS_LINE_2) ?></address>
          <div class="flex flex-wrap gap-3 mt-6">
            <a href="<?= e(map_link()) ?>" target="_blank" rel="noopener" class="btn btn-ghost btn-sm"><span>Open in Maps</span></a>
            <a href="tel:<?= e(str_replace(' ', '', PHONE_DISPLAY)) ?>" class="btn btn-ghost btn-sm num"><span><?= e(PHONE_DISPLAY) ?></span></a>
          </div>
          <p class="prose-note text-sm mt-6">
            Parking is on the service road behind the block. The gallery is on the first floor; there is a lift,
            and someone will meet you at the door if you tell us you are coming.
          </p>
        </div>

        <div class="rule-t pt-7" data-reveal="up">
          <p class="label-xs text-muted mb-5">Opening hours</p>
          <ul class="grid gap-2.5">
            <?php foreach (OPENING_HOURS as $days => $hours): ?>
              <li class="flex justify-between gap-4 pb-2.5 rule-b">
                <span class="text-sm text-muted"><?= e($days) ?></span>
                <span class="text-sm num text-muted"><?= e($hours) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="rule-t pt-7" data-reveal="up">
          <p class="label-xs text-muted mb-5">What a private viewing is</p>
          <ol class="grid gap-4">
            <?php foreach ([
                'The gallery is closed to everyone else for your slot.',
                'A curator stays in the room and answers questions, or leaves you alone — your call.',
                'Anything in storage is brought up, unwrapped and put on the wall.',
                'Nothing is priced at you. If you ask, you get the real number and the reasoning.',
                'There is no expectation that you buy anything at the end of it.',
            ] as $i => $line): ?>
              <li class="flex gap-4">
                <span class="label-xs text-ink num shrink-0 pt-0.5"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                <span class="text-sm prose-note"><?= e($line) ?></span>
              </li>
            <?php endforeach; ?>
          </ol>
        </div>

        <div class="rule-t pt-7" data-reveal="up">
          <p class="label-xs text-muted mb-4">Rather just message?</p>
          <p class="prose-note text-sm">Skip the form entirely. Tell us roughly when you want to come and we will fit you in.</p>
          <a href="<?= e(wa_link('Hello Nuqta, I would like to arrange a viewing. Here is when I am free: ')) ?>"
             target="_blank" rel="noopener" class="btn btn-ghost w-full mt-5"><span>WhatsApp the front desk</span></a>
        </div>
      </aside>
    </div>
  </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
