<?php
/** @var array $rows */
$usageLabel = $typeKey === 'post-categories' ? 'posts' : 'packages';
?>
<?php if ($rows === []): ?>
  <div class="bba-panel-body text-body-secondary text-center py-5">Nothing here yet — add the first one on the left.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table bba-table align-middle mb-0">
      <thead>
        <tr>
          <th>Name</th>
          <th>Slug</th>
          <?php if (in_array('icon', $meta['fields'], true)): ?><th>Icon</th><?php endif; ?>
          <?php if (in_array('region', $meta['fields'], true)): ?><th>Region</th><?php endif; ?>
          <th class="text-end">In use</th>
          <th style="width:5rem">Order</th>
          <th>Shown</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
          <tr>
            <form hx-post="<?= site_url('admin/taxonomy/' . $typeKey . '/' . $row['id']) ?>" hx-target="#tax-list" id="tax-form-<?= (int) $row['id'] ?>"></form>

            <td><input class="form-control form-control-sm" form="tax-form-<?= (int) $row['id'] ?>" name="name" type="text" value="<?= esc($row['name'], 'attr') ?>" aria-label="Name"></td>
            <td><input class="form-control form-control-sm" form="tax-form-<?= (int) $row['id'] ?>" name="slug" type="text" value="<?= esc($row['slug'], 'attr') ?>" aria-label="Slug"></td>

            <?php if (in_array('icon', $meta['fields'], true)): ?>
              <td class="text-nowrap">
                <i class="bi <?= esc($row['icon'] ?: 'bi-compass', 'attr') ?> me-1" aria-hidden="true"></i>
                <input class="form-control form-control-sm d-inline-block" style="width:9rem" form="tax-form-<?= (int) $row['id'] ?>" name="icon" type="text" value="<?= esc($row['icon'], 'attr') ?>" aria-label="Icon">
              </td>
            <?php endif; ?>

            <?php if (in_array('region', $meta['fields'], true)): ?>
              <td><input class="form-control form-control-sm" form="tax-form-<?= (int) $row['id'] ?>" name="region" type="text" value="<?= esc($row['region'], 'attr') ?>" aria-label="Region"></td>
            <?php endif; ?>

            <td class="text-end small text-body-secondary text-nowrap"><?= (int) $row['usage'] ?> <?= esc($usageLabel) ?></td>

            <td><input class="form-control form-control-sm" form="tax-form-<?= (int) $row['id'] ?>" name="sort_order" type="number" value="<?= (int) $row['sort_order'] ?>" aria-label="Sort order"></td>

            <td>
              <div class="form-check form-switch mb-0">
                <input class="form-check-input" form="tax-form-<?= (int) $row['id'] ?>" type="checkbox" name="is_active" value="1"
                       id="xa-<?= (int) $row['id'] ?>" <?= $row['is_active'] ? 'checked' : '' ?>>
                <label class="visually-hidden" for="xa-<?= (int) $row['id'] ?>">Shown</label>
              </div>
            </td>

            <td class="text-end text-nowrap">
              <button type="submit" form="tax-form-<?= (int) $row['id'] ?>" class="btn btn-bba-outline btn-sm">Save</button>
              <button type="button" class="btn btn-sm btn-outline-danger"
                      hx-post="<?= site_url('admin/taxonomy/' . $typeKey . '/' . $row['id'] . '/delete') ?>"
                      hx-target="#tax-list"
                      data-confirm="Delete “<?= esc($row['name'], 'attr') ?>”? <?= (int) $row['usage'] ?> <?= esc($usageLabel) ?> will keep working but lose this label."
                      aria-label="Delete"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
