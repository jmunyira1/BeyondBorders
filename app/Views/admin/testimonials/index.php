<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="row g-3">
  <div class="col-lg-4">
    <div class="bba-panel">
      <div class="bba-panel-head"><h2>Add a testimonial</h2></div>
      <div class="bba-panel-body">
        <form hx-post="<?= site_url('admin/testimonials') ?>" hx-target="#testimonial-list"
              hx-on::after-request="if(event.detail.successful) this.reset()">
          <div class="mb-3">
            <label class="form-label" for="t-quote">Quote <span class="text-danger">*</span></label>
            <textarea class="form-control" id="t-quote" name="quote" rows="4" required
                      placeholder="Every detail was arranged before we even thought to ask."></textarea>
            <div class="form-text">No quotation marks needed — the design adds them.</div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="t-name">Author <span class="text-danger">*</span></label>
            <input class="form-control" id="t-name" name="author_name" type="text" required placeholder="Happy Traveler">
          </div>
          <div class="mb-3">
            <label class="form-label" for="t-location">Location</label>
            <input class="form-control" id="t-location" name="author_location" type="text" placeholder="Nairobi">
          </div>
          <div class="mb-3">
            <label class="form-label" for="t-rating">Rating</label>
            <select class="form-select" id="t-rating" name="rating">
              <?php foreach ([5, 4, 3, 2, 1] as $n): ?>
                <option value="<?= $n ?>"><?= $n ?> star<?= $n === 1 ? '' : 's' ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-bba-green w-100">Add testimonial</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="bba-panel">
      <div class="bba-panel-head"><h2>Testimonials</h2></div>
      <div class="bba-panel-body" id="testimonial-list">
        <?= $this->include('admin/testimonials/_list') ?>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
