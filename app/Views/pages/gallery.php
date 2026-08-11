<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<header class="bb-pagehead">
  <div class="container">
    <h1>Moments from the road</h1>
    <p class="bb-lede">Photographs from our safaris, beach holidays, treks and cultural trips across Kenya.</p>
  </div>
</header>

<section>
  <div class="container">
    <?php if ($images === []): ?>
      <div class="bba-empty text-center p-5">
        <p class="mb-0">No photographs have been published yet — check back soon.</p>
      </div>
    <?php else: ?>
      <div class="row g-3 bba-gallery">
        <?php foreach ($images as $image): ?>
          <figure class="col-6 col-lg-4 mb-0">
            <img src="<?= esc(media_url($image['path']), 'attr') ?>"
                 alt="<?= esc($image['alt'] ?: $image['caption'], 'attr') ?>" loading="lazy" width="800" height="600">
            <?php if ($image['caption']): ?>
              <figcaption><?= esc($image['caption']) ?></figcaption>
            <?php endif; ?>
          </figure>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?= view('partials/cta_band', [
    'ctaHeading' => 'Picture yourself here.',
    'ctaText'    => "Every one of these was somebody's ordinary Tuesday. Pick a trip and make it yours.",
]) ?>

<?= $this->endSection() ?>
