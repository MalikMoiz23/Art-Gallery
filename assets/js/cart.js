/* ==========================================================================
   Enquiry list ("cart").

   Front-end only: state lives in localStorage under one key. Unique works are
   locked to a quantity of one — you cannot buy the same painting twice — while
   editioned prints may go up to three.
   ========================================================================== */

(function () {
  'use strict';

  var KEY = 'nuqta.cart.v1';
  var MAX_EDITION = 3;

  var state = load();

  function load() {
    try {
      var raw = localStorage.getItem(KEY);
      var parsed = raw ? JSON.parse(raw) : [];
      return Array.isArray(parsed) ? parsed : [];
    } catch (err) {
      return [];
    }
  }

  function save() {
    try {
      localStorage.setItem(KEY, JSON.stringify(state));
    } catch (err) {
      /* private mode or quota — the list simply won't survive a reload */
    }
    document.dispatchEvent(new CustomEvent('cart:change', { detail: snapshot() }));
  }

  function snapshot() {
    return { items: state.slice(), count: count(), total: total() };
  }

  function count() {
    return state.reduce(function (n, i) { return n + i.qty; }, 0);
  }

  function total() {
    return state.reduce(function (n, i) { return n + i.price * i.qty; }, 0);
  }

  function find(slug) {
    for (var i = 0; i < state.length; i++) {
      if (state[i].slug === slug) return state[i];
    }
    return null;
  }

  function ceilingFor(item) {
    return item.unique ? 1 : MAX_EDITION;
  }

  function add(item) {
    var existing = find(item.slug);
    if (existing) {
      var max = ceilingFor(existing);
      if (existing.qty >= max) {
        return { ok: false, reason: existing.unique ? 'unique' : 'max' };
      }
      existing.qty += 1;
    } else {
      item.qty = 1;
      state.push(item);
    }
    save();
    return { ok: true };
  }

  function remove(slug) {
    state = state.filter(function (i) { return i.slug !== slug; });
    save();
  }

  function setQty(slug, qty) {
    var item = find(slug);
    if (!item) return;
    var max = ceilingFor(item);
    item.qty = Math.max(1, Math.min(max, qty));
    save();
  }

  function clear() {
    state = [];
    save();
  }

  function money(n) {
    return '₨ ' + n.toLocaleString('en-US');
  }

  window.Cart = {
    items: function () { return state.slice(); },
    count: count,
    total: total,
    has: function (slug) { return !!find(slug); },
    add: add,
    remove: remove,
    setQty: setQty,
    clear: clear,
    money: money,
    MAX_EDITION: MAX_EDITION
  };
})();
