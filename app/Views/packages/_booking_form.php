<?php
/**
 * Booking enquiry form. Posts through htmx and replaces itself with either the
 * re-rendered form (validation errors) or the success panel.
 *
 * @var array $package
 * @var array $errors
 * @var array $old
 */
$errors ??= [];
$old    ??= [];
$val    = static fn (string $k, string $d = ''): string => (string) ($old[$k] ?? $d);
$bad    = static fn (string $k): string => isset($errors[$k]) ? ' is-invalid' : '';
?>
<form class="bba-form p-4"
      id="booking-form"
      method="post"
      action="<?= url_to('package-enquire', $package['slug']) ?>"
      hx-post="<?= url_to('package-enquire', $package['slug']) ?>"
      hx-target="#booking-form"
      hx-swap="outerHTML"
      aria-label="Booking enquiry">
  <?= csrf_field() ?>

  <h2 class="h5 mb-1">Enquire about this trip</h2>
  <p class="text-body-secondary small mb-4">No payment now — we'll confirm availability and send a written quote.</p>

  <?php if ($errors !== []): ?>
    <div class="bba-alert bba-alert-error mb-3" role="alert">
      Please check the highlighted fields below.
    </div>
  <?php endif; ?>

  <div class="mb-3">
    <label class="form-label" for="b-name">Your name <span aria-hidden="true">*</span></label>
    <input class="form-control<?= $bad('name') ?>" id="b-name" name="name" type="text"
           value="<?= esc($val('name'), 'attr') ?>" placeholder="Jane Wanjiku" required>
    <?= view('partials/field_error', ['errors' => $errors, 'field' => 'name']) ?>
  </div>

  <div class="mb-3">
    <label class="form-label" for="b-email">Email <span aria-hidden="true">*</span></label>
    <input class="form-control<?= $bad('email') ?>" id="b-email" name="email" type="email"
           value="<?= esc($val('email'), 'attr') ?>" placeholder="you@example.com" required>
    <?= view('partials/field_error', ['errors' => $errors, 'field' => 'email']) ?>
  </div>

  <div class="mb-3">
    <label class="form-label" for="b-phone">Phone / WhatsApp <span aria-hidden="true">*</span></label>
    <input class="form-control<?= $bad('phone') ?>" id="b-phone" name="phone" type="tel"
           value="<?= esc($val('phone'), 'attr') ?>" placeholder="+254 7XX XXX XXX" required>
    <?= view('partials/field_error', ['errors' => $errors, 'field' => 'phone']) ?>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-6">
      <label class="form-label" for="b-people">Travelers</label>
      <input class="form-control<?= $bad('people') ?>" id="b-people" name="people" type="number" min="1"
             value="<?= esc($val('people'), 'attr') ?>" placeholder="2">
      <?= view('partials/field_error', ['errors' => $errors, 'field' => 'people']) ?>
    </div>
    <div class="col-6">
      <label class="form-label" for="b-dates">Preferred dates</label>
      <input class="form-control<?= $bad('travel_dates') ?>" id="b-dates" name="travel_dates" type="text"
             value="<?= esc($val('travel_dates'), 'attr') ?>" placeholder="Aug 2026">
      <?= view('partials/field_error', ['errors' => $errors, 'field' => 'travel_dates']) ?>
    </div>
  </div>

  <div class="mb-4">
    <label class="form-label" for="b-message">Anything we should know?</label>
    <textarea class="form-control<?= $bad('message') ?>" id="b-message" name="message" rows="3"
              placeholder="Dietary needs, celebrations, accessibility…"><?= esc($val('message')) ?></textarea>
    <?= view('partials/field_error', ['errors' => $errors, 'field' => 'message']) ?>
  </div>

  <?php // Honeypot — hidden from people, tempting to bots. ?>
  <div class="bba-hp" aria-hidden="true">
    <label for="b-website">Leave this field empty</label>
    <input type="text" id="b-website" name="website" tabindex="-1" autocomplete="off">
  </div>

  <div class="d-grid gap-2">
    <button class="btn btn-bba-green" type="submit">
      <span class="bba-btn-label">Send enquiry</span>
      <span class="spinner-border spinner-border-sm ms-2 bba-btn-spin" aria-hidden="true"></span>
    </button>
    <a class="btn btn-bba-outline"
       href="<?= esc(whatsapp_link('Hi Beyond Borders, I am interested in the ' . $package['title'] . ' package.'), 'attr') ?>"
       target="_blank" rel="noopener">
      <i class="bi bi-whatsapp me-2" aria-hidden="true"></i>Ask on WhatsApp
    </a>
  </div>
</form>
