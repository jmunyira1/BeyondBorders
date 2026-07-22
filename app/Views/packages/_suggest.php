<?php
/**
 * Search-suggestion dropdown fragment, swapped into the combobox's listbox
 * container by htmx as the visitor types. Options are plain links — Enter and
 * click both navigate; the combobox JS in app.js only moves the highlight.
 *
 * @var string $q
 * @var array  $packages
 * @var array  $destinations
 * @var array  $categories
 * @var array  $tourTypes
 */
$hasAny = $packages !== [] || $destinations !== [] || $categories !== [] || $tourTypes !== [];
$optId  = 0;
?>
<?php if (! $hasAny): ?>
  <div class="bb-suggest__empty" role="status">
    <p class="mb-1">We don't have a ready-made trip for “<?= esc($q) ?>” yet.</p>
    <p class="mb-2 bb-suggest__hint">Tell us what you have in mind and we'll plan it — or see everything that's ready to book.</p>
    <a href="<?= url_to('custom-trips') ?>" id="sg-opt-0" role="option" class="bb-suggest__option">Plan a custom trip&nbsp;→</a>
    <a href="<?= url_to('packages') ?>" id="sg-opt-1" role="option" class="bb-suggest__option">View all packages&nbsp;→</a>
  </div>
<?php else: ?>
  <ul class="bb-suggest__list" role="presentation">
    <?php if ($packages !== []): ?>
      <li class="bb-suggest__group" role="presentation">Trips</li>
      <?php foreach ($packages as $package): ?>
        <li role="presentation">
          <a href="<?= url_to('package', $package['slug']) ?>" id="sg-opt-<?= $optId++ ?>" role="option" class="bb-suggest__option">
            <span class="bb-suggest__name"><?= esc($package['title']) ?></span>
            <span class="bb-suggest__meta">
              <?= esc(implode(' · ', array_filter([$package['duration_label'] ?: null, 'From ' . money($package['price'], $package['currency'])]))) ?>
            </span>
          </a>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>

    <?php foreach ([
        ['Destinations', $destinations, 'destination'],
        ['Categories',   $categories,   'category'],
        ['Tour types',   $tourTypes,    'tour_type'],
    ] as [$label, $rows, $param]): ?>
      <?php if ($rows !== []): ?>
        <li class="bb-suggest__group" role="presentation"><?= esc($label) ?></li>
        <?php foreach ($rows as $row): ?>
          <li role="presentation">
            <a href="<?= url_to('packages') ?>?<?= esc($param, 'attr') ?>=<?= esc($row['slug'], 'url') ?>" id="sg-opt-<?= $optId++ ?>" role="option" class="bb-suggest__option">
              <span class="bb-suggest__name"><?= esc($row['name']) ?></span>
              <span class="bb-suggest__meta">Browse trips&nbsp;→</span>
            </a>
          </li>
        <?php endforeach; ?>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
