<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="row g-3">
  <div class="col-lg-5">
    <div class="bba-panel">
      <div class="bba-panel-head"><h2>Account</h2></div>
      <div class="bba-panel-body">
        <ul class="bba-meta-list mb-0">
          <li><span class="k">Username</span><span class="v"><?= esc($user->username ?? '—') ?></span></li>
          <li><span class="k">Email</span><span class="v"><?= esc($user->email) ?></span></li>
          <li><span class="k">Groups</span><span class="v"><?= esc(implode(', ', $user->getGroups()) ?: '—') ?></span></li>
          <li><span class="k">Last active</span><span class="v"><?= esc($user->last_active ? date('j M Y, H:i', strtotime((string) $user->last_active)) : '—') ?></span></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="bba-panel">
      <div class="bba-panel-head"><h2>Change password</h2></div>
      <div class="bba-panel-body">
        <form method="post" action="<?= site_url('admin/account/password') ?>">
          <?= csrf_field() ?>

          <div class="mb-3">
            <label class="form-label" for="a-current">Current password</label>
            <input class="form-control" id="a-current" name="current_password" type="password" autocomplete="current-password" required>
          </div>
          <div class="mb-3">
            <label class="form-label" for="a-new">New password</label>
            <input class="form-control" id="a-new" name="new_password" type="password" autocomplete="new-password" required>
            <div class="form-text">At least 8 characters. Avoid anything you use elsewhere.</div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="a-confirm">Confirm new password</label>
            <input class="form-control" id="a-confirm" name="confirm_password" type="password" autocomplete="new-password" required>
          </div>

          <button type="submit" class="btn btn-bba-green">Change password</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
