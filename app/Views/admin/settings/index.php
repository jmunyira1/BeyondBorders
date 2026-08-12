<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<form method="post" action="<?= site_url('admin/settings') ?>" enctype="multipart/form-data" data-validate>
  <?= csrf_field() ?>

  <?php if (session('error')): ?>
    <div class="bba-alert bba-alert-error mb-3" role="alert"><?= esc(session('error')) ?></div>
  <?php endif; ?>

  <div class="row g-3">
    <?php foreach ($groups as $groupName => $fields): ?>
      <div class="col-lg-6">
        <div class="bba-panel h-100">
          <div class="bba-panel-head"><h2><?= esc($groupName) ?></h2></div>
          <div class="bba-panel-body">
            <?php foreach ($fields as $key => $spec):
                [$label, $type] = $spec;
                $help  = $spec[2] ?? null;
                $value = (string) ($values[$key] ?? '');
                $id    = 's-' . $key;
            ?>
              <div class="mb-3">
                <?php if ($type === 'bool'): ?>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="<?= $id ?>" name="<?= esc($key, 'attr') ?>" value="1"
                           <?= $values[$key] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="<?= $id ?>"><?= esc($label) ?></label>
                  </div>
                <?php elseif ($type === 'image'): ?>
                  <span class="form-label d-block"><?= esc($label) ?></span>
                  <div class="bba-logo-field">
                    <span class="bba-logo-preview">
                      <img src="<?= esc(media_url($value ?: 'assets/img/logo-nav.png'), 'attr') ?>"
                           alt="<?= $value === '' ? 'Current bundled logo' : 'Current logo' ?>">
                    </span>
                    <div class="bba-logo-controls">
                      <input class="form-control form-control-sm" type="file" id="<?= $id ?>"
                             name="<?= esc($key, 'attr') ?>_file" accept="image/png,image/jpeg,image/webp,image/gif">
                      <label class="form-label mt-2 mb-1 small" for="<?= $id ?>-url">…or link to an image</label>
                      <input class="form-control form-control-sm" id="<?= $id ?>-url"
                             name="<?= esc($key, 'attr') ?>_url" type="url"
                             value="<?= esc(str_starts_with($value, 'http') ? $value : '', 'attr') ?>"
                             placeholder="https://…">
                      <?php if ($value !== ''): ?>
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="checkbox" value="1"
                                 id="<?= $id ?>-remove" name="<?= esc($key, 'attr') ?>_remove">
                          <label class="form-check-label small" for="<?= $id ?>-remove">Remove and use the bundled logo</label>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php elseif ($type === 'textarea'): ?>
                  <label class="form-label" for="<?= $id ?>"><?= esc($label) ?></label>
                  <textarea class="form-control" id="<?= $id ?>" name="<?= esc($key, 'attr') ?>" rows="4"><?= esc($value) ?></textarea>
                <?php else:
                    // "#" is the legacy hidden-sentinel for social links — show it as blank.
                    $shown    = $value === '#' ? '' : $value;
                    $required = $key === 'companyName';
                ?>
                  <label class="form-label" for="<?= $id ?>"><?= esc($label) ?><?= $required ? ' <span class="text-danger" aria-hidden="true">*</span>' : '' ?></label>
                  <input class="form-control" id="<?= $id ?>" name="<?= esc($key, 'attr') ?>"
                         type="<?= esc($type, 'attr') ?>" value="<?= esc($shown, 'attr') ?>"
                         <?= $required ? 'required' : '' ?>
                         <?= $type === 'url' ? 'inputmode="url"' : '' ?>>
                <?php endif; ?>

                <?php if ($help !== null): ?>
                  <div class="form-text"><?= $help /* trusted: authored above, may contain a link */ ?></div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="d-flex gap-2 mt-3 position-sticky bottom-0 py-3" style="background:#f1f5f4">
    <button type="submit" class="btn btn-bba-green">Save settings</button>
    <a class="btn btn-bba-outline" href="<?= site_url('/') ?>" target="_blank" rel="noopener">
      <i class="bi bi-box-arrow-up-right me-1"></i>View site
    </a>
  </div>
</form>

<?= $this->endSection() ?>
