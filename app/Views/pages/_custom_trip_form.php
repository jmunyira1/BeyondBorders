<?php
/**
 * @var array $errors
 * @var array $old
 */
$errors ??= [];
$old    ??= [];
$val    = static fn (string $k): string => (string) ($old[$k] ?? '');
$bad    = static fn (string $k): string => isset($errors[$k]) ? ' is-invalid' : '';

$tripTypes = ['Safari', 'Beach holiday', 'Adventure / trekking', 'Cultural experience', 'Honeymoon', 'Corporate retreat', 'Family holiday', 'Something else'];
$budgets   = ['Under KES 10,000 per person', 'KES 10,000 – 30,000 per person', 'KES 30,000 – 60,000 per person', 'KES 60,000+ per person', 'Not sure yet'];
?>
<form class="bba-form p-4 p-lg-5"
      id="custom-trip-form"
      method="post"
      action="<?= url_to('custom-trips') ?>"
      hx-post="<?= url_to('custom-trips') ?>"
      hx-target="#custom-trip-form"
      hx-swap="outerHTML"
      data-validate
      aria-label="Custom trip enquiry">
  <?= csrf_field() ?>

  <h2 class="h4 mb-4">Start with a free itinerary &amp; quote</h2>

  <?php if ($errors !== []): ?>
    <div class="bba-alert bba-alert-error mb-3" role="alert">Please check the highlighted fields below.</div>
  <?php endif; ?>

  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label" for="ct-name">Your name <span aria-hidden="true">*</span></label>
      <input class="form-control<?= $bad('name') ?>" id="ct-name" name="name" type="text"
             value="<?= esc($val('name'), 'attr') ?>" placeholder="Jane Wanjiku" required>
      <?= view('partials/field_error', ['errors' => $errors, 'field' => 'name']) ?>
    </div>
    <div class="col-md-6">
      <label class="form-label" for="ct-phone">Phone / WhatsApp <span aria-hidden="true">*</span></label>
      <input class="form-control<?= $bad('phone') ?>" id="ct-phone" name="phone" type="tel"
             value="<?= esc($val('phone'), 'attr') ?>" placeholder="+254 7XX XXX XXX" required>
      <?= view('partials/field_error', ['errors' => $errors, 'field' => 'phone']) ?>
    </div>
    <div class="col-md-6">
      <label class="form-label" for="ct-email">Email</label>
      <input class="form-control<?= $bad('email') ?>" id="ct-email" name="email" type="email"
             value="<?= esc($val('email'), 'attr') ?>" placeholder="you@example.com">
      <?= view('partials/field_error', ['errors' => $errors, 'field' => 'email']) ?>
    </div>
    <div class="col-md-6">
      <label class="form-label" for="ct-type">Trip type</label>
      <select class="form-select<?= $bad('trip_type') ?>" id="ct-type" name="trip_type">
        <option value="">Choose one…</option>
        <?php foreach ($tripTypes as $type): ?>
          <option value="<?= esc($type, 'attr') ?>" <?= $val('trip_type') === $type ? 'selected' : '' ?>><?= esc($type) ?></option>
        <?php endforeach; ?>
      </select>
      <?= view('partials/field_error', ['errors' => $errors, 'field' => 'trip_type']) ?>
    </div>
    <div class="col-md-6">
      <label class="form-label" for="ct-people">Number of people</label>
      <input class="form-control<?= $bad('people') ?>" id="ct-people" name="people" type="number" min="1"
             value="<?= esc($val('people'), 'attr') ?>" placeholder="4">
      <?= view('partials/field_error', ['errors' => $errors, 'field' => 'people']) ?>
    </div>
    <div class="col-md-6">
      <label class="form-label" for="ct-dates">Preferred dates</label>
      <input class="form-control<?= $bad('travel_dates') ?>" id="ct-dates" name="travel_dates" type="text"
             value="<?= esc($val('travel_dates'), 'attr') ?>" placeholder="Aug 2026">
      <?= view('partials/field_error', ['errors' => $errors, 'field' => 'travel_dates']) ?>
    </div>
    <div class="col-12">
      <label class="form-label" for="ct-budget">Budget</label>
      <select class="form-select<?= $bad('budget') ?>" id="ct-budget" name="budget">
        <option value="">Choose one…</option>
        <?php foreach ($budgets as $budget): ?>
          <option value="<?= esc($budget, 'attr') ?>" <?= $val('budget') === $budget ? 'selected' : '' ?>><?= esc($budget) ?></option>
        <?php endforeach; ?>
      </select>
      <?= view('partials/field_error', ['errors' => $errors, 'field' => 'budget']) ?>
    </div>
    <div class="col-12">
      <label class="form-label" for="ct-notes">Tell us more</label>
      <textarea class="form-control<?= $bad('message') ?>" id="ct-notes" name="message" rows="4"
                placeholder="The occasion, places you'd love to see, anything we should know…"><?= esc($val('message')) ?></textarea>
      <?= view('partials/field_error', ['errors' => $errors, 'field' => 'message']) ?>
    </div>
  </div>

  <div class="bba-hp" aria-hidden="true">
    <label for="ct-website">Leave this field empty</label>
    <input type="text" id="ct-website" name="website" tabindex="-1" autocomplete="off">
  </div>

  <button class="btn btn-bba-green mt-4" type="submit">
    <span class="bba-btn-label">Send my enquiry</span>
    <span class="spinner-border spinner-border-sm ms-2 bba-btn-spin" aria-hidden="true"></span>
  </button>
</form>
