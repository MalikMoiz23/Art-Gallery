/* ==========================================================================
   Motion engine.

   Deliberately keeps native scrolling — no transform-based smooth-scroll
   wrapper — so position:sticky, anchor links, find-in-page and the mobile
   address bar all behave. Everything reads in one rAF pass and writes in the
   next to avoid layout thrash.
   ========================================================================== */

(function () {
  'use strict';

  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

  var vh = window.innerHeight;
  var vw = window.innerWidth;

  var scrollY = window.scrollY;
  var lastY = scrollY;
  var velocity = 0;
  var smoothVel = 0;

  var parallax = [];
  var skews = [];
  var running = false;

  var lerp = function (a, b, t) { return a + (b - a) * t; };
  var clamp = function (v, lo, hi) { return v < lo ? lo : v > hi ? hi : v; };

  /* ------------------------------------------------------------- reveal */

  function initReveal() {
    var items = document.querySelectorAll('[data-reveal]');
    if (!items.length) return;

    // Children of a group inherit an incremental delay.
    document.querySelectorAll('[data-reveal-group]').forEach(function (group) {
      var step = parseInt(group.getAttribute('data-reveal-group'), 10) || 90;
      var kids = group.querySelectorAll(':scope > [data-reveal]');
      kids.forEach(function (kid, i) {
        if (!kid.style.getPropertyValue('--reveal-delay')) {
          kid.style.setProperty('--reveal-delay', i * step + 'ms');
        }
      });
    });

    items.forEach(function (el) {
      var d = el.getAttribute('data-reveal-delay');
      if (d) el.style.setProperty('--reveal-delay', d + 'ms');
    });

    if (reduced) {
      items.forEach(function (el) { el.classList.add('is-in'); });
      return;
    }

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-in');
        if (entry.target.getAttribute('data-reveal-repeat') === null) {
          io.unobserve(entry.target);
        }
      });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.01 });

    items.forEach(function (el) { io.observe(el); });
  }

  /* --------------------------------------------------------- split text */

  // Wraps words, then regroups them into masked lines by vertical offset.
  function splitLines(el) {
    var text = el.getAttribute('data-split-text');
    if (text === null) {
      text = el.textContent.replace(/\s+/g, ' ').trim();
      el.setAttribute('data-split-text', text);
    }

    el.textContent = '';
    var words = text.split(' ');
    var spans = words.map(function (word, i) {
      var s = document.createElement('span');
      s.textContent = word + (i < words.length - 1 ? ' ' : '');
      s.style.display = 'inline-block';
      s.style.whiteSpace = 'pre';
      el.appendChild(s);
      return s;
    });

    // Group by rounded offsetTop — one bucket per rendered line.
    var lines = [];
    var currentTop = null;
    spans.forEach(function (s) {
      var top = Math.round(s.offsetTop / 4);
      if (top !== currentTop) { lines.push([]); currentTop = top; }
      lines[lines.length - 1].push(s.textContent);
    });

    el.textContent = '';
    lines.forEach(function (words, i) {
      var line = document.createElement('span');
      line.className = 'split-line';
      var inner = document.createElement('i');
      inner.textContent = words.join('');
      inner.style.setProperty('--line-delay', i * 95 + 'ms');
      line.appendChild(inner);
      el.appendChild(line);
    });
  }

  // Each word rides up out of its own mask, one after the next.
function splitWords(el) {
  var text = el.getAttribute('data-split-text');
  if (text === null) {
    text = el.textContent.replace(/\s+/g, ' ').trim();
    el.setAttribute('data-split-text', text);
  }
  el.textContent = '';
  var parts = text.split(' ');
  parts.forEach(function (word, i) {
    var wrap = document.createElement('span');
    wrap.className = 'split-word';
    var inner = document.createElement('i');
    inner.textContent = word;
    inner.style.setProperty('--word-delay', i * 115 + 'ms');
    wrap.appendChild(inner);
    el.appendChild(wrap);
    if (i < parts.length - 1) el.appendChild(document.createTextNode(' '));
  });
}

