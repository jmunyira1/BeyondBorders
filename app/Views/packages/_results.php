<?php
/**
 * The results region of the packages page. Rendered inline on a full page load
 * and swapped on its own by htmx when a filter changes.
 *
 * @var array $packages
 * @var array $activeFilters
 * @var int   $total
 */
?>
<div class="bba-spinner" role="status" aria-live="polite">
  <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Finding trips…
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
  <p class="bba-result-count mb-0" role="status" aria-live="polite">
    <?php if ($total === 0): ?>
      No packages match those filters
    <?php else: ?>
      Showing <strong><?= count($packages) ?></strong> of <strong><?= $total ?></strong> <?= $total === 1 ? 'package' : 'packages' ?>
    <?php endif; ?>
  </p>

  <?php if ($activeFilters !== []): ?>
    <div class="d-flex flex-wrap gap-2 align-items-center">
      <?php foreach ($activeFilters as $chip): ?>
        <a class="bba-chip"
           href="<?= esc($chip['removeUrl'], 'attr') ?>"
           hx-get="<?= esc(str_replace(url_to('packages'), url_to('packages-filter'), $chip['removeUrl']), 'attr') ?>"
           hx-target="#packages-results"
           hx-push-url="<?= esc($chip['removeUrl'], 'attr') ?>"
           aria-label="Remove filter: <?= esc($chip['label'], 'attr') ?>">
          <?= esc($chip['label']) ?><i class="bi bi-x-lg" aria-hidden="true"></i>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php if ($packages === []): ?>
  <div class="bba-empty text-center p-5">
    <p class="mb-2">No ready-made packages match what you're looking for.</p>
    <p class="text-body-secondary mb-3">Try widening the filters — or let us build the trip around you instead.</p>
    <div class="d-flex flex-wrap gap-2 justify-content-center">
      <a href="<?= url_to('packages') ?>" class="btn btn-bba-outline btn-sm"
         hx-get="<?= url_to('packages-filter') ?>" hx-target="#packages-results" hx-push-url="<?= url_to('packages') ?>">Clear filters</a>
      <a href="<?= url_to('custom-trips') ?>" class="btn btn-bba-gold btn-sm">Plan a Custom Trip instead</a>
    </div>
  </div>
<?php else: ?>
  <div class="row g-4">
    <?php foreach ($packages as $package): ?>
      <div class="col-md-6 col-lg-4">
        <?= view('partials/package_card', ['package' => $package]) ?>
      </div>
    <?php endforeach; ?>
  </div>

  <?php if ($pager->getPageCount('default') > 1): ?>
    <nav class="mt-5 d-flex justify-content-center bba-pager" aria-label="Package pages">
      <?= $pager->links('default', 'bba_pager') ?>
    </nav>
  <?php endif; ?>
<?php endif; ?>
