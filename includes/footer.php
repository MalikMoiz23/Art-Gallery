<footer class="relative bg-char text-mist overflow-hidden">

  <div class="marquee-mask py-5 rule-b">
    <div class="marquee animate-marquee-slow" data-marquee>
      <?php for ($i = 0; $i < 4; $i++): ?>
        <span class="flex items-center gap-6 pr-6 label-xs text-white/45">
          <span>Painting</span><span class="text-white/25">·</span>
          <span>Miniature</span><span class="text-white/25">·</span>
          <span>Calligraphy</span><span class="text-white/25">·</span>
          <span>Print</span><span class="text-white/25">·</span>
          <span>Textile</span><span class="text-white/25">·</span>
          <span>Sculpture</span><span class="text-white/25">·</span>
        </span>
      <?php endfor; ?>
    </div>
  </div>

  <div class="shell py-16 sm:py-20">
    <div class="grid gap-12 lg:grid-cols-12">

      <div class="lg:col-span-4" data-reveal="up">
        <p class="display d-2 max-w-[18ch] text-white">Come and stand in front of something.</p>
        <p class="text-sm text-mist/70 leading-relaxed mt-5 max-w-[42ch]">Tuesday to Saturday the door is simply open. For a private viewing, or to see a work held in storage, book a slot and the room is yours.</p>
        <div class="flex flex-wrap gap-3 mt-7">
          <a href="booking.php" class="btn btn-invert" data-magnet="7"><span>Book a viewing</span></a>
          <a href="<?= e(wa_link()) ?>" target="_blank" rel="noopener" class="btn btn-invert-ghost"><span>WhatsApp</span></a>
        </div>
      </div>

      <div class="lg:col-span-2" data-reveal="up" data-reveal-delay="80">
        <p class="label-xs text-white/45 mb-4">Gallery</p>
        <ul class="grid gap-2.5 text-sm text-mist">
          <?php foreach (NAV as $item): ?>
            <li><a href="<?= e($item['file']) ?>" class="ul-draw hover:text-white transition-colors"><?= e($item['label']) ?></a></li>
          <?php endforeach; ?>
          <li><a href="cart.php" class="ul-draw hover:text-white transition-colors">Enquiry list</a></li>
        </ul>
      </div>

      <div class="lg:col-span-3" data-reveal="up" data-reveal-delay="160">
        <p class="label-xs text-white/45 mb-4">Opening hours</p>
        <ul class="grid gap-2.5 text-sm">
          <?php foreach (OPENING_HOURS as $days => $hours): ?>
            <li class="flex justify-between gap-4 pb-2 rule-b">
              <span class="text-mist"><?= e($days) ?></span>
              <span class="num text-mist/65"><?= e($hours) ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="lg:col-span-3" data-reveal="up" data-reveal-delay="240">
        <p class="label-xs text-white/45 mb-4">Find us</p>
        <address class="not-italic text-sm text-mist/75 leading-relaxed">
          <?= e(ADDRESS_LINE_1) ?><br>
          <?= e(ADDRESS_LINE_2) ?>
        </address>
        <p class="mt-4 text-sm grid gap-1.5 text-mist">
          <a href="tel:<?= e(str_replace(' ', '', PHONE_DISPLAY)) ?>" class="ul-draw num hover:text-white transition-colors"><?= e(PHONE_DISPLAY) ?></a>
          <a href="mailto:<?= e(EMAIL) ?>" class="ul-draw hover:text-white transition-colors"><?= e(EMAIL) ?></a>
          <a href="<?= e(map_link()) ?>" target="_blank" rel="noopener" class="ul-draw text-white hover:text-mist transition-colors">Open in Maps</a>
        </p>
        <ul class="flex flex-wrap gap-x-5 gap-y-2 mt-5 label-xs text-white/45">
          <?php foreach (SOCIALS as $social): ?>
            <li><a href="<?= e($social['url']) ?>" class="ul-draw hover:text-white transition-colors"><?= e($social['label']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>

  <!-- Oversized wordmark, drifting slightly against the scroll. -->
  <div class="shell pb-8 select-none" aria-hidden="true">
    <p class="display leading-[0.78] text-center text-white/[0.07] whitespace-nowrap"
       style="font-size:clamp(4rem,18vw,16rem)" data-parallax="0.05"><?= e(SITE_NAME) ?>.</p>
  </div>

  <div class="shell rule-t py-6 flex flex-col sm:flex-row items-center justify-between gap-4 label-xs text-white/45">
    <p>© <span data-year-now>2026</span> <?= e(SITE_FULL) ?>. All works © the artists.</p>
    <div class="flex items-center gap-6">
      <button type="button" class="ul-draw hover:text-white transition-colors" data-to-top>Back to top ↑</button>
      <span class="hidden sm:inline">Islamabad, PK</span>
    </div>
  </div>
</footer>

<?php include __DIR__ . '/cart-drawer.php'; ?>
<?php include __DIR__ . '/whatsapp.php'; ?>

<script src="assets/js/cart.js"></script>
<script src="assets/js/motion.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
