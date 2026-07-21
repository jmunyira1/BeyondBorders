<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="row g-3 mb-3">
  <?php foreach ([
      ['New enquiries', $stats['new'], 'Waiting for a reply', 'admin/enquiries?status=new'],
      ['Last 7 days', $stats['week'], 'Enquiries received', 'admin/enquiries'],
      ['Live packages', $stats['packages'], $counts['inactive'] . ' hidden', 'admin/packages'],
      ['Published posts', $stats['posts'], 'In the journal', 'admin/posts'],
  ] as [$label, $value, $note, $link]): ?>
    <div class="col-6 col-lg-3">
      <a class="bba-panel bba-stat d-block text-decoration-none h-100" href="<?= site_url($link) ?>">
        <p class="k mb-1"><?= esc($label) ?></p>
        <p class="v mb-0"><?= (int) $value ?></p>
        <p class="n mb-0"><?= esc($note) ?></p>
      </a>
    </div>
  <?php endforeach; ?>
</div>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="bba-panel">
      <div class="bba-panel-head">
        <h2>Latest enquiries</h2>
        <a class="btn btn-bba-outline btn-sm" href="<?= site_url('admin/enquiries') ?>">Open inbox</a>
      </div>
      <?php if ($latest === []): ?>
        <div class="bba-panel-body text-body-secondary">No enquiries yet.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table bba-table align-middle">
            <thead>
              <tr><th>Received</th><th>Name</th><th>Type</th><th>About</th><th>Status</th></tr>
            </thead>
            <tbody>
              <?php foreach ($latest as $row): ?>
                <tr>
                  <td class="text-nowrap text-body-secondary"><?= esc(date('j M, H:i', strtotime($row['created_at']))) ?></td>
                  <td>
                    <a href="<?= site_url('admin/enquiries?q=' . urlencode($row['email'] ?: $row['name'])) ?>" class="text-decoration-none fw-medium"><?= esc($row['name']) ?></a>
                    <div class="small text-body-secondary"><?= esc($row['email'] ?: $row['phone']) ?></div>
                  </td>
                  <td><span class="bba-pill bba-pill-read"><?= esc(\App\Models\EnquiryModel::LABELS[$row['type']] ?? $row['type']) ?></span></td>
                  <td class="text-body-secondary small"><?= esc($row['package_title'] ?: $row['subject'] ?: '—') ?></td>
                  <td><span class="bba-pill bba-pill-<?= esc($row['status']) ?>"><?= esc(ucfirst($row['status'])) ?></span></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="bba-panel mb-3">
      <div class="bba-panel-head"><h2>Enquiries by type</h2></div>
      <div class="bba-panel-body">
        <ul class="bba-meta-list mb-0">
          <?php foreach ($byType as $type => $n): ?>
            <li>
              <span class="k flex-grow-1"><?= esc(\App\Models\EnquiryModel::LABELS[$type] ?? $type) ?></span>
              <span class="v"><?= (int) $n ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <div class="bba-panel">
      <div class="bba-panel-head"><h2>Quick actions</h2></div>
      <div class="bba-panel-body d-grid gap-2">
        <a class="btn btn-bba-green btn-sm" href="<?= site_url('admin/packages/new') ?>"><i class="bi bi-plus-lg me-2"></i>Add a package</a>
        <a class="btn btn-bba-outline btn-sm" href="<?= site_url('admin/posts/new') ?>"><i class="bi bi-plus-lg me-2"></i>Write a post</a>
        <a class="btn btn-bba-outline btn-sm" href="<?= site_url('admin/gallery') ?>"><i class="bi bi-images me-2"></i>Manage gallery <span class="text-body-secondary">(<?= (int) $counts['gallery'] ?>)</span></a>
        <a class="btn btn-bba-outline btn-sm" href="<?= site_url('admin/settings') ?>"><i class="bi bi-sliders me-2"></i>Site settings</a>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
