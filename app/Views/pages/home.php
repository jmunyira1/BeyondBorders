<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- Photographic fold (H6) — the hero IS the page above the fold -->
<header class="bb-fold">
  <img class="bb-fold__img" src="https://picsum.photos/seed/grove-18/1800/1100"
       alt="Golden-hour light over the Kenyan savanna" width="1800" height="1100" fetchpriority="high">
  <div class="container bb-fold__copy">
    <p class="bb-fold__caption mb-3"><?= esc(site('heroEyebrow')) ?></p>
    <h1><?= site('heroHeading') /* allows <br> from settings */ ?></h1>
    <p class="lead"><?= esc(site('heroLead')) ?></p>
    <div class="d-flex flex-wrap gap-3">
      <a href="<?= url_to('packages') ?>" class="btn btn-bba-gold">Explore packages</a>
      <a href="<?= url_to('custom-trips') ?>" class="btn btn-bba-outline-light">Plan a custom trip</a>
    </div>
  </div>
</header>

<!-- Trip search — submits straight into the packages filter -->
<section class="pt-4 pb-0">
  <div class="container">
    <form class="bb-search row g-0 align-items-center p-3" action="<?= url_to('packages') ?>" method="get" aria-label="Search trips">
      <div class="col-lg-4 cell px-3 py-2 bb-suggest-wrap"
           data-custom-url="<?= esc(url_to('custom-trips'), 'attr') ?>"
           data-packages-url="<?= esc(url_to('packages'), 'attr') ?>">
        <label class="form-label" for="q-dest">Destination</label>
        <?php // Combobox. The whole catalogue (a few KB) is embedded below, so
              // app.js filters it locally — no request per keystroke. Without
              // JavaScript this is a plain input and the form still submits. ?>
        <input class="form-control" id="q-dest" name="q" type="text" placeholder="e.g. Maasai Mara, Diani…"
               autocomplete="off" role="combobox" aria-expanded="false"
               aria-autocomplete="list" aria-controls="q-dest-suggest">
        <div class="bb-suggest" id="q-dest-suggest" role="listbox" aria-label="Trip suggestions" hidden></div>
      </div>
      <div class="col-lg-3 cell px-3 py-2">
        <label class="form-label" for="q-type">Trip type</label>
        <select class="form-select" id="q-type" name="category">
          <option value="">Any type</option>
          <?php foreach ($categories as $category): ?>
            <option value="<?= esc($category['slug'], 'attr') ?>"><?= esc($category['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-lg-3 cell px-3 py-2">
        <label class="form-label" for="q-budget">Budget</label>
        <select class="form-select" id="q-budget" name="price">
          <option value="">Any budget</option>
          <?php foreach (\App\Models\PackageModel::PRICE_RANGES as $key => $range): ?>
            <option value="<?= esc($key, 'attr') ?>"><?= esc($range['label']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-lg-2 px-3 py-2 d-grid">
        <button class="btn btn-bba-green" type="submit"><i class="bi bi-search me-2" aria-hidden="true"></i>Search</button>
      </div>
    </form>

    <?php // Search index for the combobox. Regenerated on every render, so
          // anything the admin edits is reflected immediately. ?>
    <script type="application/json" id="bb-search-index"><?= json_encode(
        $searchIndex,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
    ) ?></script>

    <?php // Category shortcuts belong to the search — one discovery block. ?>
    <?php if ($categories !== []): ?>
      <nav class="mt-4" aria-label="Browse by category">
        <p class="bb-meta mb-2">Or browse by category</p>
        <div class="bb-index">
          <?php foreach ($categories as $category): ?>
            <a href="<?= url_to('packages') ?>?category=<?= esc($category['slug'], 'url') ?>"><?= esc($category['name']) ?></a>
          <?php endforeach; ?>
          <a href="<?= url_to('packages') ?>">All trips&nbsp;→</a>
        </div>
      </nav>
    <?php endif; ?>
  </div>
</section>

<!-- Featured packages — catalogue grid (F6), straight after the discovery block -->
<?php if ($featured !== []): ?>
<section>
  <div class="container">
    <div class="bb-rowhead">
      <h2>Featured packages</h2>
      <a href="<?= url_to('packages') ?>" class="bb-link">View all packages&nbsp;→</a>
    </div>
    <div class="row g-4">
      <?php foreach ($featured as $package): ?>
        <div class="col-md-6 col-lg-4">
          <?= view('partials/package_card', ['package' => $package]) ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Ways to explore — photographic campaign tiles -->
<?php if ($categories !== []): ?>
<section class="pt-0">
  <div class="container">
    <div class="bb-rowhead">
      <h2>Ways to explore Kenya</h2>
      <a href="<?= url_to('packages') ?>" class="bb-link">All trips&nbsp;→</a>
    </div>
    <div class="bb-campaign<?= count($categories) === 3 ? ' bb-campaign--3' : '' ?>">
      <?php foreach (array_slice($categories, 0, 4) as $category): ?>
        <a class="bb-tile" href="<?= url_to('packages') ?>?category=<?= esc($category['slug'], 'url') ?>">
          <?php // Placeholder photography, seeded per category for stability,
                // until real category images are uploaded. ?>
          <img src="https://picsum.photos/seed/bba-<?= esc($category['slug'], 'attr') ?>/800/1000"
               alt="<?= esc($category['name']) ?> trips in Kenya" loading="lazy" width="800" height="1000">
          <span class="bb-tile__label">
            <span class="bb-tile__name"><?= esc($category['name']) ?></span>
            <span class="bb-tile__go">Explore <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
          </span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Intro — who we are, after the trips have made the case -->
<section class="section-sand">
  <div class="container">
    <div class="row">
      <div class="col-lg-7">
        <h2 class="mb-4 bb-display-s"><?= esc(site('introHeading')) ?></h2>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6 offset-lg-1">
        <?= nl2paras(site('introBody'), 'mb-3') ?>
        <p class="mt-4 mb-0"><a class="bb-link" href="<?= url_to('about') ?>">More about us&nbsp;→</a></p>
      </div>
    </div>
  </div>
</section>

<!-- Statement band -->
<section class="bb-band">
  <div class="container">
    <h2>Ready for the adventure of a lifetime?</h2>
    <p>Private safaris, honeymoons, corporate staff retreats and cultural events — tell us the occasion, the group and the budget, and we plan the whole journey end to end.</p>
    <div class="d-flex flex-wrap gap-3 mb-4">
      <a href="<?= url_to('custom-trips') ?>" class="btn btn-bba-gold">Plan a custom trip</a>
      <a href="<?= url_to('contact') ?>" class="btn btn-bba-outline-light">Talk to us</a>
    </div>
    <p class="bb-meta mb-0"><i class="bi bi-telephone me-2" aria-hidden="true"></i><a href="tel:<?= esc(site('phoneLink'), 'attr') ?>"><?= esc(site('phone')) ?></a></p>
  </div>
</section>

<!-- Why book with us — spec rows, not icon cards -->
<section>
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

<!-- Guest words — one voice set big, two beside it -->
<?php if ($testimonials !== []): ?>
<section class="section-sand">
  <div class="container">
    <blockquote class="mb-0">
      <p class="bb-quote-huge">“<?= esc($testimonials[0]['quote']) ?>”</p>
      <footer><?= esc($testimonials[0]['author_name']) ?><?= $testimonials[0]['author_location'] ? ', ' . esc($testimonials[0]['author_location']) : '' ?></footer>
    </blockquote>
    <?php $others = array_slice($testimonials, 1, 2); ?>
    <?php if ($others !== []): ?>
      <div class="row g-4 mt-4">
        <?php foreach ($others as $testimonial): ?>
          <div class="col-md-6">
            <blockquote class="bba-quote">
              <p>“<?= esc($testimonial['quote']) ?>”</p>
              <footer><?= esc($testimonial['author_name']) ?><?= $testimonial['author_location'] ? ', ' . esc($testimonial['author_location']) : '' ?></footer>
            </blockquote>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<!-- Gallery teaser -->
<?php if ($gallery !== []): ?>
<section>
  <div class="container">
    <div class="bb-rowhead">
      <h2>Moments from the road</h2>
      <a href="<?= url_to('gallery') ?>" class="bb-link">Full gallery&nbsp;→</a>
    </div>
    <div class="row g-3 bba-gallery">
      <?php foreach ($gallery as $image): ?>
        <div class="col-6 col-lg-3">
          <img src="<?= esc(media_url($image['path']), 'attr') ?>" alt="<?= esc($image['alt'] ?: $image['caption'], 'attr') ?>" loading="lazy" width="800" height="600">
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Journal teaser -->
<?php if ($posts !== []): ?>
<section class="pt-0">
  <div class="container">
    <div class="bb-rowhead">
      <h2>Notes from the road</h2>
      <a href="<?= url_to('blog') ?>" class="bb-link">All posts&nbsp;→</a>
    </div>
    <div class="row g-4">
      <?php foreach ($posts as $post): ?>
        <div class="col-md-4">
          <article class="bb-item h-100">
            <a href="<?= url_to('post', $post['slug']) ?>" class="bb-item__media" tabindex="-1" aria-hidden="true">
              <img src="<?= esc(media_url($post['image']), 'attr') ?>" alt="<?= esc($post['image_alt'] ?: $post['title'], 'attr') ?>" loading="lazy" width="800" height="600">
            </a>
            <div class="bb-item__body">
              <p class="bb-meta mb-0"><?= esc(date('F Y', strtotime($post['published_at']))) ?> · <?= (int) $post['read_minutes'] ?>&nbsp;min read</p>
              <h3><a href="<?= url_to('post', $post['slug']) ?>" class="stretched-link"><?= esc($post['title']) ?></a></h3>
              <div class="bb-item__foot">
                <span class="bb-link" aria-hidden="true">Read the story&nbsp;→</span>
              </div>
            </div>
          </article>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Vision & Mission -->
<section class="section-sand">
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

<?= $this->endSection() ?>
