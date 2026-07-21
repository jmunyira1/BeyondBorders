<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<header class="bb-pagehead">
  <div class="container">
    <nav aria-label="Breadcrumb">
      <p class="bb-meta mb-3">
        <a href="<?= url_to('blog') ?>">The Journal</a>
        <?php if ($post['category_name']): ?>
          <span aria-hidden="true"> / </span>
          <a href="<?= url_to('blog') ?>?category=<?= esc($post['category_slug'], 'url') ?>"><?= esc($post['category_name']) ?></a>
        <?php endif; ?>
      </p>
    </nav>
    <h1><?= esc($post['title']) ?></h1>
    <p class="bb-meta mt-3 mb-0">
      <?= esc(date('j F Y', strtotime($post['published_at']))) ?>
      · <?= (int) $post['read_minutes'] ?>&nbsp;min read
      <?= $post['author'] ? ' · ' . esc($post['author']) : '' ?>
    </p>
  </div>
</header>

<section>
  <div class="container">
    <?php if ($post['image']): ?>
      <div class="bba-post-hero mb-5">
        <img src="<?= esc(media_url($post['image']), 'attr') ?>" alt="<?= esc($post['image_alt'] ?: $post['title'], 'attr') ?>"
             width="1600" height="900" fetchpriority="high">
      </div>
    <?php endif; ?>

    <div class="col-lg-8 mx-auto">
      <?php if ($post['excerpt']): ?>
        <p class="bb-lede-para mb-4"><?= esc($post['excerpt']) ?></p>
        <hr class="bba-rule">
      <?php endif; ?>

      <div class="bba-article">
        <?= nl2paras($post['body']) ?>
      </div>

      <div class="d-flex flex-wrap gap-3 align-items-center mt-5 pt-4 border-top">
        <a href="<?= url_to('blog') ?>" class="bb-link">←&nbsp;All posts</a>
        <a href="<?= url_to('packages') ?>" class="bb-link">Explore our trips&nbsp;→</a>
      </div>
    </div>
  </div>
</section>

<?php if ($more !== []): ?>
<section class="section-sand">
  <div class="container">
    <div class="bb-rowhead">
      <h2>More from the journal</h2>
    </div>
    <div class="row g-4">
      <?php foreach ($more as $item): ?>
        <div class="col-md-4">
          <article class="bb-item h-100">
            <a href="<?= url_to('post', $item['slug']) ?>" class="bb-item__media" tabindex="-1" aria-hidden="true">
              <img src="<?= esc(media_url($item['image']), 'attr') ?>" alt="<?= esc($item['image_alt'] ?: $item['title'], 'attr') ?>" loading="lazy" width="800" height="600">
            </a>
            <div class="bb-item__body">
              <p class="bb-meta mb-0"><?= esc(date('F Y', strtotime($item['published_at']))) ?> · <?= (int) $item['read_minutes'] ?>&nbsp;min read</p>
              <h3><a href="<?= url_to('post', $item['slug']) ?>" class="stretched-link"><?= esc($item['title']) ?></a></h3>
              <div class="bb-item__foot">
                <span class="bb-link" aria-hidden="true">Read the story&nbsp;→</span>
              </div>
            </div>
          </article>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?= $this->endSection() ?>
