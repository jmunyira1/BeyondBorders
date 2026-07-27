/* Inline form validation — shared by the public site and the admin.

   Any <form data-validate> gets its fields checked as the visitor fills them
   in (on blur, then live once a field has been touched) and again on submit,
   which is blocked while anything is invalid. Errors are shown in the field's
   own .bba-field-error slot with the field marked .is-invalid — the same look
   the server produces, so client and server errors are indistinguishable.

   It uses event delegation, so forms swapped in by htmx are covered without
   re-initialising. The browser's own error bubbles are suppressed
   (form.noValidate) in favour of these inline messages. */
(function () {
  'use strict';

  // The off-screen spam honeypot must never be validated or focused.
  var SKIP = '[name="website"]';

  function fieldOf(el) {
    if (!el || !el.matches) return null;
    if (!el.matches('input, select, textarea')) return null;
    if (el.type === 'hidden' || el.type === 'submit' || el.type === 'button') return null;
    if (el.matches(SKIP)) return null;
    var form = el.closest('form[data-validate]');
    return form ? el : null;
  }

  function slotFor(field) {
    var wrap = field.closest('.mb-3, [class*="col-"], .bba-form') || field.parentNode;
    var slot = wrap.querySelector('.bba-field-error');
    if (!slot) {
      slot = document.createElement('span');
      slot.className = 'bba-field-error';
      field.insertAdjacentElement('afterend', slot);
    }
    return slot;
  }

  function messageFor(field) {
    var v = field.validity;
    if (v.valueMissing) {
      return field.dataset.msgRequired || 'This field is required.';
    }
    if (v.typeMismatch && field.type === 'email') {
      return 'Enter a valid email address, like name@example.com.';
    }
    if (v.typeMismatch && field.type === 'url') {
      return 'Enter a full web address, starting with https://.';
    }
    if (v.patternMismatch) {
      return field.dataset.msgPattern || 'Please match the requested format.';
    }
    if (v.tooShort) {
      return 'Use at least ' + field.minLength + ' characters.';
    }
    if (v.rangeUnderflow) {
      return 'Enter ' + field.min + ' or more.';
    }
    if (v.stepMismatch) {
      return 'Enter a whole number.';
    }
    return field.validationMessage || 'Please check this field.';
  }

  function check(field, markTouched) {
    if (markTouched) field.dataset.touched = '1';

    var ok = field.checkValidity();
    field.classList.toggle('is-invalid', !ok);

    var slot = slotFor(field);
    if (ok) {
      slot.textContent = '';
      slot.hidden = true;
      field.removeAttribute('aria-invalid');
    } else {
      slot.textContent = messageFor(field);
      slot.hidden = false;
      field.setAttribute('aria-invalid', 'true');
    }
    return ok;
  }

  // Blur doesn't bubble, so listen in the capture phase.
  document.addEventListener('blur', function (e) {
    var field = fieldOf(e.target);
    if (field) check(field, true);
  }, true);

  // Once a field has been flagged, keep it honest as the visitor edits.
  function live(e) {
    var field = fieldOf(e.target);
    if (field && field.dataset.touched === '1') check(field, false);
  }
  document.addEventListener('input', live);
  document.addEventListener('change', live);

  // Capture phase so this runs before htmx's own submit handler and can stop it.
  document.addEventListener('submit', function (e) {
    var form = e.target;
    if (!form.matches || !form.matches('form[data-validate]')) return;

    var firstBad = null;
    form.querySelectorAll('input, select, textarea').forEach(function (el) {
      var field = fieldOf(el);
      if (!field) return;
      if (!check(field, true) && !firstBad) firstBad = field;
    });

    if (firstBad) {
      e.preventDefault();
      e.stopImmediatePropagation();
      firstBad.focus({ preventScroll: true });
      firstBad.scrollIntoView({ block: 'center', behavior: 'smooth' });
    }
  }, true);

  // Hand error display to us, not the browser's native bubbles.
  function disableNative(root) {
    (root.matches && root.matches('form[data-validate]') ? [root] : [])
      .concat(Array.prototype.slice.call(
        (root.querySelectorAll && root.querySelectorAll('form[data-validate]')) || []
      ))
      .forEach(function (f) { f.noValidate = true; });
  }
  disableNative(document);
  document.body.addEventListener('htmx:load', function (e) {
    if (e.target) disableNative(e.target);
  });
})();
