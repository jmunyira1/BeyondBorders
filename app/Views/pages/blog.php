<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<header class="bb-pagehead">
  <div class="container">
    <h1>Notes from the road</h1>
    <p class="bb-lede">Timing, packing, planning and the places we keep going back to.</p>
  </div>
</header>

<?php if ($categories !== []): ?>
<section class="pt-4 pb-0" aria-label="Filter by category">
  <div class="container">
    <div class="bb-index">
      <a href="<?= url_to('blog') ?>" class="<?= $activeCategory === '' ? 'active' : '' ?>">All posts</a>
      <?php foreach ($categories as $category): ?>
        <a href="<?= url_to('blog') ?>?category=<?= esc($category['slug'], 'url') ?>"
           class="<?= $activeCategory === $category['slug'] ? 'active' : '' ?>"><?= esc($category['name']) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section>
  <div class="container">
    <?php if ($posts === []): ?>
      <div class="bba-empty text-center p-5">
        <p class="mb-2">No posts in this category yet.</p>
        <a href="<?= url_to('blog') ?>" class="btn btn-bba-outline btn-sm">See all posts</a>
      </div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($posts as $post): ?>
          <div class="col-md-6 col-lg-4">
            <article class="bb-item h-100">
              <a href="<?= url_to('post', $post['slug']) ?>" class="bb-item__media" tabindex="-1" aria-hidden="true">
                <img src="<?= esc(media_url($post['image']), 'attr') ?>" alt="<?= esc($post['image_alt'] ?: $post['title'], 'attr') ?>" loading="lazy" width="800" height="600">
              </a>
              <div class="bb-item__body">
                <p class="bb-meta mb-0">
                  <?= esc(date('F Y', strtotime($post['published_at']))) ?>
                  <?= $post['category_name'] ? ' · ' . esc($post['category_name']) : '' ?>
                  · <?= (int) $post['read_minutes'] ?>&nbsp;min read
                </p>
                <h3><a href="<?= url_to('post', $post['slug']) ?>" class="stretched-link"><?= esc($post['title']) ?></a></h3>
                <p class="text-body-secondary mb-0"><?= esc(excerpt_of($post['excerpt'] ?: $post['body'], 130)) ?></p>
                <div class="bb-item__foot">
                  <span class="bb-link" aria-hidden="true">Read the story&nbsp;→</span>
                </div>
              </div>
            </article>
          </div>
        <?php endforeach; ?>
      </div>

      <?php if ($pager->getPageCount('default') > 1): ?>
        <nav class="mt-5 d-flex justify-content-center bba-pager" aria-label="Blog pages">
          <?= $pager->links('default', 'bba_pager_plain') ?>
        </nav>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>

<section class="bb-band">
  <div class="container">
    <h2>Read enough? Time to travel.</h2>
    <p>Every trip on this site was designed by the same people who write these posts.</p>
    <a href="<?= url_to('packages') ?>" class="btn btn-bba-gold">Explore packages</a>
  </div>
</section>

<?= $this->endSection() ?>
