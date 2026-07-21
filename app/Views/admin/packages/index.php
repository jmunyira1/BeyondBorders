<?= $this->extend('admin/layout') ?>

<?= $this->section('actions') ?>
<a class="btn btn-bba-green btn-sm" href="<?= site_url('admin/packages/new') ?>"><i class="bi bi-plus-lg me-1"></i>New package</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="bba-panel">
  <div class="bba-panel-head">
    <form class="d-flex flex-wrap gap-2 align-items-center w-100"
          hx-get="<?= site_url('admin/packages/list') ?>"
          hx-target="#package-table"
          hx-trigger="change, keyup from:#p-q delay:400ms changed, submit">
      <input class="form-control form-control-sm" style="max-width:16rem" type="search" id="p-q" name="q"
             value="<?= esc($filters['q'], 'attr') ?>" placeholder="Search by title…">

      <select class="form-select form-select-sm w-auto" name="category">
        <option value="">All categories</option>
        <?php foreach ($categories as $row): ?>
          <option value="<?= (int) $row['id'] ?>" <?= $filters['category'] === (string) $row['id'] ? 'selected' : '' ?>><?= esc($row['name']) ?></option>
        <?php endforeach; ?>
      </select>

      <select class="form-select form-select-sm w-auto" name="status">
        <option value="">All</option>
        <option value="active"   <?= $filters['status'] === 'active' ? 'selected' : '' ?>>Live only</option>
        <option value="hidden"   <?= $filters['status'] === 'hidden' ? 'selected' : '' ?>>Hidden only</option>
        <option value="featured" <?= $filters['status'] === 'featured' ? 'selected' : '' ?>>Featured only</option>
      </select>

      <a class="btn btn-bba-outline btn-sm ms-auto" href="<?= site_url('admin/packages') ?>">Reset</a>
    </form>
  </div>

  <div id="package-table">
    <?= $this->include('admin/packages/_table') ?>
  </div>
</div>

<?= $this->endSection() ?>
