<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<?php
// Section visibility + content is driven by Admin → Homepage (Site.* settings,
// with the current copy as defaults in Config\Site).
$show = static fn (string $k): bool => (bool) setting('Site.' . $k);

// Rotating hero backdrop. Use the images the admin flagged "Show in homepage
// hero"; if none are flagged, fall back to the active gallery; if that is empty
// too, seeded placeholders. Always exactly four layers so the pure-CSS crossfade
// timing stays exact (cycles the pool if there are fewer than four images).
$heroSource = ($heroImages ?? []) !== [] ? $heroImages : $gallery;
$pool = [];
foreach ($heroSource as $g) {
    $pool[] = [
        'src' => media_url($g['path']),
        'alt' => $g['alt'] ?: ($g['caption'] ?: 'A travel moment in Kenya'),
    ];
}
if (count($pool) < 2) {
    $pool = array_map(
        static fn (string $seed): array => [
            'src' => 'https://picsum.photos/seed/bbahero-' . $seed . '/1900/1200',
            'alt' => 'Kenya landscape',
        ],
        ['savanna', 'mara', 'diani', 'amboseli']
    );
}
$heroSlides = [];
for ($i = 0; $i < 4; $i++) {
    $heroSlides[] = $pool[$i % count($pool)];
}
?>

<!-- Hero — crossfading photographic slideshow with centred copy -->
<header class="bb-hero">
  <div class="bb-hero__slides" aria-hidden="true">
    <?php foreach ($heroSlides as $i => $slide): ?>
      <img class="bb-hero__slide" style="--i: <?= $i ?>"
           src="<?= esc($slide['src'], 'attr') ?>"
           alt="<?= esc($slide['alt'], 'attr') ?>"
           width="1900" height="1200"
           <?= $i === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>>
    <?php endforeach; ?>
  </div>
  <div class="container bb-hero__copy">
    <p class="bb-hero__caption"><?= esc(site('heroEyebrow')) ?></p>
    <h1><?= site('heroHeading') /* allows <br> from settings */ ?></h1>
    <p class="lead"><?= esc(site('heroLead')) ?></p>
    <div class="bb-hero__cta">
      <a href="<?= url_to('packages') ?>" class="btn btn-bba-gold">Explore packages</a>
      <a href="<?= url_to('custom-trips') ?>" class="btn btn-bba-outline-light">Plan a custom trip</a>
    </div>
  </div>
</header>

<?php if ($show('homeShowContactStrip')): ?>
<!-- Quick contact strip — reach us the way you prefer -->
<section class="bb-contactstrip">
  <div class="container">
    <div class="bb-contactstrip__row">
      <a href="mailto:<?= esc(site('email'), 'attr') ?>">
        <i class="bi bi-envelope" aria-hidden="true"></i><span><?= esc(site('email')) ?></span>
      </a>
      <button type="button" class="bb-contactstrip__btn" data-wa-open="Hi, I'd like to plan a trip.">
        <i class="bi bi-whatsapp" aria-hidden="true"></i><span>Chat on WhatsApp</span>
      </button>
      <a href="tel:<?= esc(site('phoneLink'), 'attr') ?>">
        <i class="bi bi-telephone" aria-hidden="true"></i><span><?= esc(site('phone')) ?></span>
      </a>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($show('homeShowHighlights')): ?>
<!-- What you can look forward to — the emotional hook, up top -->
<section>
  <div class="container">
    <div class="bb-rowhead">
      <div>
        <p class="bb-meta mb-1"><?= esc(site('homeHighlightsEyebrow')) ?></p>
        <h2><?= esc(site('homeHighlightsHeading')) ?></h2>
      </div>
    </div>
    <div class="bb-highlights">
      <?php foreach ([
          ['bi-camera',     site('homeHighlight1')],
          ['bi-binoculars', site('homeHighlight2')],
          ['bi-tree',       site('homeHighlight3')],
          ['bi-cup-hot',    site('homeHighlight4')],
          ['bi-buildings',  site('homeHighlight5')],
      ] as [$icon, $label]): ?>
        <?php if (trim((string) $label) === '') { continue; } ?>
        <div class="bb-highlight">
          <span class="bb-highlight__icon"><i class="bi <?= $icon ?>" aria-hidden="true"></i></span>
          <span class="bb-highlight__label"><?= esc($label) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($show('homeShowCtaBand')): ?>
<!-- Stories worth telling — the big emotional CTA, brought up high -->
<section class="bb-band">
  <div class="container">
    <h2><?= esc(site('homeCtaHeading')) ?></h2>
    <p><?= esc(site('homeCtaBody')) ?></p>
    <div class="d-flex flex-wrap gap-3 mb-4">
      <a href="<?= url_to('custom-trips') ?>" class="btn btn-bba-gold">Plan my trip</a>
      <a href="sms:<?= esc(site('phoneLink'), 'attr') ?>" class="btn btn-bba-outline-light"><i class="bi bi-chat-text me-2" aria-hidden="true"></i>Text us</a>
      <button type="button" class="btn btn-bba-outline-light" data-wa-open="Hi, I'd like to plan a trip."><i class="bi bi-whatsapp me-2" aria-hidden="true"></i>WhatsApp</button>
    </div>
    <p class="bb-meta mb-0 d-flex flex-wrap gap-3">
      <a href="mailto:<?= esc(site('email'), 'attr') ?>"><i class="bi bi-envelope me-2" aria-hidden="true"></i><?= esc(site('email')) ?></a>
      <a href="tel:<?= esc(site('phoneLink'), 'attr') ?>"><i class="bi bi-telephone me-2" aria-hidden="true"></i><?= esc(site('phone')) ?></a>
    </p>
  </div>
