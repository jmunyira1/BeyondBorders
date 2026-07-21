<?php /** @var array $rows */ ?>
<?php if ($rows === []): ?>
  <div class="bba-panel-body text-center text-body-secondary py-5">
    <i class="bi bi-journal-text d-block mb-2" style="font-size:2rem" aria-hidden="true"></i>
    No posts match those filters.
    <div class="mt-3"><a class="btn btn-bba-green btn-sm" href="<?= site_url('admin/posts/new') ?>">Write the first one</a></div>
  </div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table bba-table align-middle mb-0">
      <thead>
        <tr><th style="width:70px">Image</th><th>Title</th><th>Category</th><th>Published</th><th>Status</th><th class="text-end">Actions</th></tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
          <tr>
            <td><img class="bba-thumb" src="<?= esc(media_url($row['image']), 'attr') ?>" alt="" loading="lazy"></td>
            <td>
              <a class="fw-medium text-decoration-none" href="<?= site_url('admin/posts/' . $row['id'] . '/edit') ?>"><?= esc($row['title']) ?></a>
              <div class="small text-body-secondary"><?= esc(excerpt_of($row['excerpt'] ?: $row['body'], 70)) ?></div>
            </td>
            <td class="small"><?= esc($row['category_name'] ?: '—') ?></td>
            <td class="small text-nowrap"><?= esc(date('j M Y', strtotime($row['published_at']))) ?></td>
            <td><span class="bba-pill bba-pill-<?= $row['is_published'] ? 'on' : 'off' ?>"><?= $row['is_published'] ? 'Published' : 'Draft' ?></span></td>
            <td class="text-end text-nowrap">
              <a class="btn btn-bba-outline btn-sm" href="<?= site_url('admin/posts/' . $row['id'] . '/edit') ?>">Edit</a>
              <a class="btn btn-bba-outline btn-sm" target="_blank" rel="noopener" href="<?= site_url('blog/' . $row['slug']) ?>" aria-label="View on site"><i class="bi bi-box-arrow-up-right"></i></a>
              <button class="btn btn-sm btn-outline-danger"
                      hx-post="<?= site_url('admin/posts/' . $row['id'] . '/delete') ?>"
                      hx-target="#post-table"
                      data-confirm="Delete “<?= esc($row['title'], 'attr') ?>”?" aria-label="Delete"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php if ($pager->getPageCount('default') > 1): ?>
    <div class="bba-panel-body d-flex justify-content-between align-items-center flex-wrap gap-2">
      <span class="small text-body-secondary"><?= (int) $total ?> posts</span>
      <nav class="bba-pager"><?= $pager->links('default', 'bba_pager_plain') ?></nav>
    </div>
  <?php endif; ?>
<?php endif; ?>
