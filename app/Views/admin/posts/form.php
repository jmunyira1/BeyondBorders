<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<?php
$isNew  = $post === null || empty($post['id']);
$action = $isNew ? site_url('admin/posts') : site_url('admin/posts/' . $post['id']);
$v      = static fn (string $k, $d = '') => esc((string) ($post[$k] ?? $d), 'attr');
?>

<form method="post" action="<?= $action ?>" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <?php if ($errors !== []): ?>
    <div class="bba-alert bba-alert-error mb-3">Please check the highlighted fields below.</div>
  <?php endif; ?>

  <div class="row g-3">
    <div class="col-lg-8">
      <div class="bba-panel">
        <div class="bba-panel-head"><h2>Post</h2></div>
        <div class="bba-panel-body">
          <div class="mb-3">
            <label class="form-label" for="f-title">Title <span class="text-danger">*</span></label>
            <input class="form-control<?= isset($errors['title']) ? ' is-invalid' : '' ?>" id="f-title" name="title"
                   type="text" value="<?= $v('title') ?>" data-slug-source required>
            <?php if (isset($errors['title'])): ?><span class="bba-field-error"><?= esc($errors['title']) ?></span><?php endif; ?>
          </div>

          <div class="mb-3">
            <label class="form-label" for="f-slug">URL slug</label>
            <div class="input-group">
              <span class="input-group-text small">/blog/</span>
              <input class="form-control" id="f-slug" name="slug" type="text" value="<?= $v('slug') ?>" data-slug-target>
            </div>
            <div class="form-text">Leave blank to generate from the title.</div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="f-excerpt">Excerpt</label>
            <textarea class="form-control" id="f-excerpt" name="excerpt" rows="2"
                      placeholder="The standfirst shown on the card and above the article."><?= esc((string) ($post['excerpt'] ?? '')) ?></textarea>
          </div>

          <div class="mb-0">
            <label class="form-label" for="f-body">Body</label>
            <textarea class="form-control" id="f-body" name="body" rows="18"
                      placeholder="Leave a blank line between paragraphs."><?= esc((string) ($post['body'] ?? '')) ?></textarea>
            <div class="form-text">Plain text. A blank line starts a new paragraph.</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="bba-panel mb-3">
        <div class="bba-panel-head"><h2>Publishing</h2></div>
        <div class="bba-panel-body">
          <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="f-published" name="is_published" value="1"
                   <?= ($post['is_published'] ?? 1) ? 'checked' : '' ?>>
            <label class="form-check-label" for="f-published">Published</label>
          </div>

          <div class="mb-3">
            <label class="form-label" for="f-date">Publish date</label>
            <input class="form-control" id="f-date" name="published_at" type="datetime-local"
                   value="<?= esc(date('Y-m-d\TH:i', strtotime($post['published_at'] ?? 'now')), 'attr') ?>">
            <div class="form-text">A future date keeps the post hidden until then.</div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="f-category">Category</label>
            <select class="form-select" id="f-category" name="post_category_id">
              <option value="">— none —</option>
              <?php foreach ($categories as $category): ?>
                <option value="<?= (int) $category['id'] ?>" <?= (string) ($post['post_category_id'] ?? '') === (string) $category['id'] ? 'selected' : '' ?>><?= esc($category['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label" for="f-author">Author</label>
            <input class="form-control" id="f-author" name="author" type="text" value="<?= $v('author', site('companyName')) ?>">
          </div>

          <div class="mb-0">
            <label class="form-label" for="f-read">Read time (minutes)</label>
            <input class="form-control" id="f-read" name="read_minutes" type="number" min="1" value="<?= $v('read_minutes') ?>" placeholder="Auto">
            <div class="form-text">Leave blank to estimate from the word count.</div>
          </div>
        </div>
      </div>

      <div class="bba-panel">
        <div class="bba-panel-head"><h2>Featured image</h2></div>
        <div class="bba-panel-body">
          <img class="bba-img-preview mb-3" id="img-preview" src="<?= esc(media_url($post['image'] ?? null), 'attr') ?>" alt="">
          <div class="mb-3">
            <label class="form-label" for="f-image">Upload</label>
            <input class="form-control" id="f-image" name="image_file" type="file" accept="image/*" data-preview="#img-preview">
          </div>
          <div class="mb-3">
            <label class="form-label" for="f-image-url">…or paste a URL</label>
            <input class="form-control" id="f-image-url" name="image_url" type="url" placeholder="https://…"
                   value="<?= str_contains((string) ($post['image'] ?? ''), '://') ? $v('image') : '' ?>">
          </div>
          <div class="mb-0">
            <label class="form-label" for="f-alt">Image description</label>
            <input class="form-control" id="f-alt" name="image_alt" type="text" value="<?= $v('image_alt') ?>">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex gap-2 mt-3">
    <button type="submit" class="btn btn-bba-green"><?= $isNew ? 'Create post' : 'Save changes' ?></button>
    <a class="btn btn-bba-outline" href="<?= site_url('admin/posts') ?>">Cancel</a>
    <?php if (! $isNew): ?>
      <a class="btn btn-bba-outline ms-auto" target="_blank" rel="noopener" href="<?= site_url('blog/' . ($post['slug'] ?? '')) ?>"><i class="bi bi-box-arrow-up-right me-1"></i>View on site</a>
    <?php endif; ?>
  </div>
</form>

<?= $this->endSection() ?>
