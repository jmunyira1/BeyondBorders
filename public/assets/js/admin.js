/* Admin behaviour: sidebar, htmx wiring, toasts, upload previews. */
(function () {
  'use strict';

  /* ---------------- Sidebar (mobile) ---------------- */
  var side = document.getElementById('bba-side');
  var backdrop = document.getElementById('bba-side-backdrop');
  var toggle = document.getElementById('bba-side-toggle');

  function closeSide() {
    side.classList.remove('open');
    backdrop.classList.remove('show');
  }
  if (toggle) {
    toggle.addEventListener('click', function () {
      side.classList.toggle('open');
      backdrop.classList.toggle('show');
    });
  }
  if (backdrop) backdrop.addEventListener('click', closeSide);

  /* ---------------- CSRF on every htmx request ---------------- */
  document.body.addEventListener('htmx:configRequest', function (evt) {
    var meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) evt.detail.parameters['csrf_token'] = meta.content;
  });

  /* ---------------- Toasts ----------------
     Controllers signal results with an HX-Trigger header carrying
     {"bba:toast": {"message": "...", "type": "success"}}. */
  function toast(message, type) {
    var box = document.getElementById('bba-toasts');
    if (!box) return;
    var el = document.createElement('div');
    el.className = 'bba-toast bba-toast-' + (type === 'error' ? 'error' : 'success');
    el.textContent = message;
    box.appendChild(el);
    window.setTimeout(function () {
      el.style.opacity = '0';
      window.setTimeout(function () { el.remove(); }, 200);
    }, 3200);
  }

  document.body.addEventListener('bba:toast', function (e) {
    var d = e.detail || {};
    toast(d.message || 'Saved', d.type);
  });

  document.body.addEventListener('htmx:responseError', function (e) {
    toast('Something went wrong (' + e.detail.xhr.status + '). Please try again.', 'error');
  });

  /* ---------------- Confirm before destructive actions ---------------- */
  document.body.addEventListener('htmx:confirm', function (e) {
    var msg = e.detail.elt.getAttribute('data-confirm');
    if (!msg) return;
    e.preventDefault();
    if (window.confirm(msg)) e.detail.issueRequest();
  });

  /* ---------------- Slug auto-fill ----------------
     Only while the slug field is untouched, so editing an existing row never
     silently changes a live URL. */
  document.body.addEventListener('input', function (e) {
    if (!e.target.matches('[data-slug-source]')) return;
    var form = e.target.closest('form');
    var slug = form && form.querySelector('[data-slug-target]');
    if (!slug || slug.dataset.touched === '1') return;
    slug.value = e.target.value
      .toLowerCase()
      .replace(/&/g, ' and ')
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-+|-+$/g, '')
      .slice(0, 140);
  });
  document.body.addEventListener('input', function (e) {
    if (e.target.matches('[data-slug-target]')) e.target.dataset.touched = '1';
  });

  /* ---------------- Image preview before upload ---------------- */
  document.body.addEventListener('change', function (e) {
    if (!e.target.matches('input[type="file"][data-preview]')) return;
    var img = document.querySelector(e.target.dataset.preview);
    var file = e.target.files && e.target.files[0];
    if (img && file) img.src = URL.createObjectURL(file);
  });

  /* ---------------- Modal helper ----------------
     Panels loaded over htmx into #bba-modal-body open the shared modal. */
  document.body.addEventListener('htmx:afterSwap', function (e) {
    if (e.detail.target && e.detail.target.id === 'bba-modal-body') {
      var el = document.getElementById('bba-modal');
      if (el) bootstrap.Modal.getOrCreateInstance(el).show();
    }
  });

  // Controllers close the modal by sending HX-Trigger: bba:close-modal
  document.body.addEventListener('bba:close-modal', function () {
    var el = document.getElementById('bba-modal');
    if (el) {
      var inst = bootstrap.Modal.getInstance(el);
      if (inst) inst.hide();
    }
  });
})();
