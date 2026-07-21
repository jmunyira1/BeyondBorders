<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<header class="bb-pagehead">
  <div class="container">
    <h1>Born in Kenya. Inspired by its beauty.</h1>
    <p class="bb-lede">A Kenyan travel company creating authentic, safe and seamless journeys.</p>
  </div>
</header>

<!-- Story — prose beside an image pair, left-biased -->
<section>
  <div class="container">
    <div class="row g-4 g-lg-5 align-items-center">
      <div class="col-lg-6">
        <h2 class="mb-4">A Kenyan company, for travelers everywhere</h2>
        <p>We are a Kenyan travel and adventure company dedicated to creating unforgettable experiences for both local and international travelers. We help you discover Kenya's breathtaking landscapes, incredible wildlife, vibrant cultures and pristine beaches through authentic, safe and memorable journeys.</p>
        <p>Whether you're looking for an exciting safari, a relaxing weekend getaway, a budget-friendly escape, a nature adventure, mountain trekking or an immersive cultural experience, we have the expertise to make your dream adventure a reality.</p>
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
        <h2 class="mb-4">Travel, handled properly.</h2>
      </div>
      <div class="col-lg-8">
        <div class="bb-facts">
          <div class="bb-fact">
            <h3>Transparent pricing</h3>
            <p>Inclusions spelled out on every package — the price you see is the price you pay.</p>
          </div>
          <div class="bb-fact">
            <h3>Secure payments</h3>
            <p>Pay securely with M-Pesa or bank transfer, with written confirmation every time.</p>
          </div>
          <div class="bb-fact">
            <h3>Local experts</h3>
            <p>Journeys planned by Kenyans who know the parks, coast and seasons first-hand.</p>
          </div>
          <div class="bb-fact">
            <h3>Accountable</h3>
            <p>One point of contact from enquiry to your journey home. Licensed &amp; registered.</p>
          </div>
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
      <h2>What travelers say</h2>
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

<!-- Statement band -->
<section class="bb-band">
  <div class="container">
    <h2>Your next great adventure starts with us.</h2>
    <p>Browse our ready-to-book journeys, or tell us what you have in mind and we'll design it around you.</p>
    <div class="d-flex flex-wrap gap-3">
      <a href="<?= url_to('packages') ?>" class="btn btn-bba-gold">Explore packages</a>
      <a href="<?= url_to('contact') ?>" class="btn btn-bba-outline-light">Talk to us</a>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
