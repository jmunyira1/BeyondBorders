<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<header class="bb-pagehead">
  <div class="container">
    <p class="bb-meta mb-3"><?= esc($eyebrow) ?></p>
    <h1><?= esc($heading) ?></h1>
  </div>
</header>

<section>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="bba-article">
          <?= nl2paras($body) ?>
        </div>
        <p class="text-body-secondary mt-5 mb-0">
          Questions about this policy? <a href="<?= url_to('contact') ?>">Get in touch</a> or email
          <a href="mailto:<?= esc(site('email'), 'attr') ?>"><?= esc(site('email')) ?></a>.
        </p>
      </div>
    </div>
  </div>
</section>

<?= view('partials/cta_band', ['ctaHeading' => 'Ready to plan your trip?']) ?>

<?= $this->endSection() ?>
