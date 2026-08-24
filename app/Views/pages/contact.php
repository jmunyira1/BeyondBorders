<?= $this->extend('layouts/public') ?>

<?php if (($faqs ?? []) !== []): ?>
<?= $this->section('head') ?>
<?php
// FAQPage — lets Google and AI assistants surface these answers directly.
$faqLd = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => []];
foreach ($faqs as $faq) {
    $faqLd['mainEntity'][] = [
        '@type'          => 'Question',
        'name'           => $faq['question'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
    ];
}
?>
<?= json_ld($faqLd) ?>
<?= $this->endSection() ?>
<?php endif; ?>

<?= $this->section('content') ?>

<header class="bb-pagehead">
  <div class="container">
    <h1><?= esc(site('contactHeroHeading')) ?></h1>
    <p class="bb-lede"><?= esc(site('contactHeroLede')) ?></p>
  </div>
</header>

<!-- Contact channels — text blocks with a top rule, not icon cards -->
<section>
  <div class="container">
    <div class="row g-4 g-lg-5">
      <div class="col-md-4">
        <div class="bb-channel">
          <h3>WhatsApp</h3>
          <p><?= esc(site('contactWhatsAppText')) ?></p>
          <button type="button" class="btn btn-bba-green btn-sm" data-wa-open="">Chat on WhatsApp</button>
        </div>
      </div>
      <div class="col-md-4">
        <div class="bb-channel">
          <h3>Call us</h3>
          <p><?= esc(site('contactCallText')) ?></p>
          <a href="tel:<?= esc(site('phoneLink'), 'attr') ?>" class="bb-link"><?= esc(site('phone')) ?></a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="bb-channel">
          <h3>Email</h3>
          <p><?= esc(site('contactEmailText')) ?></p>
          <a href="mailto:<?= esc(site('email'), 'attr') ?>" class="bb-link"><?= esc(site('email')) ?></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Message form + FAQs -->
<section class="section-sand">
  <div class="container">
    <div class="row g-4 g-lg-5">
      <div class="col-lg-6">
        <h2 class="mb-4">We reply within 24 hours</h2>
        <?= view('pages/_contact_form') ?>
      </div>

      <?php if ($faqs !== []): ?>
      <div class="col-lg-6">
        <h2 class="mb-4">Quick answers</h2>
        <div class="accordion" id="faq">
          <?php foreach ($faqs as $i => $faq): ?>
            <div class="accordion-item">
              <h3 class="accordion-header">
                <button class="accordion-button <?= $i === 0 ? '' : 'collapsed' ?>" type="button"
                        data-bs-toggle="collapse" data-bs-target="#faq<?= $faq['id'] ?>"
                        aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>" aria-controls="faq<?= $faq['id'] ?>">
                  <?= esc($faq['question']) ?>
                </button>
              </h3>
              <div id="faq<?= $faq['id'] ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>" data-bs-parent="#faq">
                <div class="accordion-body"><?= nl2paras($faq['answer'], 'mb-2') ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?= view('partials/cta_band', ['ctaHeading' => 'Ready when you are.']) ?>

<?= $this->endSection() ?>
