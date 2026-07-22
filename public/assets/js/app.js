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
     Search suggestions — instant, client-side.

     The whole catalogue (a few KB) is embedded in the page as
     JSON, so filtering happens locally on every keystroke: no
     network, no debounce, no waiting. Without JavaScript the
     input is a plain text field and the form submits normally.
     ---------------------------------------------------------- */
  (function searchSuggest() {
    var indexEl = document.getElementById('bb-search-index');
    if (!indexEl) return;

    var index;
    try {
      index = JSON.parse(indexEl.textContent);
    } catch (e) {
      return; // Malformed index: leave the plain input alone.
    }
    if (!Array.isArray(index) || index.length === 0) return;

    var MIN_CHARS = 2;
    var MAX_RESULTS = 8;

    document.querySelectorAll('.bb-suggest-wrap').forEach(function (wrap) {
      var input = wrap.querySelector('input[role="combobox"]');
      var box = wrap.querySelector('.bb-suggest');
      if (!input || !box) return;

      var activeIndex = -1;

      /* Keep the panel inside the visible viewport. On phones the search sits
         low on the page and the on-screen keyboard eats the bottom half, so a
         fixed 21rem panel would open almost entirely off-screen. Measure the
         real space (visualViewport accounts for the keyboard) and either cap
         the height or flip the panel above the field. */
      function place() {
        var vh = (window.visualViewport && window.visualViewport.height) || window.innerHeight;
        var rect = input.getBoundingClientRect();
        var GUTTER = 12;
        // Clamp to the visible band so a field scrolled out of view can't
        // report more room than the viewport actually has.
        var below = vh - Math.max(rect.bottom, 0) - GUTTER;
        var above = Math.min(rect.top, vh) - GUTTER;
        var flip = below < 160 && above > below;

        box.classList.toggle('is-above', flip);
        box.style.setProperty('--bb-suggest-max', Math.max(120, flip ? above : below) + 'px');
      }

      function setExpanded(open) {
        box.hidden = !open;
        input.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open) {
          place();
        } else {
          activeIndex = -1;
          input.removeAttribute('aria-activedescendant');
          box.classList.remove('is-above');
        }
      }

      function options() {
        return box.querySelectorAll('[role="option"]');
      }

      function setActive(i) {
        var opts = options();
        activeIndex = i;
        input.removeAttribute('aria-activedescendant');
        opts.forEach(function (opt, n) {
          var on = n === i;
          opt.classList.toggle('is-active', on);
          opt.setAttribute('aria-selected', on ? 'true' : 'false');
          if (on) {
            input.setAttribute('aria-activedescendant', opt.id);
            opt.scrollIntoView({ block: 'nearest' });
          }
        });
      }

      // Whole-word-start matches rank above mid-word ones, so typing
      // "mara" puts "Maasai Mara Safari" above a passing mention.
      function search(q) {
        var needle = q.toLowerCase();
        var scored = [];

        for (var i = 0; i < index.length; i++) {
          var entry = index[i];
          var at = entry.search.indexOf(needle);
          if (at === -1) continue;

          var label = entry.label.toLowerCase();
          var score = 2;
          if (label.indexOf(needle) === 0) score = 0;          // label starts with it
          else if (label.indexOf(needle) !== -1) score = 1;     // appears in the label
          else if (at === 0 || entry.search[at - 1] === ' ') score = 1.5;

          scored.push({ entry: entry, score: score, at: at });
        }

        scored.sort(function (a, b) { return a.score - b.score || a.at - b.at; });
        return scored.slice(0, MAX_RESULTS).map(function (s) { return s.entry; });
      }

      function esc(str) {
        return String(str).replace(/[&<>"]/g, function (c) {
          return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c];
        });
      }

      function render(results, q) {
        if (results.length === 0) {
          box.innerHTML =
            '<div class="bb-suggest__empty" role="status">' +
              '<p class="mb-1">We don\'t have a ready-made trip for “' + esc(q) + '” yet.</p>' +
              '<p class="mb-2 bb-suggest__hint">Tell us what you have in mind and we\'ll plan it — or see everything that\'s ready to book.</p>' +
              '<a href="' + esc(wrap.dataset.customUrl) + '" id="sg-opt-0" role="option" class="bb-suggest__option">Plan a custom trip&nbsp;→</a>' +
              '<a href="' + esc(wrap.dataset.packagesUrl) + '" id="sg-opt-1" role="option" class="bb-suggest__option">View all packages&nbsp;→</a>' +
            '</div>';
          return;
        }

        var html = '<ul class="bb-suggest__list" role="presentation">';
        var group = '';
        var n = 0;

        results.forEach(function (entry) {
          if (entry.group !== group) {
            group = entry.group;
            html += '<li class="bb-suggest__group" role="presentation">' + esc(group) + '</li>';
          }
          html += '<li role="presentation">' +
            '<a href="' + esc(entry.url) + '" id="sg-opt-' + (n++) + '" role="option" class="bb-suggest__option">' +
              '<span class="bb-suggest__name">' + esc(entry.label) + '</span>' +
              (entry.meta ? '<span class="bb-suggest__meta">' + esc(entry.meta) + '</span>' : '') +
            '</a></li>';
        });

        box.innerHTML = html + '</ul>';
      }

      function update() {
        var q = input.value.trim();
        if (q.length < MIN_CHARS) {
          setExpanded(false);
          return;
        }
        render(search(q), q);
        setExpanded(true);
        setActive(-1);
      }

      // No debounce: the work is a filter over a few dozen rows.
      input.addEventListener('input', update);

      input.addEventListener('focus', function () {
        if (input.value.trim().length >= MIN_CHARS) update();
      });

      input.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { setExpanded(false); return; }

        var opts = options();
        if (box.hidden || opts.length === 0) return;

        if (e.key === 'ArrowDown') {
          e.preventDefault();
          setActive((activeIndex + 1) % opts.length);
        } else if (e.key === 'ArrowUp') {
          e.preventDefault();
          setActive((activeIndex - 1 + opts.length) % opts.length);
        } else if (e.key === 'Enter' && activeIndex >= 0) {
          e.preventDefault(); // A highlighted suggestion beats submitting.
          opts[activeIndex].click();
        } else if (e.key === 'Tab') {
          setExpanded(false);
        }
      });

      document.addEventListener('click', function (e) {
        if (!wrap.contains(e.target)) setExpanded(false);
      });

      // Re-measure while the panel is open: scrolling, rotation, and the
      // on-screen keyboard all change how much room there is.
      ['scroll', 'resize'].forEach(function (evt) {
        window.addEventListener(evt, function () {
          if (!box.hidden) place();
        }, { passive: true });
      });
      if (window.visualViewport) {
        ['resize', 'scroll'].forEach(function (evt) {
          window.visualViewport.addEventListener(evt, function () {
            if (!box.hidden) place();
          });
        });
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
