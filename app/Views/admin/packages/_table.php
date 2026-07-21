<?php /** @var array $rows */ ?>
<?php if ($rows === []): ?>
  <div class="bba-panel-body text-center text-body-secondary py-5">
    <i class="bi bi-compass d-block mb-2" style="font-size:2rem" aria-hidden="true"></i>
    No packages match those filters.
    <div class="mt-3"><a class="btn btn-bba-green btn-sm" href="<?= site_url('admin/packages/new') ?>">Add the first one</a></div>
  </div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table bba-table align-middle mb-0">
      <thead>
        <tr>
          <th style="width:70px">Image</th>
          <th>Title</th>
          <th>Category</th>
          <th>Destination</th>
          <th class="text-nowrap">Duration</th>
          <th class="text-end">Price</th>
          <th>Status</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
          <tr id="package-row-<?= (int) $row['id'] ?>">
            <td><img class="bba-thumb" src="<?= esc(media_url($row['image']), 'attr') ?>" alt="" loading="lazy"></td>
            <td>
              <a class="fw-medium text-decoration-none" href="<?= site_url('admin/packages/' . $row['id'] . '/edit') ?>"><?= esc($row['title']) ?></a>
              <div class="small text-body-secondary">/<?= esc($row['slug']) ?></div>
            </td>
            <td class="small"><?= esc($row['category_name'] ?: '—') ?></td>
            <td class="small"><?= esc($row['destination_name'] ?: '—') ?></td>
            <td class="small text-nowrap"><?= esc($row['duration_label'] ?: '—') ?></td>
            <td class="text-end text-nowrap"><?= esc(money($row['price'], $row['currency'])) ?></td>
            <td class="text-nowrap">
              <span class="bba-pill bba-pill-<?= $row['is_active'] ? 'on' : 'off' ?>"><?= $row['is_active'] ? 'Live' : 'Hidden' ?></span>
              <?php if ($row['is_featured']): ?>
                <span class="bba-pill bba-pill-featured">Featured</span>
              <?php endif; ?>
            </td>
            <td class="text-end text-nowrap">
              <a class="btn btn-bba-outline btn-sm" href="<?= site_url('admin/packages/' . $row['id'] . '/edit') ?>">Edit</a>
              <div class="btn-group">
                <button class="btn btn-bba-outline btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More actions"></button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" target="_blank" rel="noopener" href="<?= site_url('packages/' . $row['slug']) ?>">View on site</a></li>
                  <li>
                    <button class="dropdown-item"
                            hx-post="<?= site_url('admin/packages/' . $row['id'] . '/toggle') ?>"
                            hx-target="#package-table"><?= $row['is_active'] ? 'Hide from site' : 'Publish' ?></button>
                  </li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <button class="dropdown-item text-danger"
                            hx-post="<?= site_url('admin/packages/' . $row['id'] . '/delete') ?>"
                            hx-target="#package-table"
                            data-confirm="Delete “<?= esc($row['title'], 'attr') ?>”? Existing enquiries will keep their reference.">Delete</button>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php if ($pager->getPageCount('default') > 1): ?>
    <div class="bba-panel-body d-flex justify-content-between align-items-center flex-wrap gap-2">
      <span class="small text-body-secondary"><?= (int) $total ?> packages</span>
      <nav class="bba-pager"><?= $pager->links('default', 'bba_pager_plain') ?></nav>
    </div>
  <?php endif; ?>
<?php endif; ?>
