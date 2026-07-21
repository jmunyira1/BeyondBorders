<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<form method="post" action="<?= site_url('admin/settings') ?>">
  <?= csrf_field() ?>

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
                <?php elseif ($type === 'textarea'): ?>
                  <label class="form-label" for="<?= $id ?>"><?= esc($label) ?></label>
                  <textarea class="form-control" id="<?= $id ?>" name="<?= esc($key, 'attr') ?>" rows="4"><?= esc($value) ?></textarea>
                <?php else: ?>
                  <label class="form-label" for="<?= $id ?>"><?= esc($label) ?></label>
                  <input class="form-control" id="<?= $id ?>" name="<?= esc($key, 'attr') ?>"
                         type="<?= esc($type, 'attr') ?>" value="<?= esc($value, 'attr') ?>">
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

  <div class="d-flex gap-2 mt-3 position-sticky bottom-0 py-3" style="background:#f6f4ef">
    <button type="submit" class="btn btn-bba-green">Save settings</button>
    <a class="btn btn-bba-outline" href="<?= site_url('/') ?>" target="_blank" rel="noopener">
      <i class="bi bi-box-arrow-up-right me-1"></i>View site
    </a>
  </div>
</form>

<?= $this->endSection() ?>
