/* Beyond Borders Adventures — front-end behaviour.
   Everything here is progressive: the site works without it, this just makes
   it pleasant. */
(function () {
  'use strict';

  /* ----------------------------------------------------------
     WhatsApp chat card
     ---------------------------------------------------------- */
  (function whatsappWidget() {
    var root = document.querySelector('.bba-wa');
    if (!root) return;

    var toggle = root.querySelector('#bba-wa-toggle');
    var panel = root.querySelector('#bba-wa-panel');
    var form = root.querySelector('[data-wa-form]');
    var input = root.querySelector('.bba-wa-input');
    var closeBtn = root.querySelector('[data-wa-close]');
    var number = root.dataset.waNumber || '';
    var prefill = root.dataset.waPrefill || '';

    function open() {
      panel.hidden = false;
      toggle.setAttribute('aria-expanded', 'true');
      // Only steal focus on pointer-capable screens; on mobile this would
      // pop the keyboard over the greeting the visitor is meant to read.
      if (window.matchMedia('(min-width: 768px)').matches) {
        window.setTimeout(function () { input.focus(); }, 60);
      }
    }

    function close() {
      panel.hidden = true;
      toggle.setAttribute('aria-expanded', 'false');
    }

    toggle.addEventListener('click', function () {
      panel.hidden ? open() : close();
    });

    if (closeBtn) closeBtn.addEventListener('click', close);

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !panel.hidden) {
        close();
        toggle.focus();
      }
    });

    document.addEventListener('click', function (e) {
      if (!panel.hidden && !root.contains(e.target)) close();
    });

    // Grow the textarea with its content, up to the CSS max-height.
    input.addEventListener('input', function () {
      input.style.height = 'auto';
      input.style.height = input.scrollHeight + 'px';
    });

    // Enter sends, Shift+Enter makes a new line — the WhatsApp convention.
    input.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        form.requestSubmit();
      }
    });

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var typed = input.value.trim();
      var message = typed ? (prefill ? prefill + '\n\n' + typed : typed) : prefill;
      var url = 'https://wa.me/' + number.replace(/\D+/g, '');
      if (message) url += '?text=' + encodeURIComponent(message);

      window.open(url, '_blank', 'noopener');

      input.value = '';
      input.style.height = 'auto';
      close();
    });

    // If a package page asked for a specific opening message, use it.
    document.addEventListener('click', function (e) {
      var trigger = e.target.closest('[data-wa-open]');
      if (!trigger) return;
      e.preventDefault();
      var msg = trigger.getAttribute('data-wa-open');
      if (msg) input.value = msg;
      open();
    });
  })();

  /* ----------------------------------------------------------
     Packages filter
     ---------------------------------------------------------- */
  (function packagesFilter() {
    var results = document.getElementById('packages-results');
    if (!results) return;

    // htmx fires these on the element issuing the request; we dim the results
    // region regardless of which control triggered the swap.
    document.body.addEventListener('htmx:beforeRequest', function (e) {
      if (e.detail.target && e.detail.target.id === 'packages-results') {
        results.classList.add('bba-loading');
      }
    });

    ['htmx:afterOnLoad', 'htmx:responseError', 'htmx:sendError'].forEach(function (evt) {
      document.body.addEventListener(evt, function () {
        results.classList.remove('bba-loading');
      });
    });

    // After a filter swap, bring the results back into view on small screens
    // where the grid may have scrolled out from under the filter bar.
    document.body.addEventListener('htmx:afterSwap', function (e) {
      if (e.detail.target && e.detail.target.id === 'packages-results') {
        var top = results.getBoundingClientRect().top;
        if (top < 0) {
          results.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      }
    });
  })();

  /* ----------------------------------------------------------
     Forms — disable submit while a request is in flight
     ---------------------------------------------------------- */
  document.body.addEventListener('htmx:beforeRequest', function (e) {
    var btn = e.detail.elt.querySelector ? e.detail.elt.querySelector('[type="submit"]') : null;
    if (btn) btn.disabled = true;
  });
  document.body.addEventListener('htmx:afterRequest', function (e) {
    var btn = e.detail.elt.querySelector ? e.detail.elt.querySelector('[type="submit"]') : null;
    if (btn) btn.disabled = false;
  });
})();
