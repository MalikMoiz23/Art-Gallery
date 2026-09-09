/* ==========================================================================
   Interface wiring: chrome, enquiry drawer, gallery filtering, lightbox,
   booking flow and the WhatsApp handoff.
   ========================================================================== */

(function () {
  'use strict';

  var qs = function (sel, root) { return (root || document).querySelector(sel); };
  var qsa = function (sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); };
  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var WA = document.documentElement.getAttribute('data-wa') || '';

  function waLink(message) {
    return 'https://wa.me/' + WA + '?text=' + encodeURIComponent(message);
  }

  /* ------------------------------------------------------------- toasts */

  var stack = null;

  function toast(message, tone) {
    if (!stack) {
      stack = document.createElement('div');
      stack.className = 'toast-stack';
      stack.setAttribute('role', 'status');
      stack.setAttribute('aria-live', 'polite');
      document.body.appendChild(stack);
    }
    var el = document.createElement('div');
    el.className = 'toast';
    if (tone === 'warn') el.style.borderLeftColor = 'var(--clay)';
    el.innerHTML = '<span class="label-xs" style="color:var(--muted)">' + (tone === 'warn' ? 'Note' : 'Added') +
      '</span><p class="text-sm" style="color:rgb(212 202 186 / .9)"></p>';
    qs('p', el).textContent = message;
    stack.appendChild(el);
    requestAnimationFrame(function () { el.classList.add('is-in'); });
    setTimeout(function () {
      el.classList.remove('is-in');
      setTimeout(function () { el.remove(); }, 500);
    }, 3400);
  }

  window.toast = toast;

  /* ------------------------------------------------- header + progress */

  function initChrome() {
    var header = qs('.site-header');
    var rail = qs('.scroll-rail > i');
    var lastY = window.scrollY;
    var ticking = false;

    function update() {
      var y = window.scrollY;
      var max = document.documentElement.scrollHeight - window.innerHeight;
      if (rail) rail.style.setProperty('--p', max > 0 ? (y / max).toFixed(4) : '0');

      if (header) {
        header.classList.toggle('is-stuck', y > 24);
        // Hide on the way down, reveal the moment the user reverses.
        var menuOpen = document.body.classList.contains('menu-open');
        header.classList.toggle('is-hidden', !menuOpen && y > 420 && y > lastY + 4);
      }
      lastY = y;
      ticking = false;
    }

    window.addEventListener('scroll', function () {
      if (!ticking) { ticking = true; requestAnimationFrame(update); }
    }, { passive: true });

    update();
  }

  /* -------------------------------------------------------------- menu */

  function initMenu() {
    var sheet = qs('.menu-sheet');
    var toggle = qs('[data-menu-toggle]');
    if (!sheet || !toggle) return;

    function setOpen(open) {
      sheet.classList.toggle('is-open', open);
      document.body.classList.toggle('menu-open', open);
      document.body.classList.toggle('is-locked', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      qsa('.m-item', sheet).forEach(function (item, i) {
        item.style.transitionDelay = open ? (140 + i * 65) + 'ms' : '0ms';
      });
    }

    toggle.addEventListener('click', function () {
      setOpen(!sheet.classList.contains('is-open'));
    });

    qsa('a', sheet).forEach(function (a) {
      a.addEventListener('click', function () { setOpen(false); });
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && sheet.classList.contains('is-open')) setOpen(false);
    });
  }

  /* ------------------------------------------------------ enquiry list */

  function itemFromButton(btn) {
    return {
      slug: btn.getAttribute('data-slug'),
      title: btn.getAttribute('data-title'),
      artist: btn.getAttribute('data-artist'),
      price: parseInt(btn.getAttribute('data-price'), 10) || 0,
      img: btn.getAttribute('data-thumb'),
      edition: btn.getAttribute('data-edition') || '',
      unique: btn.getAttribute('data-unique') === '1'
    };
  }

  function drawerRow(item) {
    var max = item.unique ? 1 : window.Cart.MAX_EDITION;
    return '' +
      '<li class="drawer-row flex gap-4 py-5 rule-b" data-row="' + item.slug + '">' +
        '<a href="artwork.php?slug=' + encodeURIComponent(item.slug) + '" class="media w-20 shrink-0" style="aspect-ratio:3/4">' +
          '<img src="assets/img/' + item.img + '" alt="" class="is-loaded" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">' +
        '</a>' +
        '<div class="min-w-0 flex-1">' +
          '<a href="artwork.php?slug=' + encodeURIComponent(item.slug) + '" class="d-4 display block leading-tight hover:text-muted transition-colors">' + esc(item.title) + '</a>' +
          '<p class="text-xs text-muted mt-1">' + esc(item.artist) + (item.edition ? ' · ' + esc(item.edition) : '') + '</p>' +
          '<div class="flex items-center justify-between gap-3 mt-3">' +
            '<div class="flex items-center gap-2">' +
              (max > 1
                ? '<button class="icon-btn !w-7 !h-7 text-sm" data-qty="-1" aria-label="Reduce quantity">&minus;</button>' +
                  '<span class="num text-sm w-4 text-center" data-qty-value>' + item.qty + '</span>' +
                  '<button class="icon-btn !w-7 !h-7 text-sm" data-qty="1" aria-label="Increase quantity">+</button>'
                : '<span class="chip chip-muted">Unique work</span>') +
            '</div>' +
            '<span class="num text-sm text-ink">' + window.Cart.money(item.price * item.qty) + '</span>' +
          '</div>' +
        '</div>' +
        '<button class="text-muted hover:text-ink transition-colors self-start" data-drop aria-label="Remove ' + esc(item.title) + '">' +
          '<svg width="15" height="15" viewBox="0 0 15 15" fill="none" stroke="currentColor" stroke-width="1.3"><path d="M2 2l11 11M13 2L2 13"/></svg>' +
        '</button>' +
      '</li>';
  }

  function esc(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  function enquiryMessage() {
    var items = window.Cart.items();
    if (!items.length) return 'Hello Nuqta, I would like to enquire about the collection.';
    var lines = items.map(function (i) {
      return '• ' + i.qty + ' × ' + i.title + ' — ' + i.artist + ' (' + window.Cart.money(i.price * i.qty) + ')';
    });
    return 'Hello Nuqta, I would like to enquire about these works:\n\n' + lines.join('\n') +
      '\n\nTotal: ' + window.Cart.money(window.Cart.total()) + '\n\nPlease let me know availability and next steps.';
  }

  function paintCart() {
    var items = window.Cart.items();
    var n = window.Cart.count();

    qsa('[data-cart-badge]').forEach(function (badge) {
      badge.textContent = String(n);
      badge.classList.toggle('is-on', n > 0);
    });

    var body = qs('[data-drawer-body]');
    if (body) {
      body.innerHTML = items.length
        ? '<ul>' + items.map(drawerRow).join('') + '</ul>'
        : '<div class="py-16 text-center">' +
            '<p class="display d-3">Nothing held yet</p>' +
            '<p class="prose-note text-sm mt-3 max-w-[26ch] mx-auto">Add a work to start an enquiry. Nothing is charged — we reply with availability and next steps.</p>' +
            '<a href="gallery.php" class="btn btn-ghost mt-7"><span>Browse the collection</span></a>' +
          '</div>';
      qsa('.drawer-row', body).forEach(function (row, i) {
        row.style.transitionDelay = (90 + i * 55) + 'ms';
      });
    }

    qsa('[data-cart-total]').forEach(function (el) { el.textContent = window.Cart.money(window.Cart.total()); });
    qsa('[data-cart-count-text]').forEach(function (el) {
      el.textContent = n === 0 ? 'Empty' : n + (n === 1 ? ' work' : ' works');
    });
    qsa('[data-cart-empty-toggle]').forEach(function (el) { el.hidden = items.length > 0; });
    qsa('[data-cart-filled-toggle]').forEach(function (el) { el.hidden = items.length === 0; });
    qsa('[data-wa-enquiry]').forEach(function (a) { a.href = waLink(enquiryMessage()); });

    // Reflect membership on every add button across the page.
    qsa('[data-add]').forEach(function (btn) {
      var inList = window.Cart.has(btn.getAttribute('data-slug'));
      btn.classList.toggle('is-in-list', inList);
      var lbl = qs('[data-add-label]', btn);
      if (lbl) lbl.textContent = inList ? 'In your list' : (btn.getAttribute('data-label') || 'Add to enquiry');
    });

    paintCartPage();
  }

  function paintCartPage() {
    var host = qs('[data-cart-page]');
    if (!host) return;
    var items = window.Cart.items();

    host.innerHTML = items.map(function (item) {
      var max = item.unique ? 1 : window.Cart.MAX_EDITION;
      return '' +
        '<article class="grid grid-cols-[88px_1fr] sm:grid-cols-[130px_1fr_auto] gap-5 sm:gap-8 items-start py-8 rule-b" data-row="' + item.slug + '">' +
          '<a href="artwork.php?slug=' + encodeURIComponent(item.slug) + '" class="media" style="aspect-ratio:3/4">' +
            '<img src="assets/img/' + item.img + '" alt="" class="is-loaded" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">' +
          '</a>' +
          '<div>' +
            '<a href="artwork.php?slug=' + encodeURIComponent(item.slug) + '" class="display d-3 hover:text-muted transition-colors">' + esc(item.title) + '</a>' +
            '<p class="text-sm text-muted mt-2">' + esc(item.artist) + '</p>' +
            '<p class="text-xs text-muted mt-1">' + esc(item.edition || 'Unique work') + '</p>' +
            '<div class="flex items-center gap-3 mt-5">' +
              (max > 1
                ? '<button class="icon-btn !w-8 !h-8" data-qty="-1" aria-label="Reduce quantity">&minus;</button>' +
                  '<span class="num w-5 text-center" data-qty-value>' + item.qty + '</span>' +
                  '<button class="icon-btn !w-8 !h-8" data-qty="1" aria-label="Increase quantity">+</button>'
                : '<span class="chip chip-muted">Unique · one only</span>') +
              '<button class="text-xs label-xs text-muted hover:text-ink transition-colors ml-2" data-drop>Remove</button>' +
            '</div>' +
          '</div>' +
          '<div class="col-span-2 sm:col-span-1 text-left sm:text-right">' +
            '<p class="num display d-4 text-ink">' + window.Cart.money(item.price * item.qty) + '</p>' +
            (item.qty > 1 ? '<p class="text-xs text-muted mt-1 num">' + window.Cart.money(item.price) + ' each</p>' : '') +
          '</div>' +
        '</article>';
    }).join('');
  }

  function initCart() {
    var drawer = qs('.drawer');
    var scrim = qs('.drawer-scrim');

    function setDrawer(open) {
      if (!drawer) return;
      drawer.classList.toggle('is-open', open);
      if (scrim) scrim.classList.toggle('is-open', open);
      document.body.classList.toggle('is-locked', open);
      drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
      if (open) {
        var close = qs('[data-drawer-close]', drawer);
        if (close) close.focus();
      }
    }

    qsa('[data-drawer-open]').forEach(function (btn) {
      btn.addEventListener('click', function (e) { e.preventDefault(); setDrawer(true); });
    });
    qsa('[data-drawer-close]').forEach(function (btn) {
      btn.addEventListener('click', function () { setDrawer(false); });
    });
    if (scrim) scrim.addEventListener('click', function () { setDrawer(false); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && drawer && drawer.classList.contains('is-open')) setDrawer(false);
    });

    // Add buttons anywhere on the page.
    document.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-add]');
      if (!btn) return;
      e.preventDefault();

      var item = itemFromButton(btn);
      var result = window.Cart.add(item);

      if (!result.ok) {
        toast(result.reason === 'unique'
          ? item.title + ' is a unique work and is already on your list.'
          : 'Maximum of ' + window.Cart.MAX_EDITION + ' from an edition.', 'warn');
        return;
      }

      toast(item.title + ' added to your enquiry list.');
      var badge = qs('[data-cart-badge]');
      if (badge) {
        badge.classList.add('is-bump');
        setTimeout(function () { badge.classList.remove('is-bump'); }, 520);
      }
      if (btn.hasAttribute('data-add-open')) setDrawer(true);
    });

    // Quantity and removal, in the drawer and on the cart page.
    document.addEventListener('click', function (e) {
      var row = e.target.closest('[data-row]');
      if (!row) return;
      var slug = row.getAttribute('data-row');

      var step = e.target.closest('[data-qty]');
      if (step) {
        var current = parseInt(qs('[data-qty-value]', row).textContent, 10) || 1;
        window.Cart.setQty(slug, current + parseInt(step.getAttribute('data-qty'), 10));
        return;
      }

      if (e.target.closest('[data-drop]')) {
        row.style.transition = 'opacity .35s var(--ease), transform .35s var(--ease)';
        row.style.opacity = '0';
        row.style.transform = 'translateX(24px)';
        setTimeout(function () { window.Cart.remove(slug); }, 260);
      }
    });

    qsa('[data-cart-clear]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        window.Cart.clear();
        toast('Enquiry list cleared.', 'warn');
      });
    });

    document.addEventListener('cart:change', paintCart);
    paintCart();
  }

  /* --------------------------------------------------- gallery filters */

  function initGallery() {
    var grid = qs('[data-grid]');
    if (!grid) return;

    var cards = qsa('[data-card]', grid);
    var search = qs('[data-search]');
    var sort = qs('[data-sort]');
    var readout = qs('[data-result-count]');
    var empty = qs('[data-grid-empty]');
    var activeCat = 'all';
    var activeStatus = 'all';

    function matches(card) {
      var cat = card.getAttribute('data-category');
      var status = card.getAttribute('data-status');
      if (activeCat !== 'all' && cat !== activeCat) return false;
      if (activeStatus !== 'all' && status !== activeStatus) return false;
      if (search && search.value.trim()) {
        var q = search.value.trim().toLowerCase();
        if (card.getAttribute('data-haystack').indexOf(q) === -1) return false;
      }
      return true;
    }

    // FLIP: measure, mutate, invert, play.
    function apply() {
      var before = cards.map(function (c) {
        return c.classList.contains('is-filtered-out') ? null : c.getBoundingClientRect();
      });

      var shown = 0;
      cards.forEach(function (card) {
        var ok = matches(card);
        card.classList.toggle('is-filtered-out', !ok);
        if (ok) shown++;
      });

      if (readout) readout.textContent = shown + (shown === 1 ? ' work' : ' works');
      if (empty) empty.hidden = shown > 0;

      if (reduced) return;

      cards.forEach(function (card, i) {
        if (card.classList.contains('is-filtered-out')) return;
        var after = card.getBoundingClientRect();
        var was = before[i];

        if (!was) {
          card.style.transition = 'none';
          card.style.opacity = '0';
          card.style.transform = 'scale(.94)';
          requestAnimationFrame(function () {
            card.style.transition = 'opacity .5s var(--ease), transform .5s var(--ease)';
            card.style.opacity = '1';
            card.style.transform = 'none';
          });
          return;
        }

        var dx = was.left - after.left;
        var dy = was.top - after.top;
        if (!dx && !dy) return;

        card.style.transition = 'none';
        card.style.transform = 'translate3d(' + dx + 'px,' + dy + 'px,0)';
        requestAnimationFrame(function () {
          card.style.transition = 'transform .6s var(--ease)';
          card.style.transform = 'none';
        });
      });
    }

    qsa('[data-filter]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var group = btn.getAttribute('data-filter-group') || 'cat';
        qsa('[data-filter][data-filter-group="' + group + '"]').forEach(function (sibling) {
          sibling.setAttribute('aria-pressed', sibling === btn ? 'true' : 'false');
        });
        if (group === 'cat') activeCat = btn.getAttribute('data-filter');
        else activeStatus = btn.getAttribute('data-filter');
        apply();
      });
    });

    if (search) {
      var t;
      search.addEventListener('input', function () {
        clearTimeout(t);
        t = setTimeout(apply, 160);
      });
    }

    if (sort) {
      sort.addEventListener('change', function () {
        var mode = sort.value;
        var order = cards.slice().sort(function (a, b) {
          var pa = parseInt(a.getAttribute('data-price'), 10);
          var pb = parseInt(b.getAttribute('data-price'), 10);
          var ya = parseInt(a.getAttribute('data-year'), 10);
          var yb = parseInt(b.getAttribute('data-year'), 10);
          if (mode === 'price-asc') return pa - pb;
          if (mode === 'price-desc') return pb - pa;
          if (mode === 'year-desc') return yb - ya;
          if (mode === 'title') return a.getAttribute('data-title').localeCompare(b.getAttribute('data-title'));
          return parseInt(a.getAttribute('data-index'), 10) - parseInt(b.getAttribute('data-index'), 10);
        });
        order.forEach(function (card) { grid.appendChild(card); });
        apply();
        if (window.Motion) window.Motion.measure();
      });
    }

    // Deep link: gallery.php?category=Print
    var preset = new URLSearchParams(window.location.search).get('category');
    if (preset) {
      var target = qs('[data-filter="' + preset.replace(/"/g, '') + '"]');
      if (target) target.click();
    }
  }

  /* ---------------------------------------------------------- lightbox */

  function initLightbox() {
    var triggers = qsa('[data-zoom]');
    if (!triggers.length) return;

    var box = document.createElement('div');
    box.className = 'lightbox';
    box.setAttribute('role', 'dialog');
    box.setAttribute('aria-modal', 'true');
    box.setAttribute('aria-label', 'Artwork, enlarged');
    box.innerHTML =
      '<button class="icon-btn absolute top-5 right-5 z-10" data-lb-close aria-label="Close">' +
        '<svg width="16" height="16" viewBox="0 0 15 15" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M2 2l11 11M13 2L2 13"/></svg>' +
      '</button>' +
      '<p class="absolute bottom-5 left-1/2 -translate-x-1/2 label-xs text-muted">Click the image to zoom · Esc to close</p>' +
      // 1x1 transparent placeholder: an <img> with no src is invalid markup.
      '<img alt="" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">';
    document.body.appendChild(box);

    var img = qs('img', box);

    function open(src, alt) {
      img.src = src;
      img.alt = alt || '';
      img.classList.remove('is-zoomed');
      box.classList.add('is-open');
      document.body.classList.add('is-locked');
    }

    function close() {
      box.classList.remove('is-open');
      document.body.classList.remove('is-locked');
    }

    triggers.forEach(function (t) {
      t.addEventListener('click', function (e) {
        e.preventDefault();
        var full = t.getAttribute('data-zoom');
        var picture = qs('img', t);
        open(full || (picture && picture.src), picture && picture.alt);
      });
    });

    img.addEventListener('click', function (e) {
      var r = img.getBoundingClientRect();
      img.style.setProperty('--ox', (((e.clientX - r.left) / r.width) * 100).toFixed(1) + '%');
      img.style.setProperty('--oy', (((e.clientY - r.top) / r.height) * 100).toFixed(1) + '%');
      img.classList.toggle('is-zoomed');
    });

    qs('[data-lb-close]', box).addEventListener('click', close);
    box.addEventListener('click', function (e) { if (e.target === box) close(); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && box.classList.contains('is-open')) close();
    });
  }

  /* --------------------------------------------------------- accordion */

  function initAccordion() {
    qsa('.acc-item').forEach(function (item) {
      var btn = qs('.acc-btn', item);
      if (!btn) return;
      btn.addEventListener('click', function () {
        var open = item.classList.contains('is-open');
        // One panel at a time within a list.
        var list = item.closest('[data-accordion]');
        if (list && !open) {
          qsa('.acc-item.is-open', list).forEach(function (other) {
            other.classList.remove('is-open');
            qs('.acc-btn', other).setAttribute('aria-expanded', 'false');
          });
        }
        item.classList.toggle('is-open', !open);
        btn.setAttribute('aria-expanded', !open ? 'true' : 'false');
      });
    });
  }

  /* ------------------------------------------------------------- forms */

  function fieldOf(input) { return input.closest('.field') || input.closest('[data-field]'); }

  function setError(input, message) {
    var wrap = fieldOf(input);
    if (!wrap) return;
    wrap.classList.add('is-invalid');
    var slot = qs('.field-error', wrap);
    if (slot) slot.textContent = message;
    input.setAttribute('aria-invalid', 'true');
  }

  function clearError(input) {
    var wrap = fieldOf(input);
    if (!wrap) return;
    wrap.classList.remove('is-invalid');
    input.removeAttribute('aria-invalid');
  }

  function validate(input) {
    var value = (input.value || '').trim();
    var type = input.getAttribute('data-validate') || input.type;

    if (input.hasAttribute('required') && !value) {
      setError(input, 'This field is required.');
      return false;
    }
    if (!value) { clearError(input); return true; }

    if (type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value)) {
      setError(input, 'Enter a valid email address.');
      return false;
    }
    if (type === 'tel' && value.replace(/\D/g, '').length < 10) {
      setError(input, 'Enter a full phone number with area code.');
      return false;
    }
    if (type === 'date') {
      var picked = new Date(value + 'T00:00:00');
      var today = new Date();
      today.setHours(0, 0, 0, 0);
      if (isNaN(picked.getTime()) || picked < today) {
        setError(input, 'Choose today or a later date.');
        return false;
      }
      if (picked.getDay() === 1) {
        setError(input, 'Mondays are by appointment only — pick another day or message us.');
        return false;
      }
    }
    clearError(input);
    return true;
  }

  function validateScope(scope) {
    var ok = true;
    qsa('input, select, textarea', scope).forEach(function (input) {
      if (input.type === 'hidden' || input.disabled) return;
      if (input.type === 'radio') {
        var group = qsa('input[name="' + input.name + '"]', scope);
        var chosen = group.some(function (r) { return r.checked; });
        if (!chosen && input.hasAttribute('required')) {
          var host = input.closest('[data-field]');
          if (host) {
            host.classList.add('is-invalid');
            var slot = qs('.field-error', host);
            if (slot) slot.textContent = 'Pick one to continue.';
          }
          ok = false;
        }
        return;
      }
      if (!validate(input)) ok = false;
    });
    return ok;
  }

  function initFieldBehaviour() {
    qsa('.field input, .field select, .field textarea').forEach(function (input) {
      var sync = function () {
        var wrap = fieldOf(input);
        if (!wrap) return;
        // A select is never empty-looking - it always shows an option - so its
        // label must stay floated regardless of value.
        var filled = input.tagName === 'SELECT' || !!(input.value || '').trim();
        wrap.classList.toggle('is-filled', filled);
      };
      sync();
      input.addEventListener('input', function () { sync(); clearError(input); });
      input.addEventListener('change', sync);
      input.addEventListener('blur', function () { validate(input); });
    });

    qsa('[data-field] input[type="radio"]').forEach(function (radio) {
      radio.addEventListener('change', function () {
        var host = radio.closest('[data-field]');
        if (host) host.classList.remove('is-invalid');
      });
    });
  }

  /* ------------------------------------------------------ booking flow */

  function initBooking() {
    var form = qs('[data-booking]');
    if (!form) return;

    var panels = qsa('.step-panel', form);
    var dots = qsa('.step-dot', form);
    var labels = qsa('[data-step-label]', form);
    var next = qs('[data-step-next]', form);
    var prev = qs('[data-step-prev]', form);
    var submit = qs('[data-step-submit]', form);
    var success = qs('[data-booking-success]', form.parentNode);
    var step = 0;

    // Same-day bookings are fine, but nothing in the past.
    var dateInput = qs('input[type="date"]', form);
    if (dateInput) {
      var today = new Date();
      dateInput.min = today.toISOString().slice(0, 10);
      var horizon = new Date(today.getTime() + 1000 * 60 * 60 * 24 * 120);
      dateInput.max = horizon.toISOString().slice(0, 10);
    }

    function show(i) {
      step = Math.max(0, Math.min(panels.length - 1, i));
      panels.forEach(function (p, n) { p.classList.toggle('is-on', n === step); });
      dots.forEach(function (d, n) { d.classList.toggle('is-on', n <= step); });
      labels.forEach(function (l, n) { l.classList.toggle('text-ink', n === step); l.classList.toggle('text-muted', n !== step); });
      if (prev) prev.hidden = step === 0;
      if (next) next.hidden = step === panels.length - 1;
      if (submit) submit.hidden = step !== panels.length - 1;
      if (step === panels.length - 1) fillSummary();
      var first = qs('input:not([type="radio"]), select, textarea', panels[step]);
      if (first && step > 0) first.focus({ preventScroll: true });
    }

    function readable() {
      var type = qs('input[name="visit_type"]:checked', form);
      var date = qs('input[name="visit_date"]', form);
      var slot = qs('input[name="visit_time"]:checked', form);
      var work = qs('select[name="visit_work"]', form);
      var people = qs('select[name="visit_people"]', form);
      return {
        type: type ? type.getAttribute('data-label') : '—',
        date: date && date.value ? new Date(date.value + 'T00:00:00').toLocaleDateString('en-GB', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }) : '—',
        time: slot ? slot.value : '—',
        work: work && work.value ? work.options[work.selectedIndex].text : 'No particular work',
        people: people ? people.value : '1'
      };
    }

    function fillSummary() {
      var r = readable();
      var map = { type: r.type, date: r.date, time: r.time, work: r.work, people: r.people };
      qsa('[data-summary]', form).forEach(function (el) {
        el.textContent = map[el.getAttribute('data-summary')] || '—';
      });
    }

    if (next) {
      next.addEventListener('click', function () {
        if (!validateScope(panels[step])) {
          var bad = qs('.is-invalid', panels[step]);
          if (bad) bad.scrollIntoView({ behavior: reduced ? 'auto' : 'smooth', block: 'center' });
          return;
        }
        show(step + 1);
      });
    }
    if (prev) prev.addEventListener('click', function () { show(step - 1); });

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!validateScope(panels[step])) return;

      var r = readable();
      var name = qs('input[name="visit_name"]', form);
      var message = 'Hello Nuqta, I would like to book a visit.\n\n' +
        'Type: ' + r.type + '\n' +
        'Date: ' + r.date + '\n' +
        'Time: ' + r.time + '\n' +
        'Guests: ' + r.people + '\n' +
        'Work of interest: ' + r.work + '\n' +
        'Name: ' + (name ? name.value : '') + '\n\n' +
        'Please confirm.';

      if (success) {
        var link = qs('[data-wa-booking]', success);
        if (link) link.href = waLink(message);
        qsa('[data-summary]', success).forEach(function (el) {
          el.textContent = ({ type: r.type, date: r.date, time: r.time, work: r.work, people: r.people })[el.getAttribute('data-summary')] || '—';
        });
        form.hidden = true;
        success.hidden = false;
        success.scrollIntoView({ behavior: reduced ? 'auto' : 'smooth', block: 'center' });
      }
      toast('Request prepared. Send it on WhatsApp to confirm.');
    });

    show(0);
  }

  /* ------------------------------------------------------ simple forms */

  function initSimpleForms() {
    qsa('[data-simple-form]').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!validateScope(form)) {
          var bad = qs('.is-invalid', form);
          if (bad) bad.scrollIntoView({ behavior: reduced ? 'auto' : 'smooth', block: 'center' });
          return;
        }
        var done = qs('[data-form-done]', form.parentNode);

        var subject = qs('[name="subject"]', form);
        var message = qs('[name="message"]', form);
        var name = qs('[name="name"]', form);
        var text = 'Hello Nuqta,\n\n' +
          (subject && subject.value ? 'Subject: ' + subject.value + '\n\n' : '') +
          (message && message.value ? message.value + '\n\n' : '') +
          (name && name.value ? '— ' + name.value : '');

        if (done) {
          var link = qs('[data-wa-form]', done);
          if (link) link.href = waLink(text);
          form.hidden = true;
          done.hidden = false;
        }
        toast('Message ready. Send it on WhatsApp and we will reply the same day.');
      });
    });
  }

  /* ---------------------------------------------------------- whatsapp */

  function initWhatsApp() {
    var fab = qs('.wa-fab');
    if (!fab) return;
    var trigger = qs('.wa-trigger', fab);
    var card = qs('.wa-card', fab);
    if (!trigger || !card) return;

    trigger.addEventListener('click', function () {
      var open = card.classList.toggle('is-open');
      trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    document.addEventListener('click', function (e) {
      if (!fab.contains(e.target)) {
        card.classList.remove('is-open');
        trigger.setAttribute('aria-expanded', 'false');
      }
    });

    // Surface it once the visitor has actually looked at something.
    setTimeout(function () { fab.classList.add('is-ready'); }, 1200);
  }

  /* -------------------------------------------------------- misc bits */

  function initMisc() {
    qsa('[data-year-now]').forEach(function (el) { el.textContent = String(new Date().getFullYear()); });

    qsa('[data-copy]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var value = btn.getAttribute('data-copy');
        if (navigator.clipboard) {
          navigator.clipboard.writeText(value).then(function () { toast('Copied ' + value); });
        }
      });
    });

    var top = qs('[data-to-top]');
    if (top) {
      top.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: reduced ? 'auto' : 'smooth' });
      });
    }
  }

  function boot() {
    initChrome();
    initMenu();
    initCart();
    initGallery();
    initLightbox();
    initAccordion();
    initFieldBehaviour();
    initBooking();
    initSimpleForms();
    initWhatsApp();
    initMisc();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
