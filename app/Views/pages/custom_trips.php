<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<header class="bb-pagehead">
  <div class="container">
    <h1><?= esc(site('customHeroHeading')) ?></h1>
    <p class="bb-lede"><?= esc(site('customHeroLede')) ?></p>
  </div>
</header>

<!-- How it works — numbered step sequence (F4): genuinely ordinal -->
<section>
  <div class="container">
    <div class="row">
      <div class="col-lg-4">
        <p class="bb-meta mb-1">How it works</p>
        <h2 class="mb-4">Three steps to your trip</h2>
      </div>
      <div class="col-lg-8">
        <div class="row g-4">
          <?php foreach ([
              ['01', site('homeStep1Title'), site('homeStep1Body')],
              ['02', site('homeStep2Title'), site('homeStep2Body')],
              ['03', site('homeStep3Title'), site('homeStep3Body')],
          ] as [$num, $stepTitle, $stepBody]): ?>
            <div class="col-md-4">
              <div class="bb-step">
                <span class="num"><?= $num ?></span>
                <h3><?= esc($stepTitle) ?></h3>
                <p class="text-body-secondary mb-0"><?= esc($stepBody) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-sand">
  <div class="container">
    <div class="row g-4 g-lg-5 align-items-start">
      <div class="col-lg-5">
        <p class="bb-meta mb-1"><?= esc(site('customPlanEyebrow')) ?></p>
        <h2 class="mb-4"><?= esc(site('customPlanHeading')) ?></h2>
        <ul class="list-unstyled d-grid gap-3">
          <?php foreach ([
              ['bi-binoculars',     site('customPlan1')],
              ['bi-heart',          site('customPlan2')],
              ['bi-people',         site('customPlan3')],
              ['bi-calendar-event', site('customPlan4')],
              ['bi-backpack',       site('customPlan5')],
              ['bi-signpost-2',     site('customPlan6')],
          ] as [$icon, $planItem]): ?>
            <?php if (trim((string) $planItem) === '') { continue; } ?>
            <li class="d-flex gap-3"><i class="bi <?= $icon ?>" aria-hidden="true"></i><?= esc($planItem) ?></li>
          <?php endforeach; ?>
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
