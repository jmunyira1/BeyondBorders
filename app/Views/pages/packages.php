<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<header class="bb-pagehead">
  <div class="container">
    <h1>Ready-to-book journeys across Kenya</h1>
    <p class="bb-lede">Every package is clearly priced with inclusions listed. Narrow things down with the filters, or browse them all.</p>
  </div>
</header>

<!-- Category index — shortcuts into the same filter -->
<?php if ($categories !== []): ?>
<section class="pt-4 pb-0" aria-label="Filter by category">
  <div class="container">
    <div class="bb-index">
      <?php foreach ($categories as $category): ?>
        <?php // Sets the category select and re-runs the filter form in one go. ?>
        <button type="button"
                class="<?= $filters['category'] === $category['slug'] ? 'active' : '' ?>"
                data-category-tile="<?= esc($category['slug'], 'attr') ?>">
          <?= esc($category['name']) ?>
        </button>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Filter bar -->
<section class="pb-0">
  <div class="container">
    <form class="bba-filter p-3 p-lg-4"
          id="packages-filter"
          method="get"
          action="<?= url_to('packages') ?>"
          hx-get="<?= url_to('packages-filter') ?>"
          hx-target="#packages-results"
          hx-swap="innerHTML"
          hx-push-url="true"
          hx-trigger="change, search, submit, keyup from:#f-q delay:400ms changed"
          hx-indicator="#packages-results"
          aria-label="Filter packages">

      <div class="row g-3 align-items-end">
        <div class="col-12 col-lg-4">
          <label class="form-label" for="f-q">Search</label>
          <input class="form-control" type="search" id="f-q" name="q"
                 value="<?= esc($filters['q'], 'attr') ?>"
                 placeholder="Destination, park or keyword…"
                 autocomplete="off">
        </div>

        <div class="col-6 col-lg-2">
          <label class="form-label" for="f-category">Category</label>
          <select class="form-select" id="f-category" name="category">
            <option value="">All categories</option>
            <?php foreach ($categories as $row): ?>
              <option value="<?= esc($row['slug'], 'attr') ?>" <?= $filters['category'] === $row['slug'] ? 'selected' : '' ?>><?= esc($row['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-6 col-lg-2">
          <label class="form-label" for="f-destination">Destination</label>
          <select class="form-select" id="f-destination" name="destination">
            <option value="">All destinations</option>
            <?php foreach ($destinations as $row): ?>
              <option value="<?= esc($row['slug'], 'attr') ?>" <?= $filters['destination'] === $row['slug'] ? 'selected' : '' ?>><?= esc($row['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-6 col-lg-2">
          <label class="form-label" for="f-tour-type">Tour type</label>
          <select class="form-select" id="f-tour-type" name="tour_type">
            <option value="">All tour types</option>
            <?php foreach ($tourTypes as $row): ?>
              <option value="<?= esc($row['slug'], 'attr') ?>" <?= $filters['tour_type'] === $row['slug'] ? 'selected' : '' ?>><?= esc($row['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-6 col-lg-2">
          <label class="form-label" for="f-price">Price</label>
          <select class="form-select" id="f-price" name="price">
            <option value="">Any price</option>
            <?php foreach ($priceRanges as $key => $range): ?>
              <option value="<?= esc($key, 'attr') ?>" <?= $filters['price'] === $key ? 'selected' : '' ?>><?= esc($range['label']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-6 col-lg-3">
          <label class="form-label" for="f-duration">Duration</label>
          <select class="form-select" id="f-duration" name="duration">
            <option value="">Any duration</option>
            <?php foreach ($durationRanges as $key => $range): ?>
              <option value="<?= esc($key, 'attr') ?>" <?= $filters['duration'] === $key ? 'selected' : '' ?>><?= esc($range['label']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-6 col-lg-3">
          <label class="form-label" for="f-sort">Sort by</label>
          <select class="form-select" id="f-sort" name="sort">
            <?php foreach ($sorts as $key => $label): ?>
              <option value="<?= esc($key, 'attr') ?>" <?= ($filters['sort'] ?: 'recommended') === $key ? 'selected' : '' ?>><?= esc($label) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-12 col-lg-6 d-flex gap-2 justify-content-lg-end">
          <?php // Without JS this is the button that runs the filter. ?>
          <button type="submit" class="btn btn-bba-green flex-grow-1 flex-lg-grow-0">
            <i class="bi bi-search me-2" aria-hidden="true"></i>Filter
          </button>
          <a href="<?= url_to('packages') ?>"
             class="btn btn-bba-outline flex-grow-1 flex-lg-grow-0"
             hx-get="<?= url_to('packages-filter') ?>"
             hx-target="#packages-results"
             hx-push-url="<?= url_to('packages') ?>"
             id="filter-clear">Clear</a>
        </div>
      </div>
    </form>
  </div>
</section>

<!-- Results -->
<section id="packages">
  <div class="container">
    <div id="packages-results">
      <?= $this->include('packages/_results') ?>
    </div>
  </div>
</section>

<section class="bb-band">
  <div class="container">
    <h2>Don't see the trip you had in mind?</h2>
    <p>Every journey here can be adjusted — dates, group size, comfort level — or we can design one from scratch around your plans.</p>
    <a href="<?= url_to('custom-trips') ?>" class="btn btn-bba-gold">Plan a custom trip</a>
  </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Category tiles drive the same <select> the filter form submits, so there is
// exactly one source of truth for the active category.
document.querySelectorAll('[data-category-tile]').forEach(function (tile) {
  tile.addEventListener('click', function () {
    var select = document.getElementById('f-category');
    var slug = tile.dataset.categoryTile;
    select.value = (select.value === slug) ? '' : slug;
    htmx.trigger('#packages-filter', 'submit');
    document.querySelectorAll('[data-category-tile]').forEach(function (t) {
      t.classList.toggle('active', t.dataset.categoryTile === select.value);
    });
  });
});

// "Clear" resets the controls before htmx serialises the (now empty) form.
document.getElementById('filter-clear').addEventListener('click', function () {
  document.getElementById('packages-filter').reset();
  document.querySelectorAll('#packages-filter select').forEach(function (s) { s.value = ''; });
  document.getElementById('f-q').value = '';
  document.querySelectorAll('[data-category-tile]').forEach(function (t) { t.classList.remove('active'); });
});
</script>
<?= $this->endSection() ?>
