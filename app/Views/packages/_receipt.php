<?php
/**
 * Booking receipt / confirmation. Swapped in place of the booking form after a
 * successful submission (htmx outerHTML swap on #booking-form).
 *
 * Placeholder flow: this records the request and shows how to pay — no card is
 * charged here. It is a booking-request confirmation, not a payment receipt.
 *
 * @var array  $package
 * @var string $reference
 * @var string $name
 * @var string $email
 * @var string $phone
 * @var string $people
 * @var string $dates
 */
$payInfo = trim((string) site('paymentInstructions'));
?>
<div class="bba-form bb-receipt p-4 p-lg-5" role="status" aria-live="polite">
  <div class="bb-receipt__head">
    <div>
      <p class="bb-meta mb-1">Booking received</p>
      <h2 class="h4 mb-0"><?= esc($package['title']) ?></h2>
    </div>
    <i class="bi bi-check-circle bb-receipt__tick" aria-hidden="true"></i>
  </div>

  <p class="bb-receipt__ref">Reference <strong><?= esc($reference) ?></strong></p>

  <dl class="bb-receipt__lines">
    <div>
      <dt>Price</dt>
      <dd class="tabular-nums"><?= esc(money($package['price'], $package['currency'])) ?> <span class="bb-receipt__per">per person</span></dd>
    </div>
    <?php if ($people !== ''): ?>
      <div><dt>Travellers</dt><dd class="tabular-nums"><?= esc($people) ?></dd></div>
    <?php endif; ?>
    <?php if ($dates !== ''): ?>
      <div><dt>Preferred dates</dt><dd><?= esc($dates) ?></dd></div>
    <?php endif; ?>
    <div><dt>Name</dt><dd><?= esc($name) ?></dd></div>
    <div><dt>Email</dt><dd class="bb-receipt__wrap"><?= esc($email) ?></dd></div>
    <div><dt>Phone</dt><dd><?= esc($phone) ?></dd></div>
    <div><dt>Received</dt><dd><?= esc(date('j M Y, H:i')) ?></dd></div>
    <?php if (! empty($package['deposit_amount']) && (float) $package['deposit_amount'] > 0): ?>
      <div><dt>Deposit to book</dt><dd class="tabular-nums"><?= esc(money($package['deposit_amount'], $package['currency'])) ?></dd></div>
    <?php endif; ?>
    <div><dt>Payment</dt><dd>To be arranged</dd></div>
  </dl>

  <div class="bb-receipt__pay">
    <p class="bb-meta mb-2">How to pay</p>
    <?php if ($payInfo !== ''): ?>
      <?= nl2paras($payInfo, 'mb-2') ?>
    <?php else: ?>
      <p class="mb-0 text-body-secondary">We'll confirm availability within 24 hours and send payment details (M-Pesa or bank transfer) with your quote. No payment is due yet.</p>
    <?php endif; ?>
  </div>

  <div class="bb-receipt__actions d-flex flex-wrap gap-2">
    <button type="button" class="btn btn-bba-green" data-print>
      <i class="bi bi-printer me-2" aria-hidden="true"></i>Print / save receipt
    </button>
    <a href="<?= url_to('packages') ?>" class="btn btn-bba-outline">Browse more trips</a>
  </div>

  <p class="bb-receipt__fineprint">This is a booking-request confirmation, not a payment receipt.
    <?= esc(site('companyName')) ?> will confirm availability before any payment is made.</p>
</div>
