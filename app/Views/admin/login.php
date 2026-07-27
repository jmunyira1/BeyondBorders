<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sign in — <?= esc(setting('Site.companyName') ?: 'Beyond Borders Adventures') ?></title>
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="<?= base_url('assets/css/theme.css') ?>" rel="stylesheet">
<link href="<?= base_url('assets/css/admin.css') ?>" rel="stylesheet">
</head>
<body class="bba-admin">
<div class="bba-login-wrap">
  <div class="bba-login-card">
    <div class="bba-login-head">
      <img src="<?= esc(media_url(site('logo'), 'assets/img/logo-nav.png'), 'attr') ?>" alt="">
      <h1>Beyond Borders Admin</h1>
      <p>Sign in to manage the site</p>
    </div>

    <div class="bba-login-body">
      <?php if (session('error') !== null): ?>
        <div class="bba-alert bba-alert-error mb-3"><?= esc(session('error')) ?></div>
      <?php elseif (session('errors') !== null): ?>
        <div class="bba-alert bba-alert-error mb-3">
          <?php if (is_array(session('errors'))): ?>
            <?php foreach (session('errors') as $error): ?>
              <div><?= esc($error) ?></div>
            <?php endforeach; ?>
          <?php else: ?>
            <?= esc(session('errors')) ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if (session('message') !== null): ?>
        <div class="bba-alert bba-alert-success mb-3"><?= esc(session('message')) ?></div>
      <?php endif; ?>

      <form action="<?= url_to('login') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label" for="email">Email address</label>
          <input type="email" class="form-control" id="email" name="email" inputmode="email"
                 autocomplete="username" value="<?= old('email') ?>" required autofocus>
        </div>

        <div class="mb-3">
          <label class="form-label" for="password">Password</label>
          <input type="password" class="form-control" id="password" name="password"
                 autocomplete="current-password" required>
        </div>

        <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="remember" name="remember" <?= old('remember') ? 'checked' : '' ?>>
            <label class="form-check-label small" for="remember">Keep me signed in</label>
          </div>
        <?php endif; ?>

        <div class="d-grid">
          <button type="submit" class="btn btn-bba-green">Sign in</button>
        </div>
      </form>

      <p class="text-center small text-body-secondary mt-4 mb-0">
        <a href="<?= site_url('/') ?>" class="text-decoration-none">← Back to the site</a>
      </p>
    </div>
  </div>
</div>
</body>
</html>
