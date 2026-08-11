<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- Hero — photographic fold: the hero IS the page above the fold -->
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

<!-- Trip search — lifts onto the hero seam, submits into the packages filter -->
<section class="bb-search-section pb-0">
  <div class="container">
    <form class="bb-search row g-0 align-items-center p-3" action="<?= url_to('packages') ?>" method="get" aria-label="Search trips">
      <div class="col-lg-4 cell px-3 py-2 bb-suggest-wrap"
           data-custom-url="<?= esc(url_to('custom-trips'), 'attr') ?>"
           data-packages-url="<?= esc(url_to('packages'), 'attr') ?>">
        <label class="form-label" for="q-dest">Destination</label>
        <?php // Combobox. The whole catalogue is embedded below, so app.js filters
              // it locally — no request per keystroke. Without JS it's a plain input. ?>
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
        <button class="btn btn-bba-gold" type="submit"><i class="bi bi-search me-2" aria-hidden="true"></i>Search</button>
      </div>
    </form>

    <?php // Search index for the combobox. Regenerated on every render. ?>
    <script type="application/json" id="bb-search-index"><?= json_encode(
        $searchIndex,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
    ) ?></script>
  </div>
</section>

<!-- Ways to explore — photographic category tiles -->
<?php if ($categories !== []): ?>
<section>
  <div class="container">
    <div class="bb-rowhead">
      <div>
        <p class="bb-meta mb-1">Browse by interest</p>
        <h2>Ways to explore Kenya</h2>
      </div>
      <a href="<?= url_to('packages') ?>" class="bb-link">All trips&nbsp;→</a>
    </div>
    <div class="bb-campaign<?= count($categories) === 3 ? ' bb-campaign--3' : '' ?>">
      <?php foreach (array_slice($categories, 0, 4) as $category): ?>
        <a class="bb-tile" href="<?= url_to('packages') ?>?category=<?= esc($category['slug'], 'url') ?>">
          <?php // Placeholder photography, seeded per category, until real images are uploaded. ?>
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

<!-- Featured packages -->
<?php if ($featured !== []): ?>
<section class="section-sand">
  <div class="container">
    <div class="bb-rowhead">
      <div>
        <p class="bb-meta mb-1">Ready to book</p>
        <h2>Featured packages</h2>
      </div>
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

<!-- How it works — three steps, honest and useful -->
<section>
  <div class="container">
    <div class="bb-rowhead">
      <div>
        <p class="bb-meta mb-1">Simple &amp; transparent</p>
        <h2>How it works</h2>
      </div>
    </div>
    <div class="row g-4 g-lg-5">
      <div class="col-md-4">
        <div class="bb-step">
          <span class="num">01</span>
          <h3>Tell us your plan</h3>
          <p class="text-body-secondary">Where, when, how many people and roughly what budget — through the form, WhatsApp or a quick call. We tailor-make it and send a clear quote within 24 hours.</p>
          <a class="bb-link" href="<?= url_to('custom-trips') ?>">Plan a custom trip&nbsp;→</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="bb-step">
          <span class="num">02</span>
          <h3>Or pick a ready-made trip</h3>
          <p class="text-body-secondary">Browse our clearly-priced packages — every inclusion spelled out — and choose the journey that fits your dates and budget.</p>
          <a class="bb-link" href="<?= url_to('packages') ?>">Browse packages&nbsp;→</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="bb-step">
          <span class="num">03</span>
          <h3>Confirm and travel</h3>
          <p class="text-body-secondary">Pay securely by M-Pesa or bank transfer with written confirmation. We handle transport, stays and activities — you just show up.</p>
          <button type="button" class="bb-link bb-link--btn" data-wa-open="Hi Beyond Borders, I'd like to plan a trip.">Chat on WhatsApp&nbsp;→</button>
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
        <p class="bb-meta mb-1">Why book with us</p>
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

<!-- Guest words -->
<?php if ($testimonials !== []): ?>
<section>
  <div class="container">
    <p class="bb-meta mb-3 text-center">What travellers say</p>
    <blockquote class="mb-0 text-center bb-quote-center">
      <p class="bb-quote-huge mx-auto">“<?= esc($testimonials[0]['quote']) ?>”</p>
      <footer><?= esc($testimonials[0]['author_name']) ?><?= $testimonials[0]['author_location'] ? ', ' . esc($testimonials[0]['author_location']) : '' ?></footer>
    </blockquote>
    <?php $others = array_slice($testimonials, 1, 2); ?>
    <?php if ($others !== []): ?>
      <div class="row g-4 mt-4 justify-content-center">
        <?php foreach ($others as $testimonial): ?>
          <div class="col-md-6 col-lg-5">
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
<section class="section-sand">
  <div class="container">
    <div class="bb-rowhead">
      <div>
        <p class="bb-meta mb-1">From the field</p>
        <h2>Moments from the road</h2>
      </div>
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
<section>
  <div class="container">
    <div class="bb-rowhead">
      <div>
        <p class="bb-meta mb-1">The journal</p>
        <h2>Notes from the road</h2>
      </div>
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

<!-- About — Jambo intro + vision & mission -->
<section class="section-sand">
  <div class="container">
    <div class="row g-4 g-lg-5">
      <div class="col-lg-5">
        <p class="bb-meta mb-1">Karibu</p>
        <h2 class="bb-display-s mb-3"><?= esc(site('introHeading')) ?></h2>
        <?= nl2paras(site('introBody'), 'mb-3') ?>
        <p class="mt-3 mb-0"><a class="bb-link" href="<?= url_to('about') ?>">More about us&nbsp;→</a></p>
      </div>
      <div class="col-lg-6 offset-lg-1">
        <div class="bba-vm mb-4">
          <h3>Our vision</h3>
          <p class="mb-0"><?= esc(site('vision')) ?></p>
        </div>
        <div class="bba-vm">
          <h3>Our mission</h3>
          <p class="mb-0"><?= esc(site('mission')) ?></p>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
