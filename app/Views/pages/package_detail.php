<?= $this->extend('layouts/public') ?>

<?= $this->section('head') ?>
<?php
// Structured data: the trip as a bookable Product (price + availability), plus
// a breadcrumb trail. This is what drives price rich-snippets and lets AI
// assistants quote the trip accurately.
$bookable  = \App\Models\PackageModel::isBookable($package['availability'] ?? 'available');
$productLd  = [
    '@context'    => 'https://schema.org',
    '@type'       => 'Product',
    'name'        => $package['title'],
    'description' => excerpt_of($package['summary'], 300),
    'image'       => abs_url(media_url($package['image'])),
    'category'    => $package['category_name'] ?? 'Tour',
    'brand'       => ['@type' => 'Brand', 'name' => site('companyName')],
];
if (($package['price'] ?? null) !== null && (float) $package['price'] > 0) {
    $productLd['offers'] = [
        '@type'         => 'Offer',
        'price'         => (string) (int) round((float) $package['price']),
        'priceCurrency' => $package['currency'] ?: 'KES',
        'availability'  => 'https://schema.org/' . ($bookable ? 'InStock' : 'PreOrder'),
        'url'           => current_url(),
        'seller'        => ['@id' => base_url() . '#organization'],
    ];
}
$crumbs = ['Home' => base_url(), 'Tours & Packages' => url_to('packages')];
if (! empty($package['category_name'])) {
    $crumbs[$package['category_name']] = url_to('packages') . '?category=' . rawurlencode($package['category_slug']);
}
$crumbs[$package['title']] = current_url();
?>
<?= json_ld($productLd) ?>
<?= json_ld(seo_breadcrumb_schema($crumbs)) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<header class="bb-pagehead">
  <div class="container">
    <nav aria-label="Breadcrumb">
      <p class="bb-meta mb-3">
        <a href="<?= url_to('packages') ?>">Tours &amp; Packages</a>
        <?php if ($package['category_name']): ?>
          <span aria-hidden="true"> / </span>
          <a href="<?= url_to('packages') ?>?category=<?= esc($package['category_slug'], 'url') ?>"><?= esc($package['category_name']) ?></a>
        <?php endif; ?>
      </p>
    </nav>
    <h1><?= esc($package['title']) ?></h1>
    <p class="bb-lede"><?= esc($package['summary']) ?></p>
  </div>
</header>

