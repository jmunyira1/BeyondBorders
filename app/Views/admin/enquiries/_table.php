<?php
/** @var array $rows */
$labels = \App\Models\EnquiryModel::LABELS;
?>
<?php if ($rows === []): ?>
  <div class="bba-panel-body text-center text-body-secondary py-5">
    <i class="bi bi-inbox d-block mb-2" style="font-size:2rem" aria-hidden="true"></i>
    No enquiries match those filters.
  </div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table bba-table align-middle mb-0">
      <thead>
        <tr>
          <th>Received</th>
          <th>From</th>
          <th>Type</th>
          <th>About</th>
          <th>Status</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
          <tr<?= $row['status'] === 'new' ? ' style="background:rgba(216,117,43,0.04)"' : '' ?>>
            <td class="text-nowrap text-body-secondary small">
              <?= esc(date('j M Y', strtotime($row['created_at']))) ?><br>
              <?= esc(date('H:i', strtotime($row['created_at']))) ?>
            </td>
            <td>
              <span class="fw-medium"><?= esc($row['name']) ?></span>
              <div class="small text-body-secondary">
                <?php if ($row['email']): ?>
                  <a href="mailto:<?= esc($row['email'], 'attr') ?>" class="text-decoration-none"><?= esc($row['email']) ?></a><br>
                <?php endif; ?>
                <?php if ($row['phone']): ?>
                  <a href="<?= esc(whatsapp_link(null, $row['phone']), 'attr') ?>" target="_blank" rel="noopener" class="text-decoration-none">
                    <i class="bi bi-whatsapp" aria-hidden="true"></i> <?= esc($row['phone']) ?>
                  </a>
                <?php endif; ?>
              </div>
            </td>
            <td><span class="bba-pill bba-pill-read"><?= esc($labels[$row['type']] ?? $row['type']) ?></span></td>
            <td class="small text-body-secondary" style="max-width:18rem">
              <?= esc($row['package_title'] ?: $row['subject'] ?: '—') ?>
              <?php if ($row['message']): ?>
                <div class="text-truncate"><?= esc(excerpt_of($row['message'], 70)) ?></div>
              <?php endif; ?>
            </td>
            <td><span class="bba-pill bba-pill-<?= esc($row['status']) ?>"><?= esc(ucfirst($row['status'])) ?></span></td>
            <td class="text-end text-nowrap">
              <button class="btn btn-bba-outline btn-sm"
                      hx-get="<?= site_url('admin/enquiries/' . $row['id']) ?>"
                      hx-target="#bba-modal-body">Open</button>

              <div class="btn-group">
                <button class="btn btn-bba-outline btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More actions"></button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <?php foreach (['booked' => 'Mark booked', 'replied' => 'Mark replied', 'closed' => 'Mark closed', 'new' => 'Mark unread'] as $status => $label): ?>
                    <li>
                      <button class="dropdown-item"
                              hx-post="<?= site_url('admin/enquiries/' . $row['id'] . '/status') ?>"
                              hx-vals='<?= json_encode(['status' => $status]) ?>'
                              hx-target="#enquiry-table"><?= esc($label) ?></button>
                    </li>
                  <?php endforeach; ?>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <button class="dropdown-item text-danger"
                            hx-post="<?= site_url('admin/enquiries/' . $row['id'] . '/delete') ?>"
                            hx-target="#enquiry-table"
                            data-confirm="Delete this enquiry from <?= esc($row['name'], 'attr') ?>?">Delete</button>
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
      <span class="small text-body-secondary"><?= (int) $total ?> enquiries</span>
      <nav class="bba-pager"><?= $pager->links('default', 'bba_pager_plain') ?></nav>
    </div>
  <?php endif; ?>
<?php endif; ?>
