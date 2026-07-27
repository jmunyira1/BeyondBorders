<?php
// Masthead nav (Hallmark N6, adapted to a sticky functional bar — Bootstrap
// collapse retained for mobile). Controllers set $activeNav; anything unset
// simply renders no active state.
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
?>
<nav class="navbar navbar-expand-lg bb-mast py-2">
  <div class="container">
    <a class="navbar-brand" href="<?= url_to('home') ?>">
      <?php // Admin → Settings can replace this; blank falls back to the bundled file. ?>
      <img class="bb-logo" src="<?= esc(media_url(site('logo'), 'assets/img/logo-nav.png'), 'attr') ?>"
           alt="<?= esc(site('companyName')) ?> logo">
      <span class="bb-wordmark"><?= wordmark() ?></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
        <?php foreach ($links as $key => [$label, $url]): ?>
          <li class="nav-item">
            <a class="nav-link<?= $activeNav === $key ? ' active' : '' ?>" href="<?= $url ?>"<?= $activeNav === $key ? ' aria-current="page"' : '' ?>><?= $label ?></a>
          </li>
        <?php endforeach; ?>
        <li class="nav-item mt-2 mt-lg-0 ms-lg-2"><a class="btn btn-bba-outline" href="<?= url_to('packages') ?>">Book now</a></li>
      </ul>
    </div>
  </div>
</nav>
