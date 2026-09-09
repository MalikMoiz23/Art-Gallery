<?php
require_once __DIR__ . '/config.php';

$page_title = 'The artists';
$page_desc  = 'The eight artists Nuqta represents — painters, a miniaturist, a calligrapher, a printmaker, a weaver and a sculptor, working between Lahore, Karachi, Islamabad, Peshawar, Multan, Sialkot and Quetta.';

$artists = all_artists();

include __DIR__ . '/includes/head.php';
?>

<main id="main">

  <section class="shell pb-16" style="padding-top:calc(var(--header-h) + 5rem)">
    <p class="label-xs text-brass-600 flex items-center gap-3 mb-6" data-reveal="right">
      <span class="num">02</span><span class="w-8 h-px bg-brass-600/50"></span>Represented
    </p>

    <div class="grid lg:grid-cols-12 gap-8 items-end">
      <h1 class="display d-hero lg:col-span-7" data-split="lines" data-reveal="fade">Eight hands</h1>
      <p class="lede lg:col-span-5 max-w-[44ch]" data-reveal="up" data-reveal-delay="240">
        We represent eight artists and no more. That is a deliberate ceiling: it means anyone at the desk
        can answer a real question about any work in the building without going away to check.
      </p>
    </div>

    <div class="flex flex-wrap gap-x-8 gap-y-3 mt-12 pt-8 rule-t label-xs text-paper-500" data-reveal="up">
      <?php foreach ($artists as $a): ?>
        <span><?= e($a['city']) ?></span>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="shell pb-24 sm:pb-32">
    <div class="grid gap-10">
      <?php foreach ($artists as $i => $artist): ?>
        <?php $index = $i; include __DIR__ . '/components/artist-card.php'; ?>
      <?php endforeach; ?>
      <div class="rule-t"></div>
    </div>
  </section>

  <section class="plaster text-ink-900 py-20 sm:py-28">
    <div class="shell grid lg:grid-cols-12 gap-10">
      <div class="lg:col-span-5">
        <p class="label-xs text-clay-600 mb-6" data-reveal="right">Submissions</p>
        <h2 class="display d-1 text-ink-900" data-split="lines" data-reveal="fade">We look twice a year</h2>
      </div>
      <div class="lg:col-span-7">
        <p class="text-ink-700 leading-relaxed max-w-[58ch]" data-reveal="up">
          In March and September we go through everything that has come in. Send twelve images, dimensions and one
          paragraph about what you are doing — not a CV. We reply to every submission, which is exactly why it takes
          a few weeks rather than a few days.
        </p>
        <div class="flex flex-wrap gap-3 mt-8" data-reveal="up" data-reveal-delay="120">
          <a href="mailto:<?= e(EMAIL) ?>?subject=Submission" class="btn btn-solid-dark" data-no-veil><span>Email a submission</span></a>
          <a href="<?= e(wa_link('Hello Nuqta, I would like to ask about submitting work for review.')) ?>" target="_blank" rel="noopener" class="btn btn-ghost !text-ink-900 !border-ink-900/25" data-no-veil><span>Ask a question first</span></a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
