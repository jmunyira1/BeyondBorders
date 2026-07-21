<?= $this->extend('admin/layout') ?>

<?= $this->section('actions') ?>
<a class="btn btn-bba-green btn-sm" href="<?= site_url('admin/posts/new') ?>"><i class="bi bi-plus-lg me-1"></i>New post</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="bba-panel">
  <div class="bba-panel-head">
    <form class="d-flex flex-wrap gap-2 align-items-center w-100"
          hx-get="<?= site_url('admin/posts/list') ?>" hx-target="#post-table"
          hx-trigger="change, keyup from:#po-q delay:400ms changed, submit">
      <input class="form-control form-control-sm" style="max-width:16rem" type="search" id="po-q" name="q"
             value="<?= esc($filters['q'], 'attr') ?>" placeholder="Search by title…">
      <select class="form-select form-select-sm w-auto" name="status">
        <option value="">All</option>
        <option value="published" <?= $filters['status'] === 'published' ? 'selected' : '' ?>>Published</option>
        <option value="draft"     <?= $filters['status'] === 'draft' ? 'selected' : '' ?>>Drafts</option>
      </select>
      <a class="btn btn-bba-outline btn-sm ms-auto" href="<?= site_url('admin/posts') ?>">Reset</a>
    </form>
  </div>
  <div id="post-table"><?= $this->include('admin/posts/_table') ?></div>
</div>

<?= $this->endSection() ?>
