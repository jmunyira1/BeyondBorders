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
    <div class="col-lg-8 bba-article">
      <?= nl2paras($body) ?>
      <p class="text-body-secondary mt-4 mb-0">
        Questions? <a href="<?= url_to('contact') ?>">Get in touch</a> or email
        <a href="mailto:<?= esc(site('email'), 'attr') ?>"><?= esc(site('email')) ?></a>.
      </p>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
