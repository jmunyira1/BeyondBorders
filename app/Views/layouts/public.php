<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<?php
  // ---- SEO / social / AI metadata --------------------------------------
  $seoTitle = $title ?? site('companyName');
  $seoDesc  = $metaDescription ?? 'Discover the magic of Kenya with ' . site('companyName') . ' — safaris, beach holidays, mountain treks, cultural experiences and tailor-made trips, clearly priced.';
  $seoCanon = $canonical ?? current_url();
  $seoImage = $metaImage ?? seo_logo_url();
  $seoType  = $ogType ?? 'website';
  $seoRobots = $metaRobots ?? 'index, follow, max-image-preview:large, max-snippet:-1';
?>
<title><?= esc($seoTitle) ?></title>
<meta name="description" content="<?= esc($seoDesc, 'attr') ?>">
<meta name="robots" content="<?= esc($seoRobots, 'attr') ?>">
<link rel="canonical" href="<?= esc($seoCanon, 'attr') ?>">
<meta name="theme-color" content="#003839">
<meta name="author" content="<?= esc(site('companyName'), 'attr') ?>">

<?php // Open Graph (Facebook, WhatsApp, LinkedIn) ?>
<meta property="og:type" content="<?= esc($seoType, 'attr') ?>">
<meta property="og:site_name" content="<?= esc(site('companyName'), 'attr') ?>">
<meta property="og:title" content="<?= esc($seoTitle, 'attr') ?>">
<meta property="og:description" content="<?= esc($seoDesc, 'attr') ?>">
<meta property="og:url" content="<?= esc($seoCanon, 'attr') ?>">
<meta property="og:image" content="<?= esc($seoImage, 'attr') ?>">
<meta property="og:locale" content="en_KE">

<?php // Twitter / X card ?>
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= esc($seoTitle, 'attr') ?>">
<meta name="twitter:description" content="<?= esc($seoDesc, 'attr') ?>">
<meta name="twitter:image" content="<?= esc($seoImage, 'attr') ?>">

<?php // Site-wide structured data — the business + the website search box ?>
<?= json_ld(seo_org_schema()) ?>
<?= json_ld(seo_website_schema()) ?>

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
