<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<header class="bb-pagehead">
  <div class="container">
    <h1><?= esc(site('aboutHeroHeading')) ?></h1>
    <p class="bb-lede"><?= esc(site('aboutHeroLede')) ?></p>
  </div>
</header>

<!-- Story — prose beside an image pair, left-biased -->
<section>
  <div class="container">
    <div class="row g-4 g-lg-5 align-items-center">
      <div class="col-lg-6">
        <p class="bb-meta mb-1"><?= esc(site('aboutStoryEyebrow')) ?></p>
        <h2 class="mb-4"><?= esc(site('aboutStoryHeading')) ?></h2>
        <?= nl2paras(site('aboutStoryBody')) ?>
        <?= nl2paras(site('introBody')) ?>
      </div>
      <div class="col-lg-5 offset-lg-1">
        <div class="row g-3 bba-gallery">
          <?php foreach ((new \App\Models\GalleryImageModel())->active(4) as $image): ?>
            <div class="col-6"><img src="<?= esc(media_url($image['path']), 'attr') ?>" alt="<?= esc($image['alt'] ?: $image['caption'], 'attr') ?>" loading="lazy" width="800" height="600"></div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Strapline — one huge quote (T3) -->
<section class="section-sand">
  <div class="container">
    <p class="bb-quote-huge mb-0">“<?= esc(site('strapline')) ?>”</p>
  </div>
</section>

<!-- Vision & Mission -->
<section>
  <div class="container">
    <div class="row g-4 g-lg-5">
      <div class="col-md-6 col-lg-5">
        <div class="bba-vm">
          <h3>Our vision</h3>
          <p class="mb-0"><?= esc(site('vision')) ?></p>
        </div>
      </div>
      <div class="col-md-6 col-lg-5 offset-lg-2">
        <div class="bba-vm">
          <h3>Our mission</h3>
          <p class="mb-0"><?= esc(site('mission')) ?></p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Why book with us — spec rows -->
<section class="section-sand">
  <div class="container">
    <div class="row">
      <div class="col-lg-4">
        <p class="bb-meta mb-1"><?= esc(site('homeWhyEyebrow')) ?></p>
        <h2 class="mb-4"><?= esc(site('homeWhyHeading')) ?></h2>
      </div>
      <div class="col-lg-8">
        <div class="bb-facts">
          <?php foreach ([
              [site('homeWhy1Title'), site('homeWhy1Body')],
              [site('homeWhy2Title'), site('homeWhy2Body')],
              [site('homeWhy3Title'), site('homeWhy3Body')],
              [site('homeWhy4Title'), site('homeWhy4Body')],
          ] as [$factTitle, $factBody]): ?>
            <?php if (trim((string) $factTitle) === '' && trim((string) $factBody) === '') { continue; } ?>
            <div class="bb-fact">
              <h3><?= esc($factTitle) ?></h3>
              <p><?= esc($factBody) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Guest words -->
<?php if ($testimonials !== []): ?>
<section>
  <div class="container">
    <div class="bb-rowhead">
      <div>
        <p class="bb-meta mb-1">Guest words</p>
        <h2>What travelers say</h2>
      </div>
    </div>
    <div class="row g-4">
      <?php foreach ($testimonials as $testimonial): ?>
        <div class="col-md-6 col-lg-4">
          <blockquote class="bba-quote">
            <p>“<?= esc($testimonial['quote']) ?>”</p>
            <footer><?= esc($testimonial['author_name']) ?><?= $testimonial['author_location'] ? ', ' . esc($testimonial['author_location']) : '' ?></footer>
          </blockquote>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?= view('partials/cta_band', ['ctaHeading' => 'Your next great adventure starts with us.']) ?>

<?= $this->endSection() ?>