</section>
<?php endif; ?>

<?php if ($show('homeShowThreeSteps')): ?>
<!-- Custom-trip promise + the three-step process -->
<section class="bb-journey section-sand">
  <div class="container">
    <div class="row mb-4 mb-lg-5">
      <div class="col-lg-8">
        <p class="bb-meta mb-1"><?= esc(site('homeStepsEyebrow')) ?></p>
        <h2 class="mb-3"><?= esc(site('homeStepsHeading')) ?></h2>
        <p class="bb-lede-para mb-0"><?= esc(site('homeStepsLede')) ?></p>
      </div>
    </div>
    <p class="bb-meta mb-3"><?= esc(site('homeStepsLabel')) ?></p>
    <div class="row g-4 g-lg-5">
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
    <div class="mt-4 mt-lg-5">
      <button type="button" class="btn btn-bba-green" data-wa-open="Hi, I'd like to plan a trip.">
        <i class="bi bi-whatsapp me-2" aria-hidden="true"></i>Start on WhatsApp
      </button>
    </div>
  </div>
</section>
<?php endif; ?>

<?php // Honest catalogue counts — never invented figures. Hide any that are zero. ?>
<?php $statItems = array_values(array_filter([
    ['n' => (int) ($stats['packages']     ?? 0), 'label' => 'Trips ready to book'],
    ['n' => (int) ($stats['destinations'] ?? 0), 'label' => 'Destinations covered'],
    ['n' => (int) ($stats['categories']   ?? 0), 'label' => 'Ways to travel'],
    ['n' => (int) ($stats['reviews']      ?? 0), 'label' => 'Traveller reviews'],
], static fn (array $s): bool => $s['n'] > 0)); ?>
<?php if ($show('homeShowStats') && $statItems !== []): ?>
<!-- Stat band — real numbers, counted up on scroll-in -->
<section>
  <div class="container">
    <div class="bb-stats">
      <?php foreach ($statItems as $stat): ?>
        <div class="bb-stat">
          <span class="bb-stat__num" data-count="<?= $stat['n'] ?>"><?= $stat['n'] ?></span>
          <span class="bb-stat__label"><?= esc($stat['label']) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($show('homeShowSearch')): ?>
<!-- Trip search — sits directly above the featured trips -->
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
<?php endif; ?>

<?php if ($show('homeShowFeatured') && $featured !== []): ?>
<!-- Featured packages — three curated trips -->
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
      <?php foreach (array_slice($featured, 0, 3) as $package): ?>
        <div class="col-md-6 col-lg-4">
          <?= view('partials/package_card', ['package' => $package]) ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($show('homeShowExplore') && $categories !== []): ?>
<!-- Ways to explore — photographic category tiles -->
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

<?php if ($show('homeShowWhyBook')): ?>
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
<?php endif; ?>

<?php if ($show('homeShowGallery') && $gallery !== []): ?>
<!-- Gallery teaser -->
<section>
  <div class="container">
    <div class="bb-rowhead">
      <div>
        <p class="bb-meta mb-1">From the field</p>
        <h2>Moments from the road</h2>
      </div>
      <a href="<?= url_to('gallery') ?>" class="bb-link">Full gallery&nbsp;→</a>
    </div>
    <div class="row g-3 bba-gallery">
      <?php foreach (array_slice($gallery, 0, 4) as $image): ?>
        <div class="col-6 col-lg-3">
          <img src="<?= esc(media_url($image['path']), 'attr') ?>" alt="<?= esc($image['alt'] ?: $image['caption'], 'attr') ?>" loading="lazy" width="800" height="600">
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($show('homeShowJournal') && $posts !== []): ?>
<!-- Journal teaser -->
<section class="section-sand">
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

<?php if ($show('homeShowTestimonials') && $testimonials !== []): ?>
<!-- Guest words — social proof near the close -->
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

<?php if ($show('homeShowAbout')): ?>
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
<?php endif; ?>

<?php // Stat count-up — animates 0 → value when the band scrolls into view. ?>
<script>
(function () {
  var els = document.querySelectorAll('.bb-stat__num[data-count]');
  if (!els.length) return;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  // No JS / reduced motion / no observer → leave the server-rendered number as is.
  if (reduce || !('IntersectionObserver' in window)) return;
  var run = function (el) {
    var target = parseInt(el.getAttribute('data-count'), 10) || 0;
    var start = null, dur = 1100;
    var tick = function (t) {
      if (start === null) start = t;
      var p = Math.min((t - start) / dur, 1);
      el.textContent = Math.round((0.5 - Math.cos(p * Math.PI) / 2) * target);
      if (p < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  };
  els.forEach(function (el) { el.textContent = '0'; }); // reset so the count-up starts from zero
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) { if (e.isIntersecting) { run(e.target); io.unobserve(e.target); } });
  }, { threshold: 0.4 });
  els.forEach(function (el) { io.observe(el); });
})();
</script>

<?= $this->endSection() ?>
