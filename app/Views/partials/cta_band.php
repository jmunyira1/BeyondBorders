<?php
/**
 * Shared closing CTA band — the site-wide "how it works" call to action.
 * Presents the three engagement paths consistently on every page:
 *   1. Tell us your preferences  → Plan a custom trip
 *   2. Buy a ticket for a planned trip → packages
 *   3. Talk to us now → WhatsApp / email / phone
 *
 * Optional per-page overrides: $ctaHeading, $ctaText.
 */
$ctaHeading ??= 'Ready to make memories in Kenya?';
$ctaText    ??= "Tell us your preferences and we'll tailor-make a trip — or buy a ticket for a journey that's already planned.";
?>
<section class="bb-band">
  <div class="container">
    <h2><?= esc($ctaHeading) ?></h2>
    <p><?= esc($ctaText) ?></p>
    <div class="d-flex flex-wrap gap-3 mb-4">
      <a href="<?= url_to('custom-trips') ?>" class="btn btn-bba-gold">Plan a custom trip</a>
      <a href="<?= url_to('packages') ?>" class="btn btn-bba-outline-light">Buy a ticket</a>
      <button type="button" class="btn btn-bba-outline-light" data-wa-open="Hi, I'd like to plan a trip.">
        <i class="bi bi-whatsapp me-2" aria-hidden="true"></i>WhatsApp
      </button>
    </div>
    <p class="bb-meta mb-0 d-flex flex-wrap gap-3">
      <a href="mailto:<?= esc(site('email'), 'attr') ?>"><i class="bi bi-envelope me-2" aria-hidden="true"></i><?= esc(site('email')) ?></a>
      <a href="tel:<?= esc(site('phoneLink'), 'attr') ?>"><i class="bi bi-telephone me-2" aria-hidden="true"></i><?= esc(site('phone')) ?></a>
    </p>
  </div>
</section>