function splitChars(el) {
    var text = el.getAttribute('data-split-text');
    if (text === null) {
      text = el.textContent.trim();
      el.setAttribute('data-split-text', text);
    }
    el.textContent = '';
    text.split('').forEach(function (ch, i) {
      var s = document.createElement('span');
      s.className = 'split-char';
      s.textContent = ch === ' ' ? ' ' : ch;
      s.style.setProperty('--char-delay', i * 26 + 'ms');
      el.appendChild(s);
    });
  }

  function initSplit() {
    var targets = document.querySelectorAll('[data-split]');
    if (!targets.length || reduced) return;

    targets.forEach(function (el) {
      var mode = el.getAttribute('data-split');
      if (mode === 'chars') splitChars(el);
      else if (mode === 'words') splitWords(el);
      else splitLines(el);
      if (!el.hasAttribute('data-reveal')) el.setAttribute('data-reveal', 'fade');
    });
  }

  /* ------------------------------------------------------- collect work */

  function measure() {
    vh = window.innerHeight;
    vw = window.innerWidth;

    parallax = [];
    document.querySelectorAll('[data-parallax]').forEach(function (el) {
      var speed = parseFloat(el.getAttribute('data-parallax'));
      if (isNaN(speed)) speed = 0.12;
      var rect = el.getBoundingClientRect();
      parallax.push({
        el: el,
        speed: speed,
        top: rect.top + window.scrollY,
        h: rect.height,
        y: 0
      });
    });

    skews = Array.prototype.slice.call(document.querySelectorAll('.vel-skew'));
  }

  /* ------------------------------------------------------------- frame */

  function frame() {
    scrollY = window.scrollY;
    velocity = scrollY - lastY;
    lastY = scrollY;
    smoothVel = lerp(smoothVel, velocity, 0.12);

    // Parallax: progress runs -1 .. 1 across the element's travel.
    for (var i = 0; i < parallax.length; i++) {
      var p = parallax[i];
      var centre = p.top + p.h / 2;
      var progress = (scrollY + vh / 2 - centre) / (vh + p.h);
      var target = clamp(progress, -1, 1) * p.speed * vh * -1;
      p.y = lerp(p.y, target, 0.14);
      if (Math.abs(p.y) < 0.02) p.y = 0;
      p.el.style.transform = 'translate3d(0,' + p.y.toFixed(2) + 'px,0)';
    }

    // Velocity skew — capped hard so text never becomes unreadable.
    if (skews.length) {
      var deg = clamp(smoothVel * 0.028, -2.1, 2.1);
      for (var s = 0; s < skews.length; s++) {
        skews[s].style.setProperty('--vel', deg.toFixed(3) + 'deg');
      }
    }

    requestAnimationFrame(frame);
  }

  /* ---------------------------------------------------------- counters */

  function initCounters() {
    var els = document.querySelectorAll('[data-count]');
    if (!els.length) return;

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        io.unobserve(el);
        var to = parseFloat(el.getAttribute('data-count')) || 0;
        if (reduced) { el.textContent = String(to); return; }

        // Hold until the surrounding block has faded in, otherwise the tally
        // runs while the number is still transparent.
        var host = el.closest('[data-reveal]');
        var wait = host ? (parseFloat(getComputedStyle(host).transitionDelay) || 0) * 1000 + 200 : 0;

        // Quadratic ease-out rather than quartic: a quartic curve is ~90% done
        // inside the first 750ms, so the count was over before it could be read.
        var dur = 2000;
        el.textContent = '0';
        setTimeout(function () {
          var t0 = performance.now();
          (function tick(now) {
            var t = clamp((now - t0) / dur, 0, 1);
            var eased = 1 - Math.pow(1 - t, 2);
            el.textContent = Math.round(to * eased).toLocaleString('en-US');
            if (t < 1) requestAnimationFrame(tick);
          })(performance.now());
        }, wait);
      });
    }, { threshold: 0.25 });

    els.forEach(function (el) { io.observe(el); });
  }

  /* ------------------------------------------------------------ images */

  function initImages() {
    document.querySelectorAll('[data-img]').forEach(function (img) {
      if (img.complete && img.naturalWidth) {
        img.classList.add('is-loaded');
      } else {
        img.addEventListener('load', function () { img.classList.add('is-loaded'); }, { once: true });
        img.addEventListener('error', function () { img.classList.add('is-loaded'); }, { once: true });
      }
    });
  }

  /* ------------------------------------------------- tilt, glow, magnet */

  function initPointerFx() {
    if (!fine || reduced) return;

    document.querySelectorAll('[data-tilt]').forEach(function (host) {
      var inner = host.querySelector('.tilt-inner') || host;
      var max = parseFloat(host.getAttribute('data-tilt')) || 5;
      host.addEventListener('pointermove', function (e) {
        var r = host.getBoundingClientRect();
        var px = (e.clientX - r.left) / r.width - 0.5;
        var py = (e.clientY - r.top) / r.height - 0.5;
        inner.style.setProperty('--ry', (px * max).toFixed(2) + 'deg');
        inner.style.setProperty('--rx', (-py * max).toFixed(2) + 'deg');
      });
      host.addEventListener('pointerleave', function () {
        inner.style.setProperty('--ry', '0deg');
        inner.style.setProperty('--rx', '0deg');
      });
    });

    document.querySelectorAll('.glow').forEach(function (host) {
      host.addEventListener('pointermove', function (e) {
        var r = host.getBoundingClientRect();
        host.style.setProperty('--mx', (((e.clientX - r.left) / r.width) * 100).toFixed(1) + '%');
        host.style.setProperty('--my', (((e.clientY - r.top) / r.height) * 100).toFixed(1) + '%');
      });
    });

    document.querySelectorAll('[data-magnet]').forEach(function (host) {
      var pull = parseFloat(host.getAttribute('data-magnet')) || 9;
      host.addEventListener('pointermove', function (e) {
        var r = host.getBoundingClientRect();
        host.style.setProperty('--tx', (((e.clientX - r.left) / r.width - 0.5) * pull * 2).toFixed(2) + 'px');
        host.style.setProperty('--ty', (((e.clientY - r.top) / r.height - 0.5) * pull * 2).toFixed(2) + 'px');
      });
      host.addEventListener('pointerleave', function () {
        host.style.setProperty('--tx', '0px');
        host.style.setProperty('--ty', '0px');
      });
    });
  }

  /* ----------------------------------------------------------- marquee */

  // Duplicates the row once so the -50% keyframe loops seamlessly.
  function initMarquee() {
    document.querySelectorAll('[data-marquee]').forEach(function (track) {
      track.setAttribute('aria-hidden', 'true');
      var clone = track.innerHTML;
      track.innerHTML = clone + clone;
    });
  }

  /* -------------------------------------------------------- drag rails */

  function initRails() {
    document.querySelectorAll('.rail').forEach(function (rail) {
      var down = false, startX = 0, startLeft = 0, moved = 0;

      rail.addEventListener('pointerdown', function (e) {
        if (e.pointerType === 'touch') return;
        down = true;
        moved = 0;
        startX = e.clientX;
        startLeft = rail.scrollLeft;
        rail.classList.add('is-dragging');
      });

      rail.addEventListener('pointermove', function (e) {
        if (!down) return;
        var dx = e.clientX - startX;
        moved = Math.abs(dx);
        rail.scrollLeft = startLeft - dx;
      });

      var end = function () {
        if (!down) return;
        down = false;
        rail.classList.remove('is-dragging');
      };
      rail.addEventListener('pointerup', end);
      rail.addEventListener('pointerleave', end);

      // Suppress the click that follows a real drag.
      rail.addEventListener('click', function (e) {
        if (moved > 8) { e.preventDefault(); e.stopPropagation(); }
      }, true);
    });
  }

  /* -------------------------------------------------------------- boot */

  function boot() {
    initSplit();
    initReveal();
    initImages();
    initCounters();
    initMarquee();
    initRails();
    initPointerFx();

    measure();
    if (!running) { running = true; requestAnimationFrame(frame); }

    var t;
    window.addEventListener('resize', function () {
      clearTimeout(t);
      t = setTimeout(function () {
        // Re-split only headings that have not played yet.
        // Only line-split headings depend on the measured width.
        document.querySelectorAll('[data-split]:not(.is-in)').forEach(function (el) {
          var mode = el.getAttribute('data-split');
          if (mode !== 'chars' && mode !== 'words') splitLines(el);
        });
        measure();
      }, 180);
    });

    // Late-loading imagery changes the page height; re-measure once settled.
    window.addEventListener('load', function () { setTimeout(measure, 120); });
  }

  window.Motion = { measure: measure, initImages: initImages, initReveal: initReveal, reduced: reduced };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
