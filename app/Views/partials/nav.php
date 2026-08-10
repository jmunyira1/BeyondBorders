<?php
// Two-row masthead in the Magical-Kenya shape: a promo strip, then a logo/utility
// row, then the full horizontal menu as its own row beneath it. The menu stays
// horizontal down to 768px and only collapses to a toggle on phones.
$activeNav ??= '';
$links = [
    'home'         => ['Home', url_to('home')],
    'packages'     => ['Tours &amp; Packages', url_to('packages')],
    'custom-trips' => ['Custom Trips', url_to('custom-trips')],
    'about'        => ['About', url_to('about')],
    'gallery'      => ['Gallery', url_to('gallery')],
    'blog'         => ['Blog', url_to('blog')],
    'contact'      => ['Contact', url_to('contact')],
];

$promoText = trim((string) site('promoText'));
if (site('promoEnabled', true) && $promoText !== ''):
    $promoUrl   = trim((string) site('promoLink')) ?: url_to('packages');
    $promoLabel = trim((string) site('promoLinkText')) ?: 'Learn more';
?>
<div class="bb-promo">
  <div class="container">
    <span><?= esc($promoText) ?></span>
    <a href="<?= esc($promoUrl, 'attr') ?>"><?= esc($promoLabel) ?> <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
  </div>
</div>
<?php endif; ?>

<header class="bb-head">
  <!-- Row A — logo + utility -->
  <div class="bb-head__bar">
    <div class="container">
      <a class="bb-brand" href="<?= url_to('home') ?>">
        <img class="bb-logo" src="<?= esc(media_url(site('logo'), 'assets/img/logo-nav.png'), 'attr') ?>"
             alt="<?= esc(site('companyName')) ?> logo">
        <span class="bb-wordmark"><?= wordmark() ?></span>
      </a>

      <div class="bb-head__util">
        <a class="bb-head__phone" href="tel:<?= esc(site('phoneLink'), 'attr') ?>">
          <i class="bi bi-telephone" aria-hidden="true"></i><span><?= esc(site('phone')) ?></span>
        </a>
        <a class="btn btn-bba-gold" href="<?= url_to('packages') ?>">Book now</a>
      </div>
    </div>
  </div>

  <!-- Row B — the full horizontal menu (desktop / tablet, ≥48rem) -->
  <nav class="bb-head__menu" id="bb-menu" aria-label="Primary">
    <div class="container">
      <ul>
        <?php foreach ($links as $key => [$label, $url]): ?>
          <li>
            <a class="bb-navlink<?= $activeNav === $key ? ' active' : '' ?>" href="<?= $url ?>"<?= $activeNav === $key ? ' aria-current="page"' : '' ?>><?= $label ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </nav>
</header>

<?php
// Mobile bottom tab bar (Magical-Kenya style) — replaces the hamburger below
// 48rem. Icon over label; the active destination is marked. Key destinations
// only; the full list stays in the footer and the desktop menu.
$tabs = [
    'home'         => ['Home',   'bi-house-door', url_to('home')],
    'packages'     => ['Tours',  'bi-compass',    url_to('packages')],
    'custom-trips' => ['Custom',  'bi-map',        url_to('custom-trips')],
    'gallery'      => ['Gallery', 'bi-images',     url_to('gallery')],
    'contact'      => ['Contact', 'bi-chat-dots',  url_to('contact')],
];
?>
<nav class="bb-tabbar" aria-label="Primary (mobile)">
  <?php foreach ($tabs as $key => [$label, $icon, $url]): ?>
    <a class="bb-tab<?= $activeNav === $key ? ' active' : '' ?>" href="<?= $url ?>"<?= $activeNav === $key ? ' aria-current="page"' : '' ?>>
      <i class="bi <?= $icon ?>" aria-hidden="true"></i><span><?= esc($label) ?></span>
    </a>
  <?php endforeach; ?>
</nav>