<section>
  <div class="container">
    <div class="row g-4 g-lg-5">

      <div class="col-lg-8">
        <div class="bba-detail-hero mb-4">
          <img src="<?= esc(media_url($package['image']), 'attr') ?>"
               alt="<?= esc($package['image_alt'] ?: $package['title'], 'attr') ?>"
               width="1600" height="900" fetchpriority="high">
        </div>

        <?php if ($images !== []): ?>
          <div class="row g-3 bba-gallery mb-5">
            <?php foreach ($images as $image): ?>
              <div class="col-4 col-lg-3">
                <img src="<?= esc(media_url($image['path']), 'attr') ?>" alt="<?= esc($image['alt'] ?: $package['title'], 'attr') ?>" loading="lazy" width="800" height="600">
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <h2 class="h4 mb-3">About this trip</h2>
        <div class="bba-article mb-5">
          <?= nl2paras($package['description'] ?: $package['summary']) ?>
        </div>

        <?php if ($inclusions['included'] !== [] || $inclusions['excluded'] !== []): ?>
          <h2 class="h4 mb-3">What's included</h2>
          <div class="row g-4 mb-2">
            <?php if ($inclusions['included'] !== []): ?>
              <div class="col-md-6">
                <ul class="bba-inclusions">
                  <?php foreach ($inclusions['included'] as $row): ?>
                    <li class="yes"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span><?= esc($row['item']) ?></span></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>
            <?php if ($inclusions['excluded'] !== []): ?>
              <div class="col-md-6">
                <p class="bb-meta mb-2">Not included</p>
                <ul class="bba-inclusions">
                  <?php foreach ($inclusions['excluded'] as $row): ?>
                    <li class="no"><i class="bi bi-x-circle" aria-hidden="true"></i><span><?= esc($row['item']) ?></span></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <?php if (($departures ?? []) !== []): ?>
          <h2 class="h4 mt-5 mb-3">Upcoming departures</h2>
          <ul class="bb-departures">
            <?php foreach ($departures as $dep):
                $full = $dep['spots'] !== null && (int) $dep['spots'] <= 0; ?>
              <li class="bb-departure<?= $full ? ' is-full' : '' ?>">
                <span class="bb-departure__date">
                  <?= esc(date('D, j M Y', strtotime($dep['depart_date']))) ?>
                  <?php if ($dep['return_date']): ?><span class="bb-departure__return">→ <?= esc(date('j M Y', strtotime($dep['return_date']))) ?></span><?php endif; ?>
                  <?php if ($dep['note']): ?><span class="bb-departure__note"><?= esc($dep['note']) ?></span><?php endif; ?>
                </span>
                <?php if ($dep['spots'] !== null): ?>
                  <span class="bb-departure__spots"><?= $full ? 'Fully booked' : (int) $dep['spots'] . ' spot' . ((int) $dep['spots'] === 1 ? '' : 's') . ' left' ?></span>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>

      <div class="col-lg-4">
        <?php
        $avail    = $package['availability'] ?? 'available';
        $bookable = \App\Models\PackageModel::isBookable($avail);
        $spots    = $package['spots_available'] ?? null;
        $deposit  = $package['deposit_amount'] ?? null;
        ?>
        <div class="bba-booking-card">
          <div class="price-row p-4">
            <?php if ($avail !== 'available'): ?>
              <span class="bb-badge bb-badge--<?= esc(str_replace('_', '-', $avail), 'attr') ?> mb-2"><?= esc(\App\Models\PackageModel::AVAILABILITY[$avail] ?? '') ?></span>
            <?php endif; ?>
            <p class="bb-meta mb-1">From</p>
            <p class="amount mb-0"><?= esc(money($package['price'], $package['currency'])) ?></p>
            <p class="text-body-secondary small mb-0">per person, sharing</p>
            <?php if ($deposit !== null && (float) $deposit > 0): ?>
              <p class="bb-deposit mt-2 mb-0"><i class="bi bi-shield-check me-1" aria-hidden="true"></i>Secure your spot from <strong><?= esc(money($deposit, $package['currency'])) ?></strong> deposit</p>
            <?php endif; ?>
            <?php if ($bookable && $spots !== null && (int) $spots > 0): ?>
              <p class="bb-spots mt-1 mb-0"><?= (int) $spots ?> spot<?= (int) $spots === 1 ? '' : 's' ?> left</p>
            <?php endif; ?>
          </div>
          <div class="p-4">
            <ul class="bba-meta-list mb-0">
              <?php if ($package['duration_label']): ?>
                <li><span class="k">Duration</span><span class="v"><?= esc($package['duration_label']) ?></span></li>
              <?php endif; ?>
              <?php if (! empty($package['region'])): ?>
                <li><span class="k">Region</span><span class="v"><?= esc($package['region']) ?></span></li>
              <?php endif; ?>
              <?php if (! empty($package['county'])): ?>
                <li><span class="k">County</span><span class="v"><?= esc($package['county']) ?></span></li>
              <?php endif; ?>
              <?php if ($package['destination_name']): ?>
                <li><span class="k">Destination</span><span class="v"><?= esc($package['destination_name']) ?></span></li>
              <?php endif; ?>
              <?php if ($package['category_name']): ?>
                <li><span class="k">Category</span><span class="v"><?= esc($package['category_name']) ?></span></li>
              <?php endif; ?>
              <?php if ($package['tour_type_name']): ?>
                <li><span class="k">Tour type</span><span class="v"><?= esc($package['tour_type_name']) ?></span></li>
              <?php endif; ?>
              <?php if (isset($package['entrance_fee']) && $package['entrance_fee'] !== null && (float) $package['entrance_fee'] > 0): ?>
                <li><span class="k">Entrance fee</span><span class="v"><?= esc(money($package['entrance_fee'], $package['currency'])) ?></span></li>
              <?php endif; ?>
              <?php if (! empty($package['nearby_hotel'])): ?>
                <li><span class="k">Nearby hotel</span><span class="v"><?= esc($package['nearby_hotel']) ?></span></li>
              <?php endif; ?>
              <?php if (isset($package['hotel_rate']) && $package['hotel_rate'] !== null && (float) $package['hotel_rate'] > 0): ?>
                <li><span class="k">Hotel rate</span><span class="v"><?= esc(money($package['hotel_rate'], $package['currency'])) ?> <small class="text-body-secondary">/ night</small></span></li>
              <?php endif; ?>
              <?php if (! empty($package['nearby_cottage'])): ?>
                <li><span class="k">Nearby cottage</span><span class="v"><?= esc($package['nearby_cottage']) ?></span></li>
              <?php endif; ?>
              <?php if (isset($package['cottage_rate']) && $package['cottage_rate'] !== null && (float) $package['cottage_rate'] > 0): ?>
                <li><span class="k">Cottage rate</span><span class="v"><?= esc(money($package['cottage_rate'], $package['currency'])) ?> <small class="text-body-secondary">/ night</small></span></li>
              <?php endif; ?>
              <?php if ($package['group_size']): ?>
                <li><span class="k">Group size</span><span class="v"><?= esc($package['group_size']) ?></span></li>
              <?php endif; ?>
            </ul>
          </div>
          <div class="p-4 bb-paymethods">
            <p class="bb-meta mb-2">Payment</p>
            <ul class="bb-paymethods__list">
              <li><i class="bi bi-phone" aria-hidden="true"></i>M-Pesa</li>
              <li><i class="bi bi-bank" aria-hidden="true"></i>Bank transfer</li>
              <li><i class="bi bi-credit-card" aria-hidden="true"></i>Card</li>
            </ul>
            <p class="bb-paymethods__note mb-0">No payment now — we confirm availability first, then send payment details with your quote.</p>
          </div>
        </div>

        <div class="mt-4">
          <?= view('packages/_booking_form', ['package' => $package, 'departures' => $departures ?? []]) ?>
        </div>
      </div>

    </div>
  </div>
</section>

<?php if ($related !== []): ?>
<section class="section-sand">
  <div class="container">
    <div class="bb-rowhead">
      <h2>Similar journeys</h2>
      <a href="<?= url_to('packages') ?>" class="bb-link">All packages&nbsp;→</a>
    </div>
    <div class="row g-4">
      <?php foreach ($related as $item): ?>
        <div class="col-md-6 col-lg-4">
          <?= view('partials/package_card', ['package' => $item]) ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="bb-band">
  <div class="container">
    <h2>Want this trip, adjusted?</h2>
    <p>Dates, group size and comfort level can all move. Tell us what you have in mind and we'll rework it around you.</p>
    <div class="d-flex flex-wrap gap-3">
      <a href="<?= url_to('custom-trips') ?>" class="btn btn-bba-gold">Plan a custom trip</a>
      <button type="button" class="btn btn-bba-outline-light"
              data-wa-open="I'd like to adjust the <?= esc($package['title'], 'attr') ?> package.">
        <i class="bi bi-whatsapp me-2" aria-hidden="true"></i>Chat with us
      </button>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
