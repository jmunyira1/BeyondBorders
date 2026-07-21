<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="row g-3">
  <div class="col-lg-4">
    <div class="bba-panel">
      <div class="bba-panel-head"><h2>Add a <?= esc($meta['singular']) ?></h2></div>
      <div class="bba-panel-body">
        <form hx-post="<?= site_url('admin/taxonomy/' . $typeKey) ?>" hx-target="#tax-list"
              hx-on::after-request="if(event.detail.successful) this.reset()">
          <div class="mb-3">
            <label class="form-label" for="x-name">Name <span class="text-danger">*</span></label>
            <input class="form-control" id="x-name" name="name" type="text" required data-slug-source>
          </div>
          <div class="mb-3">
            <label class="form-label" for="x-slug">Slug</label>
            <input class="form-control" id="x-slug" name="slug" type="text" data-slug-target>
            <div class="form-text">Used in filter URLs. Leave blank to generate.</div>
          </div>

          <?php if (in_array('icon', $meta['fields'], true)): ?>
            <div class="mb-3">
              <label class="form-label" for="x-icon">Icon</label>
              <input class="form-control" id="x-icon" name="icon" type="text" placeholder="bi-binoculars">
              <div class="form-text">A <a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener">Bootstrap Icons</a> name, e.g. <code>bi-umbrella</code>.</div>
            </div>
          <?php endif; ?>

          <?php if (in_array('region', $meta['fields'], true)): ?>
            <div class="mb-3">
              <label class="form-label" for="x-region">Region</label>
              <input class="form-control" id="x-region" name="region" type="text" placeholder="Rift Valley">
            </div>
          <?php endif; ?>

          <?php if (in_array('description', $meta['fields'], true)): ?>
            <div class="mb-3">
              <label class="form-label" for="x-description">Description</label>
              <textarea class="form-control" id="x-description" name="description" rows="3"></textarea>
            </div>
          <?php endif; ?>

          <button type="submit" class="btn btn-bba-green w-100">Add <?= esc($meta['singular']) ?></button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="bba-panel">
      <div class="bba-panel-head"><h2><?= esc($meta['label']) ?></h2></div>
      <div id="tax-list"><?= $this->include('admin/taxonomy/_list') ?></div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
