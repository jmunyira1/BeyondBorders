<?php /** @var array $rows */ ?>
<?php if ($rows === []): ?>
  <p class="text-body-secondary text-center py-5 mb-0">No testimonials yet — add the first one on the left.</p>
<?php else: ?>
  <div class="d-grid gap-3">
    <?php foreach ($rows as $row): ?>
      <form class="border rounded p-3" hx-post="<?= site_url('admin/testimonials/' . $row['id']) ?>" hx-target="#testimonial-list">
        <textarea class="form-control form-control-sm mb-2" name="quote" rows="3" aria-label="Quote"><?= esc($row['quote']) ?></textarea>
        <div class="row g-2 align-items-center">
          <div class="col-sm-4">
            <input class="form-control form-control-sm" name="author_name" type="text"
                   value="<?= esc($row['author_name'], 'attr') ?>" placeholder="Author" aria-label="Author">
          </div>
          <div class="col-sm-3">
            <input class="form-control form-control-sm" name="author_location" type="text"
                   value="<?= esc($row['author_location'], 'attr') ?>" placeholder="Location" aria-label="Location">
          </div>
          <div class="col-sm-2">
            <select class="form-select form-select-sm" name="rating" aria-label="Rating">
              <?php foreach ([5, 4, 3, 2, 1] as $n): ?>
                <option value="<?= $n ?>" <?= (int) $row['rating'] === $n ? 'selected' : '' ?>><?= $n ?>★</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-sm-3 d-flex gap-2 align-items-center">
            <input class="form-control form-control-sm" name="sort_order" type="number" style="width:4.2rem"
                   value="<?= (int) $row['sort_order'] ?>" aria-label="Sort order" title="Sort order">
            <div class="form-check form-switch mb-0">
              <input class="form-check-input" type="checkbox" name="is_active" value="1"
                     id="ta-<?= (int) $row['id'] ?>" <?= $row['is_active'] ? 'checked' : '' ?>>
              <label class="form-check-label small" for="ta-<?= (int) $row['id'] ?>">Shown</label>
            </div>
          </div>
        </div>
        <div class="d-flex gap-2 mt-2">
          <button type="submit" class="btn btn-bba-outline btn-sm">Save</button>
          <button type="button" class="btn btn-sm btn-outline-danger ms-auto"
                  hx-post="<?= site_url('admin/testimonials/' . $row['id'] . '/delete') ?>"
                  hx-target="#testimonial-list"
                  data-confirm="Delete this testimonial?" aria-label="Delete"><i class="bi bi-trash"></i></button>
        </div>
      </form>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
