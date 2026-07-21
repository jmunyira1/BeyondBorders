<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<header class="bb-pagehead">
  <div class="container">
    <h1>Your journey, designed around you</h1>
    <p class="bb-lede">Tell us the occasion, the group and the budget — we plan the whole thing end to end.</p>
  </div>
</header>

<!-- How it works — numbered step sequence (F4): genuinely ordinal -->
<section>
  <div class="container">
    <div class="row">
      <div class="col-lg-4">
        <h2 class="mb-4">Three steps to your trip</h2>
      </div>
      <div class="col-lg-8">
        <div class="row g-4">
          <div class="col-md-4">
            <div class="bb-step">
              <span class="num">01</span>
              <h3>Tell us your plan</h3>
              <p class="text-body-secondary mb-0">Where, when, how many people and roughly what budget — through the form below, WhatsApp or a call.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="bb-step">
              <span class="num">02</span>
              <h3>Get your itinerary &amp; quote</h3>
              <p class="text-body-secondary mb-0">Within 24 hours we send a day-by-day itinerary with a clear, all-inclusive price. Adjust it until it fits.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="bb-step">
              <span class="num">03</span>
              <h3>Confirm and travel</h3>
              <p class="text-body-secondary mb-0">Pay securely by M-Pesa or bank transfer. We handle transport, stays and activities — you just show up.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-sand">
  <div class="container">
    <div class="row g-4 g-lg-5 align-items-start">
      <div class="col-lg-5">
        <h2 class="mb-4">Any occasion, any group size</h2>
        <ul class="list-unstyled d-grid gap-3">
          <li class="d-flex gap-3"><i class="bi bi-binoculars" aria-hidden="true"></i>Private and family safaris, at your own pace</li>
          <li class="d-flex gap-3"><i class="bi bi-heart" aria-hidden="true"></i>Honeymoons and anniversary escapes</li>
          <li class="d-flex gap-3"><i class="bi bi-people" aria-hidden="true"></i>Corporate staff retreats and team-building</li>
          <li class="d-flex gap-3"><i class="bi bi-calendar-event" aria-hidden="true"></i>Cultural events, weddings and celebrations</li>
          <li class="d-flex gap-3"><i class="bi bi-backpack" aria-hidden="true"></i>Group getaways, chamas and student trips</li>
          <li class="d-flex gap-3"><i class="bi bi-signpost-2" aria-hidden="true"></i>Mountain treks and multi-day adventures</li>
        </ul>
        <p class="text-body-secondary mt-4 mb-0">Prefer to talk it through?
          <a href="#" data-wa-open="I'd like to plan a custom trip.">WhatsApp us</a> or call
          <a href="tel:<?= esc(site('phoneLink'), 'attr') ?>"><?= esc(site('phone')) ?></a>.
        </p>
      </div>
      <div class="col-lg-7">
        <?= view('pages/_custom_trip_form') ?>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
