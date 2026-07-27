<?php // Statement footer (Hallmark Ft5) — one closing line, slim link row, meta. ?>
<footer class="bb-foot">
  <div class="container">
    <p class="bb-foot__line"><?= esc(site('tagline')) ?></p>

    <div class="bb-foot__grid">
      <div class="bb-foot__col me-auto">
        <span class="bb-wordmark d-block mb-2"><?= wordmark() ?></span>
        <ul>
          <li><a href="tel:<?= esc(site('phoneLink'), 'attr') ?>"><i class="bi bi-telephone me-2" aria-hidden="true"></i><?= esc(site('phone')) ?></a></li>
          <li><a href="mailto:<?= esc(site('email'), 'attr') ?>"><i class="bi bi-envelope me-2" aria-hidden="true"></i><?= esc(site('email')) ?></a></li>
          <li><a href="<?= esc(whatsapp_link(site('whatsappPrefill')), 'attr') ?>" target="_blank" rel="noopener"><i class="bi bi-whatsapp me-2" aria-hidden="true"></i>WhatsApp us</a></li>
        </ul>
      </div>
      <div class="bb-foot__col">
        <ul>
          <li><a href="<?= url_to('packages') ?>">Tours &amp; Packages</a></li>
          <li><a href="<?= url_to('custom-trips') ?>">Custom Trips</a></li>
          <li><a href="<?= url_to('gallery') ?>">Gallery</a></li>
          <li><a href="<?= url_to('blog') ?>">Blog</a></li>
        </ul>
      </div>
      <div class="bb-foot__col">
        <ul>
          <li><a href="<?= url_to('about') ?>">About</a></li>
          <li><a href="<?= url_to('contact') ?>">Contact</a></li>
          <li><a href="<?= url_to('terms') ?>">Terms</a></li>
          <li><a href="<?= url_to('privacy') ?>">Privacy</a></li>
        </ul>
      </div>
      <div class="bb-foot__col social d-flex align-items-start gap-2">
        <?php foreach ([
            'instagram' => 'bi-instagram',
            'facebook'  => 'bi-facebook',
            'tiktok'    => 'bi-tiktok',
            'twitter'   => 'bi-twitter-x',
        ] as $key => $icon):
            $url = site($key, '#');
            if ($url === '' || $url === '#') {
                continue;
            } ?>
          <a href="<?= esc($url, 'attr') ?>" target="_blank" rel="noopener" aria-label="<?= esc(ucfirst($key)) ?>"><i class="bi <?= $icon ?>" aria-hidden="true"></i></a>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="bb-foot__meta">
      <span>© <?= date('Y') ?> <?= esc(site('companyName')) ?>. All rights reserved.</span>
      <span><?= esc(site('address')) ?> · <?= esc(site('addressNote')) ?></span>
    </div>
  </div>
</footer>
