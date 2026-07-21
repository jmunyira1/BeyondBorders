<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="bba-panel">
  <div class="bba-panel-head">
    <form class="d-flex flex-wrap gap-2 align-items-center w-100"
          id="enquiry-filter"
          hx-get="<?= site_url('admin/enquiries/list') ?>"
          hx-target="#enquiry-table"
          hx-swap="innerHTML"
          hx-trigger="change, keyup from:#e-q delay:400ms changed, submit"
          hx-push-url="false">
      <input class="form-control form-control-sm" style="max-width:16rem" type="search" id="e-q" name="q"
             value="<?= esc($filters['q'], 'attr') ?>" placeholder="Search name, email, phone…">

      <select class="form-select form-select-sm w-auto" name="type">
        <option value="">All types</option>
        <?php foreach (\App\Models\EnquiryModel::LABELS as $key => $label): ?>
          <option value="<?= esc($key, 'attr') ?>" <?= $filters['type'] === $key ? 'selected' : '' ?>><?= esc($label) ?></option>
        <?php endforeach; ?>
      </select>

      <select class="form-select form-select-sm w-auto" name="status">
        <option value="">All statuses</option>
        <?php foreach (['new' => 'New', 'read' => 'Read', 'replied' => 'Replied', 'closed' => 'Closed'] as $key => $label): ?>
          <option value="<?= esc($key, 'attr') ?>" <?= $filters['status'] === $key ? 'selected' : '' ?>><?= esc($label) ?></option>
        <?php endforeach; ?>
      </select>

      <a class="btn btn-bba-outline btn-sm ms-auto" href="<?= site_url('admin/enquiries') ?>">Reset</a>
    </form>
  </div>

  <div id="enquiry-table">
    <?= $this->include('admin/enquiries/_table') ?>
  </div>
</div>

<?= $this->include('admin/_modal') ?>

<?= $this->endSection() ?>
