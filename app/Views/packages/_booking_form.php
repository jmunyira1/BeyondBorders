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

$avail   = $package['availability'] ?? 'available';
$deposit = $package['deposit_amount'] ?? null;
// Load once here so the form works both on first render and on error re-render.
$departures = $departures ?? (new \App\Models\PackageDepartureModel())->upcoming((int) ($package['id'] ?? 0));
if ($avail === 'sold_out') {
    $heading = 'Join the waitlist';
    $intro   = "This trip is fully booked — leave your details and we'll tell you the moment a spot opens up.";
    $submit  = 'Join the waitlist';
} elseif ($avail === 'coming_soon') {
    $heading = 'Register your interest';
    $intro   = 'This trip opens for booking soon — be the first to know when it does.';
    $submit  = 'Register interest';
} else {
    $heading = 'Enquire about this trip';
    $intro   = "No payment now — we'll confirm availability and send a written quote.";
    $submit  = 'Send enquiry';
}
?>
<form class="bba-form p-4"
      id="booking-form"
      method="post"
      action="<?= url_to('package-enquire', $package['slug']) ?>"
      hx-post="<?= url_to('package-enquire', $package['slug']) ?>"
      hx-target="#booking-form"
      hx-swap="outerHTML"
      data-validate
      aria-label="Booking enquiry">
  <?= csrf_field() ?>

  <h2 class="h5 mb-1"><?= esc($heading) ?></h2>
  <p class="text-body-secondary small mb-3"><?= esc($intro) ?></p>

  <?php if ($avail !== 'sold_out' && $avail !== 'coming_soon' && $deposit !== null && (float) $deposit > 0): ?>
    <div class="bba-alert bb-deposit-note mb-4">
      <i class="bi bi-shield-check me-1" aria-hidden="true"></i>
      Secure your spot with a <strong><?= esc(money($deposit, $package['currency'])) ?></strong> deposit. We'll send payment details once we confirm availability.
    </div>
  <?php endif; ?>

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
      <?php if ($departures !== []): ?>
        <label class="form-label" for="b-departure">Departure</label>
        <select class="form-select" id="b-departure" name="departure_id">
          <option value="">Flexible / any date</option>
          <?php foreach ($departures as $dep):
              $full  = $dep['spots'] !== null && (int) $dep['spots'] <= 0;
              $label = date('j M Y', strtotime($dep['depart_date']));
              if ($dep['return_date']) {
                  $label .= ' – ' . date('j M Y', strtotime($dep['return_date']));
              }
              if ($dep['spots'] !== null) {
                  $label .= $full ? ' — full' : ' — ' . (int) $dep['spots'] . ' left';
              }
          ?>
            <option value="<?= (int) $dep['id'] ?>" <?= $full ? 'disabled' : '' ?> <?= (string) $val('departure_id') === (string) $dep['id'] ? 'selected' : '' ?>><?= esc($label) ?></option>
          <?php endforeach; ?>
        </select>
      <?php else: ?>
        <label class="form-label" for="b-dates">Preferred dates</label>
        <input class="form-control<?= $bad('travel_dates') ?>" id="b-dates" name="travel_dates" type="text"
               value="<?= esc($val('travel_dates'), 'attr') ?>" placeholder="Aug 2026">
        <?= view('partials/field_error', ['errors' => $errors, 'field' => 'travel_dates']) ?>
      <?php endif; ?>
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
      <span class="bba-btn-label"><?= esc($submit) ?></span>
      <span class="spinner-border spinner-border-sm ms-2 bba-btn-spin" aria-hidden="true"></span>
    </button>
    <a class="btn btn-bba-outline"
       href="<?= esc(whatsapp_link('Hi Beyond Borders, I am interested in the ' . $package['title'] . ' package.'), 'attr') ?>"
       target="_blank" rel="noopener">
      <i class="bi bi-whatsapp me-2" aria-hidden="true"></i>Ask on WhatsApp
    </a>
  </div>
</form>
