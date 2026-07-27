<?php
$user     = auth()->user();
$activeAdmin ??= '';
$newCount = $newEnquiries ?? 0;
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= esc($title ?? 'Admin') ?> — <?= esc(site('companyName')) ?></title>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
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
<div class="bba-admin-shell">

  <aside class="bba-side" id="bba-side">
    <div class="bba-side-brand">
      <img src="<?= esc(media_url(site('logo'), 'assets/img/logo-nav.png'), 'attr') ?>" alt="">
      <span>Beyond Borders<small>Admin</small></span>
    </div>

    <nav class="bba-side-nav">
      <?php
      $sections = [
          'Overview' => [
              ['dashboard', 'admin', 'bi-speedometer2', 'Dashboard', 0],
              ['enquiries', 'admin/enquiries', 'bi-inbox', 'Enquiries', $newCount],
          ],
          'Content' => [
              ['packages', 'admin/packages', 'bi-compass', 'Packages', 0],
              ['posts', 'admin/posts', 'bi-journal-text', 'Blog posts', 0],
              ['gallery', 'admin/gallery', 'bi-images', 'Gallery', 0],
              ['testimonials', 'admin/testimonials', 'bi-chat-quote', 'Testimonials', 0],
              ['faqs', 'admin/faqs', 'bi-question-circle', 'FAQs', 0],
          ],
          'Taxonomies' => [
              ['tax-categories', 'admin/taxonomy/categories', 'bi-tags', 'Categories', 0],
              ['tax-destinations', 'admin/taxonomy/destinations', 'bi-geo-alt', 'Destinations', 0],
              ['tax-tour-types', 'admin/taxonomy/tour-types', 'bi-signpost', 'Tour types', 0],
              ['tax-post-categories', 'admin/taxonomy/post-categories', 'bi-bookmarks', 'Post categories', 0],
          ],
          'Site' => [
              ['settings', 'admin/settings', 'bi-sliders', 'Settings', 0],
              ['account', 'admin/account', 'bi-person-gear', 'My account', 0],
          ],
      ];

      foreach ($sections as $heading => $items): ?>
        <p class="bba-side-heading"><?= esc($heading) ?></p>
        <?php foreach ($items as [$key, $path, $icon, $label, $badge]): ?>
          <a class="bba-side-link<?= $activeAdmin === $key ? ' active' : '' ?>" href="<?= site_url($path) ?>">
            <i class="bi <?= $icon ?>" aria-hidden="true"></i><span><?= esc($label) ?></span>
            <?php if ($badge > 0): ?><span class="badge rounded-pill"><?= $badge ?></span><?php endif; ?>
          </a>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </nav>

    <div class="bba-side-foot">
      <a class="bba-side-link" href="<?= site_url('/') ?>" target="_blank" rel="noopener">
        <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i><span>View site</span>
      </a>
      <form method="post" action="<?= site_url('logout') ?>" class="mt-1">
        <?= csrf_field() ?>
        <button type="submit" class="bba-side-link w-100 border-0 bg-transparent text-start">
          <i class="bi bi-box-arrow-left" aria-hidden="true"></i><span>Sign out</span>
        </button>
      </form>
    </div>
  </aside>

  <div class="bba-side-backdrop" id="bba-side-backdrop"></div>

  <main class="bba-main">
    <header class="bba-topbar">
      <button class="btn btn-sm btn-outline-secondary d-lg-none" id="bba-side-toggle" aria-label="Toggle menu">
        <i class="bi bi-list" aria-hidden="true"></i>
      </button>
      <div class="me-auto">
        <h1><?= esc($heading ?? $title ?? 'Admin') ?></h1>
        <?php if (! empty($subheading)): ?><p class="sub"><?= esc($subheading) ?></p><?php endif; ?>
      </div>
      <?= $this->renderSection('actions') ?>
      <span class="text-body-secondary small d-none d-md-inline">
        <i class="bi bi-person-circle me-1" aria-hidden="true"></i><?= esc($user->username ?? $user->email ?? '') ?>
      </span>
    </header>

    <div class="bba-content">
      <?php if (session()->getFlashdata('message')): ?>
        <div class="bba-alert bba-alert-success mb-3"><?= esc(session()->getFlashdata('message')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
        <div class="bba-alert bba-alert-error mb-3"><?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>

      <?= $this->renderSection('content') ?>
    </div>
  </main>
</div>

<div id="bba-toasts" aria-live="polite" aria-atomic="true"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/htmx.min.js') ?>"></script>
<script src="<?= base_url('assets/js/admin.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
