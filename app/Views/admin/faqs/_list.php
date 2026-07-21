<?php /** @var array $rows */ ?>
<?php if ($rows === []): ?>
  <p class="text-body-secondary text-center py-5 mb-0">No FAQs yet — add the first one on the left.</p>
<?php else: ?>
  <div class="d-grid gap-3">
    <?php foreach ($rows as $row): ?>
      <form class="border rounded p-3" hx-post="<?= site_url('admin/faqs/' . $row['id']) ?>" hx-target="#faq-list">
        <input class="form-control form-control-sm mb-2 fw-medium" name="question" type="text"
               value="<?= esc($row['question'], 'attr') ?>" aria-label="Question">
        <textarea class="form-control form-control-sm mb-2" name="answer" rows="4" aria-label="Answer"><?= esc($row['answer']) ?></textarea>
        <div class="d-flex gap-2 align-items-center">
          <input class="form-control form-control-sm" name="sort_order" type="number" style="width:4.5rem"
                 value="<?= (int) $row['sort_order'] ?>" aria-label="Sort order" title="Sort order">
          <div class="form-check form-switch mb-0">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                   id="fa-<?= (int) $row['id'] ?>" <?= $row['is_active'] ? 'checked' : '' ?>>
            <label class="form-check-label small" for="fa-<?= (int) $row['id'] ?>">Shown</label>
          </div>
          <button type="submit" class="btn btn-bba-outline btn-sm ms-auto">Save</button>
          <button type="button" class="btn btn-sm btn-outline-danger"
                  hx-post="<?= site_url('admin/faqs/' . $row['id'] . '/delete') ?>"
                  hx-target="#faq-list"
                  data-confirm="Delete this FAQ?" aria-label="Delete"><i class="bi bi-trash"></i></button>
        </div>
      </form>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
