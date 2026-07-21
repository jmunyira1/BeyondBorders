<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="row g-3">
  <div class="col-lg-4">
    <div class="bba-panel">
      <div class="bba-panel-head"><h2>Add an image</h2></div>
      <div class="bba-panel-body">
        <form hx-post="<?= site_url('admin/gallery') ?>"
              hx-target="#gallery-grid"
              hx-encoding="multipart/form-data"
              hx-on::after-request="if(event.detail.successful) this.reset()">
          <div class="mb-3">
            <img class="bba-img-preview mb-2" id="img-preview" src="" alt="">
            <label class="form-label" for="g-file">Upload a photo</label>
            <input class="form-control" id="g-file" name="image_file" type="file" accept="image/*" data-preview="#img-preview">
            <div class="form-text">JPEG, PNG or WebP, up to 8&nbsp;MB.</div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="g-url">…or paste a URL</label>
            <input class="form-control" id="g-url" name="image_url" type="url" placeholder="https://…">
          </div>
          <div class="mb-3">
            <label class="form-label" for="g-caption">Caption</label>
            <input class="form-control" id="g-caption" name="caption" type="text" placeholder="Maasai Mara at golden hour">
          </div>
          <div class="mb-3">
            <label class="form-label" for="g-alt">Image description</label>
            <input class="form-control" id="g-alt" name="alt" type="text" placeholder="Savannah at golden hour">
            <div class="form-text">For screen readers. Defaults to the caption.</div>
          </div>
          <button type="submit" class="btn btn-bba-green w-100">Add to gallery</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="bba-panel">
      <div class="bba-panel-head"><h2>Gallery images</h2></div>
      <div class="bba-panel-body" id="gallery-grid">
        <?= $this->include('admin/gallery/_grid') ?>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
