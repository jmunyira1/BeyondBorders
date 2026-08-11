<?php
/**
 * One package card (Hallmark F6 catalogue item — uniform on purpose).
 * Used by the homepage featured row, the packages grid and the "related
 * trips" row on the detail page.
 *
 * @var array $package
 */
$meta = array_filter([
    $package['duration_label'] ?? null,
    $package['category_name'] ?? null,
]);
$avail    = $package['availability'] ?? 'available';
$spots    = $package['spots_available'] ?? null;
$bookable = \App\Models\PackageModel::isBookable($avail);
?>
<article class="bb-item h-100">
  <a href="<?= url_to('package', $package['slug']) ?>" class="bb-item__media" tabindex="-1" aria-hidden="true">
    <?php if ($avail !== 'available'): ?>
      <span class="bb-badge bb-badge--<?= esc(str_replace('_', '-', $avail), 'attr') ?>"><?= esc(\App\Models\PackageModel::AVAILABILITY[$avail] ?? '') ?></span>
    <?php endif; ?>
    <img src="<?= esc(media_url($package['image']), 'attr') ?>"
         alt="<?= esc($package['image_alt'] ?: $package['title'], 'attr') ?>" loading="lazy" width="800" height="600">
  </a>
  <div class="bb-item__body">
    <?php if ($meta !== []): ?>
      <p class="bb-meta mb-0"><?= esc(implode(' · ', $meta)) ?></p>
    <?php endif; ?>
    <h3><a href="<?= url_to('package', $package['slug']) ?>" class="stretched-link"><?= esc($package['title']) ?></a></h3>
    <p class="text-body-secondary mb-0"><?= esc(excerpt_of($package['summary'], 120)) ?></p>
    <?php if ($bookable && $spots !== null && (int) $spots > 0): ?>
      <p class="bb-spots mb-0"><?= (int) $spots ?> spot<?= (int) $spots === 1 ? '' : 's' ?> left</p>
    <?php endif; ?>
    <div class="bb-item__foot">
      <span class="bb-price"><small>From</small><?= esc(money($package['price'], $package['currency'])) ?></span>
      <span class="bb-link" aria-hidden="true">View trip&nbsp;→</span>
    </div>
  </div>
</article>
