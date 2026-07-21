<?php
/**
 * @var array $errors
 * @var array $old
 */
$errors ??= [];
$old    ??= [];
$val    = static fn (string $k): string => (string) ($old[$k] ?? '');
$bad    = static fn (string $k): string => isset($errors[$k]) ? ' is-invalid' : '';

$subjects = ['General enquiry', 'Booking a package', 'Custom trip', 'Corporate retreat', 'Something else'];
?>
<form class="bba-form p-4"
      id="contact-form"
      method="post"
      action="<?= url_to('contact') ?>"
      hx-post="<?= url_to('contact') ?>"
      hx-target="#contact-form"
      hx-swap="outerHTML"
      aria-label="Contact form">
  <?= csrf_field() ?>

  <?php if ($errors !== []): ?>
    <div class="bba-alert bba-alert-error mb-3" role="alert">Please check the highlighted fields below.</div>
  <?php endif; ?>

  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label" for="c-name">Your name <span aria-hidden="true">*</span></label>
      <input class="form-control<?= $bad('name') ?>" id="c-name" name="name" type="text"
             value="<?= esc($val('name'), 'attr') ?>" placeholder="Jane Wanjiku" required>
      <?= view('partials/field_error', ['errors' => $errors, 'field' => 'name']) ?>
    </div>
    <div class="col-md-6">
      <label class="form-label" for="c-email">Email <span aria-hidden="true">*</span></label>
      <input class="form-control<?= $bad('email') ?>" id="c-email" name="email" type="email"
             value="<?= esc($val('email'), 'attr') ?>" placeholder="you@example.com" required>
      <?= view('partials/field_error', ['errors' => $errors, 'field' => 'email']) ?>
    </div>
    <div class="col-md-6">
      <label class="form-label" for="c-phone">Phone / WhatsApp</label>
      <input class="form-control<?= $bad('phone') ?>" id="c-phone" name="phone" type="tel"
             value="<?= esc($val('phone'), 'attr') ?>" placeholder="+254 7XX XXX XXX">
      <?= view('partials/field_error', ['errors' => $errors, 'field' => 'phone']) ?>
    </div>
    <div class="col-md-6">
      <label class="form-label" for="c-subject">Subject</label>
      <select class="form-select<?= $bad('subject') ?>" id="c-subject" name="subject">
        <?php foreach ($subjects as $subject): ?>
          <option value="<?= esc($subject, 'attr') ?>" <?= $val('subject') === $subject ? 'selected' : '' ?>><?= esc($subject) ?></option>
        <?php endforeach; ?>
      </select>
      <?= view('partials/field_error', ['errors' => $errors, 'field' => 'subject']) ?>
    </div>
    <div class="col-12">
      <label class="form-label" for="c-message">Message <span aria-hidden="true">*</span></label>
      <textarea class="form-control<?= $bad('message') ?>" id="c-message" name="message" rows="5"
                placeholder="How can we help?" required><?= esc($val('message')) ?></textarea>
      <?= view('partials/field_error', ['errors' => $errors, 'field' => 'message']) ?>
    </div>
  </div>

  <div class="bba-hp" aria-hidden="true">
    <label for="c-website">Leave this field empty</label>
    <input type="text" id="c-website" name="website" tabindex="-1" autocomplete="off">
  </div>

  <button class="btn btn-bba-green mt-4" type="submit">
    <span class="bba-btn-label">Send message</span>
    <span class="spinner-border spinner-border-sm ms-2 bba-btn-spin" aria-hidden="true"></span>
  </button>
</form>
