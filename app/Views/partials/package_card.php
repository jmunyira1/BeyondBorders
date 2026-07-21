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
?>
<article class="bb-item h-100">
  <a href="<?= url_to('package', $package['slug']) ?>" class="bb-item__media" tabindex="-1" aria-hidden="true">
    <img src="<?= esc(media_url($package['image']), 'attr') ?>"
         alt="<?= esc($package['image_alt'] ?: $package['title'], 'attr') ?>" loading="lazy" width="800" height="600">
  </a>
  <div class="bb-item__body">
    <?php if ($meta !== []): ?>
      <p class="bb-meta mb-0"><?= esc(implode(' · ', $meta)) ?></p>
    <?php endif; ?>
    <h3><a href="<?= url_to('package', $package['slug']) ?>" class="stretched-link"><?= esc($package['title']) ?></a></h3>
    <p class="text-body-secondary mb-0"><?= esc(excerpt_of($package['summary'], 120)) ?></p>
    <div class="bb-item__foot">
      <span class="bb-price"><small>From</small><?= esc(money($package['price'], $package['currency'])) ?></span>
      <span class="bb-link" aria-hidden="true">View trip&nbsp;→</span>
    </div>
  </div>
</article>
