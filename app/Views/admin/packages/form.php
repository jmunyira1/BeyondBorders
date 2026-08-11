<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<?php
$isNew  = $package === null || empty($package['id']);
$action = $isNew ? site_url('admin/packages') : site_url('admin/packages/' . $package['id']);
$v      = static fn (string $k, $d = '') => esc((string) ($package[$k] ?? $d), 'attr');
$bad    = static fn (string $k): string => isset($errors[$k]) ? ' is-invalid' : '';
$checked = static fn (string $k, bool $default): string => (
    ($package[$k] ?? ($default ? 1 : 0)) ? 'checked' : ''
);
?>

<form method="post" action="<?= $action ?>" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <?php if ($errors !== []): ?>
    <div class="bba-alert bba-alert-error mb-3">Please check the highlighted fields below.</div>
  <?php endif; ?>

  <div class="row g-3">
    <!-- Main column -->
    <div class="col-lg-8">
      <div class="bba-panel mb-3">
        <div class="bba-panel-head"><h2>Details</h2></div>
        <div class="bba-panel-body">
          <div class="mb-3">
            <label class="form-label" for="f-title">Title <span class="text-danger">*</span></label>
            <input class="form-control<?= $bad('title') ?>" id="f-title" name="title" type="text"
                   value="<?= $v('title') ?>" data-slug-source required>
            <?php if (isset($errors['title'])): ?><span class="bba-field-error"><?= esc($errors['title']) ?></span><?php endif; ?>
          </div>

          <div class="mb-3">
            <label class="form-label" for="f-slug">URL slug</label>
            <div class="input-group">
              <span class="input-group-text small">/packages/</span>
              <input class="form-control" id="f-slug" name="slug" type="text" value="<?= $v('slug') ?>" data-slug-target>
            </div>
            <div class="form-text">Leave blank to generate from the title. Changing this changes the public URL.</div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="f-summary">Card summary</label>
            <textarea class="form-control" id="f-summary" name="summary" rows="2"
                      placeholder="One or two sentences — this is what shows on the package card."><?= esc((string) ($package['summary'] ?? '')) ?></textarea>
          </div>

          <div class="mb-0">
            <label class="form-label" for="f-description">Full description</label>
            <textarea class="form-control" id="f-description" name="description" rows="10"
                      placeholder="Leave a blank line between paragraphs."><?= esc((string) ($package['description'] ?? '')) ?></textarea>
            <div class="form-text">Plain text. A blank line starts a new paragraph.</div>
          </div>
        </div>
      </div>

      <!-- Inclusions -->
      <div class="bba-panel mb-3">
        <div class="bba-panel-head">
          <h2>What's included</h2>
          <button type="button" class="btn btn-bba-outline btn-sm" id="add-inclusion"><i class="bi bi-plus-lg me-1"></i>Add row</button>
        </div>
        <div class="bba-panel-body">
          <p class="small text-body-secondary">Untick "Included" to list an item under <em>Not included</em> on the public page.</p>
          <div id="inclusion-rows">
            <?php foreach ($inclusions ?: [['item' => '', 'is_included' => 1]] as $row): ?>
              <div class="d-flex gap-2 align-items-center mb-2 inclusion-row">
                <input class="form-control form-control-sm" name="inclusion_item[]" type="text"
                       value="<?= esc((string) $row['item'], 'attr') ?>" placeholder="Park entry fees">
                <select class="form-select form-select-sm" name="inclusion_included[]" style="width:8.5rem;flex:0 0 8.5rem">
                  <option value="1" <?= (int) $row['is_included'] === 1 ? 'selected' : '' ?>>Included</option>
                  <option value="0" <?= (int) $row['is_included'] === 0 ? 'selected' : '' ?>>Not included</option>
                </select>
                <button type="button" class="btn btn-sm btn-outline-danger remove-inclusion" aria-label="Remove"><i class="bi bi-trash"></i></button>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Departures -->
      <div class="bba-panel mb-3">
        <div class="bba-panel-head">
          <h2>Departure dates</h2>
          <button type="button" class="btn btn-bba-outline btn-sm" id="add-departure"><i class="bi bi-plus-lg me-1"></i>Add date</button>
        </div>
        <div class="bba-panel-body">
          <p class="small text-body-secondary">Scheduled departures shown on the public page. Set spots per date; “Mark booked” on an enquiry takes a spot off the chosen date. Leave empty for flexible-date trips.</p>
          <div id="departure-rows">
            <?php foreach ($departures ?: [['depart_date' => '', 'return_date' => '', 'spots' => '', 'note' => '']] as $row): ?>
              <div class="row g-2 align-items-center mb-2 departure-row">
                <div class="col-6 col-md-3">
                  <input class="form-control form-control-sm" name="departure_date[]" type="date" value="<?= esc((string) ($row['depart_date'] ?? ''), 'attr') ?>" aria-label="Departure date">
                </div>
                <div class="col-6 col-md-3">
                  <input class="form-control form-control-sm" name="departure_return[]" type="date" value="<?= esc((string) ($row['return_date'] ?? ''), 'attr') ?>" aria-label="Return date" title="Return (optional)">
                </div>
                <div class="col-4 col-md-2">
                  <input class="form-control form-control-sm" name="departure_spots[]" type="number" min="0" value="<?= esc((string) ($row['spots'] ?? ''), 'attr') ?>" placeholder="Spots" aria-label="Spots">
                </div>
                <div class="col-6 col-md-3">
                  <input class="form-control form-control-sm" name="departure_note[]" type="text" value="<?= esc((string) ($row['note'] ?? ''), 'attr') ?>" placeholder="Note (optional)" aria-label="Note">
                </div>
                <div class="col-2 col-md-1">
                  <button type="button" class="btn btn-sm btn-outline-danger remove-departure w-100" aria-label="Remove"><i class="bi bi-trash"></i></button>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
      <div class="bba-panel mb-3">
        <div class="bba-panel-head"><h2>Publishing</h2></div>
        <div class="bba-panel-body">
          <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" id="f-active" name="is_active" value="1" <?= $checked('is_active', true) ?>>
            <label class="form-check-label" for="f-active">Live on the site</label>
          </div>
          <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="f-featured" name="is_featured" value="1" <?= $checked('is_featured', false) ?>>
            <label class="form-check-label" for="f-featured">Feature on the homepage</label>
          </div>
          <label class="form-label" for="f-sort">Sort order</label>
          <input class="form-control" id="f-sort" name="sort_order" type="number" value="<?= $v('sort_order', '0') ?>">
          <div class="form-text">Lower numbers appear first.</div>
        </div>
      </div>

      <div class="bba-panel mb-3">
        <div class="bba-panel-head"><h2>Classification</h2></div>
        <div class="bba-panel-body">
          <?php foreach ([
              ['category_id', 'Category', $categories],
              ['destination_id', 'Destination', $destinations],
              ['tour_type_id', 'Tour type', $tourTypes],
          ] as [$field, $label, $options]): ?>
            <div class="mb-3">
              <label class="form-label" for="f-<?= $field ?>"><?= esc($label) ?></label>
              <select class="form-select" id="f-<?= $field ?>" name="<?= $field ?>">
                <option value="">— none —</option>
                <?php foreach ($options as $option): ?>
                  <option value="<?= (int) $option['id'] ?>" <?= (string) ($package[$field] ?? '') === (string) $option['id'] ? 'selected' : '' ?>><?= esc($option['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php endforeach; ?>
          <p class="small text-body-secondary mb-0">These three drive the public filter.</p>
        </div>
      </div>

      <div class="bba-panel mb-3">
        <div class="bba-panel-head"><h2>Availability &amp; booking</h2></div>
        <div class="bba-panel-body">
          <div class="mb-3">
            <label class="form-label" for="f-availability">Availability</label>
            <select class="form-select" id="f-availability" name="availability">
              <?php foreach (\App\Models\PackageModel::AVAILABILITY as $key => $label): ?>
                <option value="<?= esc($key, 'attr') ?>" <?= ($package['availability'] ?? 'available') === $key ? 'selected' : '' ?>><?= esc($label) ?></option>
              <?php endforeach; ?>
            </select>
            <div class="form-text">“Sold out” and “Coming soon” turn the booking form into a waitlist / interest form.</div>
          </div>
          <div class="row g-2 mb-0">
            <div class="col-6">
              <label class="form-label" for="f-spots">Spots left</label>
              <input class="form-control" id="f-spots" name="spots_available" type="number" min="0"
                     value="<?= $v('spots_available') ?>" placeholder="Optional">
            </div>
            <div class="col-6">
              <label class="form-label" for="f-deposit">Deposit (KES)</label>
              <input class="form-control" id="f-deposit" name="deposit_amount" type="number" step="0.01" min="0"
                     value="<?= $v('deposit_amount') ?>" placeholder="e.g. 5000">
            </div>
          </div>
          <div class="form-text mt-1">Deposit = minimum payment to secure a spot. Payment details come from Settings → Payment.</div>
        </div>
      </div>

      <div class="bba-panel mb-3">
        <div class="bba-panel-head"><h2>Location &amp; lodging</h2></div>
        <div class="bba-panel-body">
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label" for="f-region">Region</label>
              <input class="form-control" id="f-region" name="region" type="text" value="<?= $v('region') ?>" placeholder="Rift Valley">
            </div>
            <div class="col-6">
              <label class="form-label" for="f-county">County</label>
              <input class="form-control" id="f-county" name="county" type="text" value="<?= $v('county') ?>" placeholder="Narok">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="f-entrance">Entrance fee (KES)</label>
            <input class="form-control" id="f-entrance" name="entrance_fee" type="number" step="0.01" min="0"
                   value="<?= $v('entrance_fee') ?>" placeholder="e.g. 3000">
            <div class="form-text">Per-person park / site entry. Leave blank if none.</div>
          </div>
          <div class="row g-2 mb-2">
            <div class="col-7">
              <label class="form-label" for="f-hotel">Nearby hotel</label>
              <input class="form-control" id="f-hotel" name="nearby_hotel" type="text" value="<?= $v('nearby_hotel') ?>" placeholder="Mara Serena">
            </div>
            <div class="col-5">
              <label class="form-label" for="f-hotel-rate">Rate (KES)</label>
              <input class="form-control" id="f-hotel-rate" name="hotel_rate" type="number" step="0.01" min="0" value="<?= $v('hotel_rate') ?>" placeholder="per night">
            </div>
          </div>
          <div class="row g-2 mb-0">
            <div class="col-7">
              <label class="form-label" for="f-cottage">Nearby cottage</label>
              <input class="form-control" id="f-cottage" name="nearby_cottage" type="text" value="<?= $v('nearby_cottage') ?>" placeholder="Mara Eden Cottages">
            </div>
            <div class="col-5">
              <label class="form-label" for="f-cottage-rate">Rate (KES)</label>
              <input class="form-control" id="f-cottage-rate" name="cottage_rate" type="number" step="0.01" min="0" value="<?= $v('cottage_rate') ?>" placeholder="per night">
            </div>
          </div>
        </div>
      </div>

      <div class="bba-panel mb-3">
        <div class="bba-panel-head"><h2>Price &amp; duration</h2></div>
        <div class="bba-panel-body">
          <div class="row g-2 mb-3">
            <div class="col-4">
              <label class="form-label" for="f-currency">Currency</label>
              <input class="form-control" id="f-currency" name="currency" type="text" value="<?= $v('currency', 'KES') ?>">
            </div>
            <div class="col-8">
              <label class="form-label" for="f-price">Price from <span class="text-danger">*</span></label>
              <input class="form-control<?= $bad('price') ?>" id="f-price" name="price" type="number" step="0.01" min="0" value="<?= $v('price', '0') ?>">
              <?php if (isset($errors['price'])): ?><span class="bba-field-error"><?= esc($errors['price']) ?></span><?php endif; ?>
            </div>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label" for="f-days">Days</label>
              <input class="form-control" id="f-days" name="duration_days" type="number" min="1" value="<?= $v('duration_days', '1') ?>">
            </div>
            <div class="col-6">
              <label class="form-label" for="f-nights">Nights</label>
              <input class="form-control" id="f-nights" name="duration_nights" type="number" min="0" value="<?= $v('duration_nights', '0') ?>">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="f-duration-label">Duration label</label>
            <input class="form-control" id="f-duration-label" name="duration_label" type="text" value="<?= $v('duration_label') ?>" placeholder="Auto">
            <div class="form-text">Leave blank to build it from days and nights.</div>
          </div>

          <div class="mb-0">
            <label class="form-label" for="f-group">Group size</label>
            <input class="form-control" id="f-group" name="group_size" type="text" value="<?= $v('group_size') ?>" placeholder="2 – 7 travelers">
          </div>
        </div>
      </div>

      <div class="bba-panel mb-3">
        <div class="bba-panel-head"><h2>Image</h2></div>
        <div class="bba-panel-body">
          <img class="bba-img-preview mb-3" id="img-preview"
               src="<?= esc(media_url($package['image'] ?? null), 'attr') ?>" alt="">
          <div class="mb-3">
            <label class="form-label" for="f-image">Upload a photo</label>
            <input class="form-control" id="f-image" name="image_file" type="file" accept="image/*" data-preview="#img-preview">
            <div class="form-text">JPEG, PNG or WebP, up to 8&nbsp;MB. Resized to 1600px automatically.</div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="f-image-url">…or paste an image URL</label>
            <input class="form-control" id="f-image-url" name="image_url" type="url" placeholder="https://…"
                   value="<?= str_contains((string) ($package['image'] ?? ''), '://') ? $v('image') : '' ?>">
          </div>
          <div class="mb-0">
            <label class="form-label" for="f-alt">Image description</label>
            <input class="form-control" id="f-alt" name="image_alt" type="text" value="<?= $v('image_alt') ?>"
                   placeholder="Elephants grazing with Kilimanjaro behind">
            <div class="form-text">Read aloud by screen readers.</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex gap-2 mt-3">
    <button type="submit" class="btn btn-bba-green"><?= $isNew ? 'Create package' : 'Save changes' ?></button>
    <a class="btn btn-bba-outline" href="<?= site_url('admin/packages') ?>">Cancel</a>
    <?php if (! $isNew): ?>
      <a class="btn btn-bba-outline ms-auto" target="_blank" rel="noopener" href="<?= site_url('packages/' . ($package['slug'] ?? '')) ?>">
        <i class="bi bi-box-arrow-up-right me-1"></i>View on site
      </a>
    <?php endif; ?>
  </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Repeatable inclusion rows.
document.getElementById('add-inclusion').addEventListener('click', function () {
  var rows = document.getElementById('inclusion-rows');
  var first = rows.querySelector('.inclusion-row');
  var clone = first.cloneNode(true);
  clone.querySelector('input').value = '';
  clone.querySelector('select').value = '1';
  rows.appendChild(clone);
  clone.querySelector('input').focus();
});

document.getElementById('inclusion-rows').addEventListener('click', function (e) {
  var btn = e.target.closest('.remove-inclusion');
  if (!btn) return;
  var rows = document.querySelectorAll('.inclusion-row');
  if (rows.length === 1) {
    // Keep one empty row rather than leaving the section with nothing to clone.
    rows[0].querySelector('input').value = '';
  } else {
    btn.closest('.inclusion-row').remove();
  }
});

// Repeatable departure rows.
document.getElementById('add-departure').addEventListener('click', function () {
  var rows = document.getElementById('departure-rows');
  var clone = rows.querySelector('.departure-row').cloneNode(true);
  clone.querySelectorAll('input').forEach(function (i) { i.value = ''; });
  rows.appendChild(clone);
  clone.querySelector('input').focus();
});

document.getElementById('departure-rows').addEventListener('click', function (e) {
  var btn = e.target.closest('.remove-departure');
  if (!btn) return;
  var rows = document.querySelectorAll('.departure-row');
  if (rows.length === 1) {
    rows[0].querySelectorAll('input').forEach(function (i) { i.value = ''; });
  } else {
    btn.closest('.departure-row').remove();
  }
});
</script>
<?= $this->endSection() ?>
