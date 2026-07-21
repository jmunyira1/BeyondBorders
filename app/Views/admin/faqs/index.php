<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="row g-3">
  <div class="col-lg-4">
    <div class="bba-panel">
      <div class="bba-panel-head"><h2>Add an FAQ</h2></div>
      <div class="bba-panel-body">
        <form hx-post="<?= site_url('admin/faqs') ?>" hx-target="#faq-list"
              hx-on::after-request="if(event.detail.successful) this.reset()">
          <div class="mb-3">
            <label class="form-label" for="q-question">Question <span class="text-danger">*</span></label>
            <input class="form-control" id="q-question" name="question" type="text" required placeholder="How do I pay?">
          </div>
          <div class="mb-3">
            <label class="form-label" for="q-answer">Answer <span class="text-danger">*</span></label>
            <textarea class="form-control" id="q-answer" name="answer" rows="6" required></textarea>
            <div class="form-text">A blank line starts a new paragraph.</div>
          </div>
          <button type="submit" class="btn btn-bba-green w-100">Add FAQ</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="bba-panel">
      <div class="bba-panel-head"><h2>Questions</h2></div>
      <div class="bba-panel-body" id="faq-list">
        <?= $this->include('admin/faqs/_list') ?>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
