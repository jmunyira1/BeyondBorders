<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= esc($title ?? site('companyName')) ?></title>
<meta name="description" content="<?= esc($metaDescription ?? 'Discover the magic of Kenya with Beyond Borders Adventures — safaris, beach holidays, mountain treks, cultural experiences and tailor-made trips.') ?>">
<?php // security.regenerate is off, so this token stays valid for the whole
      // session and htmx can reuse it across swaps without refreshing it. ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<?php // Cache-bust stylesheets by file mtime so edits show up without a hard refresh.
      $cssV = static fn (string $f): string => base_url($f) . '?v=' . (@filemtime(FCPATH . $f) ?: 1); ?>
<link href="<?= $cssV('assets/css/tokens.css') ?>" rel="stylesheet">
<link href="<?= $cssV('assets/css/hallmark.css') ?>" rel="stylesheet">
<link href="<?= $cssV('assets/css/app.css') ?>" rel="stylesheet">
<?= $this->renderSection('head') ?>
</head>
<body>

<?= $this->include('partials/nav') ?>

<?= $this->renderSection('content') ?>

<?= $this->include('partials/footer') ?>
<?= $this->include('partials/whatsapp') ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/htmx.min.js') ?>"></script>
<script src="<?= base_url('assets/js/validate.js') ?>"></script>
<script src="<?= base_url('assets/js/app.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
