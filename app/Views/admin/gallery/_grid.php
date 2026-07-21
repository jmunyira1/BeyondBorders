<?php /** @var array $rows */ ?>
<?php if ($rows === []): ?>
  <p class="text-body-secondary text-center py-5 mb-0">No images yet — add the first one on the left.</p>
<?php else: ?>
  <div class="row g-3">
    <?php foreach ($rows as $row): ?>
      <div class="col-sm-6 col-xl-4">
        <form class="border rounded p-2 h-100 d-flex flex-column"
              hx-post="<?= site_url('admin/gallery/' . $row['id']) ?>"
              hx-target="#gallery-grid">
          <img src="<?= esc(media_url($row['path']), 'attr') ?>" alt="<?= esc($row['alt'], 'attr') ?>"
               class="w-100 rounded mb-2" style="aspect-ratio:4/3;object-fit:cover" loading="lazy">

          <input class="form-control form-control-sm mb-2" name="caption" type="text"
                 value="<?= esc($row['caption'], 'attr') ?>" placeholder="Caption" aria-label="Caption">
          <input class="form-control form-control-sm mb-2" name="alt" type="text"
                 value="<?= esc($row['alt'], 'attr') ?>" placeholder="Image description" aria-label="Image description">

          <div class="d-flex gap-2 align-items-center mt-auto">
            <input class="form-control form-control-sm" name="sort_order" type="number" style="width:4.5rem"
                   value="<?= (int) $row['sort_order'] ?>" aria-label="Sort order" title="Sort order">
            <div class="form-check form-switch mb-0">
              <input class="form-check-input" type="checkbox" name="is_active" value="1"
                     id="ga-<?= (int) $row['id'] ?>" <?= $row['is_active'] ? 'checked' : '' ?>>
              <label class="form-check-label small" for="ga-<?= (int) $row['id'] ?>">Shown</label>
            </div>
            <button type="submit" class="btn btn-bba-outline btn-sm ms-auto" aria-label="Save"><i class="bi bi-check-lg"></i></button>
            <button type="button" class="btn btn-sm btn-outline-danger"
                    hx-post="<?= site_url('admin/gallery/' . $row['id'] . '/delete') ?>"
                    hx-target="#gallery-grid"
                    data-confirm="Remove this image from the gallery?" aria-label="Delete"><i class="bi bi-trash"></i></button>
          </div>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
