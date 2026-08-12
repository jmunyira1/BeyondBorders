<?php
/** @var array $row */
$labels = \App\Models\EnquiryModel::LABELS;

$fields = array_filter([
    'Type'         => $labels[$row['type']] ?? $row['type'],
    'Received'     => date('j F Y \a\t H:i', strtotime($row['created_at'])),
    'Package'      => $row['package_title'] ?? null,
    'Subject'      => $row['subject'] ?? null,
    'Trip type'    => $row['trip_type'] ?? null,
    'Travelers'    => $row['people'] ?? null,
    'Travel dates' => $row['travel_dates'] ?? null,
    'Budget'       => $row['budget'] ?? null,
]);
?>
<div class="modal-header">
  <div>
    <h2 class="modal-title h5 mb-0"><?= esc($row['name']) ?></h2>
    <p class="small text-body-secondary mb-0"><?= esc($labels[$row['type']] ?? $row['type']) ?> · <?= esc(date('j M Y, H:i', strtotime($row['created_at']))) ?></p>
  </div>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
  <div class="d-flex flex-wrap gap-2 mb-4">
    <?php if ($row['email']): ?>
      <a class="btn btn-bba-green btn-sm" href="mailto:<?= esc($row['email'], 'attr') ?>?subject=<?= esc(rawurlencode('Re: ' . ($row['subject'] ?: 'Your enquiry')), 'attr') ?>">
        <i class="bi bi-envelope me-1" aria-hidden="true"></i>Reply by email
      </a>
    <?php endif; ?>
    <?php if ($row['phone']): ?>
      <a class="btn btn-bba-outline btn-sm" target="_blank" rel="noopener"
         href="<?= esc(whatsapp_link('Hi ' . $row['name'] . ', thank you for your enquiry with Beyond Borders Adventures.', $row['phone']), 'attr') ?>">
        <i class="bi bi-whatsapp me-1" aria-hidden="true"></i>WhatsApp
      </a>
      <a class="btn btn-bba-outline btn-sm" href="tel:<?= esc($row['phone'], 'attr') ?>">
        <i class="bi bi-telephone me-1" aria-hidden="true"></i>Call
      </a>
    <?php endif; ?>
    <?php if (! empty($row['package_slug'])): ?>
      <a class="btn btn-bba-outline btn-sm" target="_blank" rel="noopener" href="<?= site_url('packages/' . $row['package_slug']) ?>">
        <i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i>View package
      </a>
    <?php endif; ?>
  </div>

  <ul class="bba-meta-list mb-4">
    <?php foreach ($fields as $k => $v): ?>
      <li><span class="k"><?= esc($k) ?></span><span class="v"><?= esc($v) ?></span></li>
    <?php endforeach; ?>
    <?php if ($row['email']): ?>
      <li><span class="k">Email</span><span class="v"><a href="mailto:<?= esc($row['email'], 'attr') ?>"><?= esc($row['email']) ?></a></span></li>
    <?php endif; ?>
    <?php if ($row['phone']): ?>
      <li><span class="k">Phone</span><span class="v"><?= esc($row['phone']) ?></span></li>
    <?php endif; ?>
  </ul>

  <?php if ($row['message']): ?>
    <p class="bba-eyebrow mb-2">Message</p>
    <div class="bba-panel-body border rounded mb-4" style="background:var(--bba-sand)">
      <?= nl2paras($row['message'], 'mb-2') ?>
    </div>
  <?php endif; ?>

  <form hx-post="<?= site_url('admin/enquiries/' . $row['id'] . '/notes') ?>" hx-swap="none">
    <label class="form-label" for="admin-notes">Internal notes</label>
    <textarea class="form-control mb-2" id="admin-notes" name="admin_notes" rows="3"
              placeholder="Quoted 24 Jul, waiting on their dates…"><?= esc($row['admin_notes']) ?></textarea>
    <div class="d-flex justify-content-between align-items-center">
      <span class="small text-body-secondary">Only visible here.</span>
      <button type="submit" class="btn btn-bba-green btn-sm">Save note</button>
    </div>
  </form>
</div>

<div class="modal-footer justify-content-between">
  <div class="d-flex align-items-center gap-3">
    <button class="btn bba-btn-danger btn-sm"
            hx-post="<?= site_url('admin/enquiries/' . $row['id'] . '/delete') ?>"
            hx-target="#enquiry-table"
            data-confirm="Delete this enquiry permanently? This cannot be undone."
            data-bs-dismiss="modal"><i class="bi bi-trash3 me-1" aria-hidden="true"></i>Delete</button>
    <span class="small text-body-secondary"><?= esc($row['ip_address'] ?: '') ?></span>
  </div>
  <div class="d-flex gap-2 flex-wrap">
    <?php foreach (['replied' => 'Mark replied', 'closed' => 'Mark closed'] as $status => $label): ?>
      <button class="btn btn-bba-outline btn-sm"
              hx-post="<?= site_url('admin/enquiries/' . $row['id'] . '/status') ?>"
              hx-vals='<?= json_encode(['status' => $status]) ?>'
              hx-target="#enquiry-table"
              data-bs-dismiss="modal"><?= esc($label) ?></button>
    <?php endforeach; ?>
    <?php if ($row['status'] !== 'booked'): ?>
      <button class="btn btn-bba-green btn-sm"
              hx-post="<?= site_url('admin/enquiries/' . $row['id'] . '/status') ?>"
              hx-vals='<?= json_encode(['status' => 'booked']) ?>'
              hx-target="#enquiry-table"
              data-confirm="Mark as booked? This takes one spot off the trip's availability."
              data-bs-dismiss="modal"><i class="bi bi-check2-circle me-1" aria-hidden="true"></i>Mark booked</button>
    <?php endif; ?>
  </div>
</div>
