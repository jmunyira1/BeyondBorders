<?php
/**
 * Swapped in place of a form after a successful submission.
 *
 * @var string $heading
 * @var string $message  Escaped unless $raw is true.
 * @var bool   $raw
 */
$raw ??= false;
?>
<div class="bba-form p-4 p-lg-5 text-center" role="status" aria-live="polite">
  <i class="bi bi-check-circle text-success d-block mb-3" style="font-size:2.5rem" aria-hidden="true"></i>
  <h2 class="h4 mb-2"><?= esc($heading) ?></h2>
  <p class="text-body-secondary mb-4"><?= $raw ? $message : esc($message) ?></p>
  <div class="d-flex flex-wrap gap-2 justify-content-center">
    <a href="<?= url_to('packages') ?>" class="btn btn-bba-outline btn-sm">Browse more trips</a>
    <a href="<?= esc(whatsapp_link(site('whatsappPrefill')), 'attr') ?>" target="_blank" rel="noopener" class="btn btn-bba-green btn-sm">
      <i class="bi bi-whatsapp me-2" aria-hidden="true"></i>Chat on WhatsApp
    </a>
  </div>
</div>
