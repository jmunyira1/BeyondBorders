<?php if (! site('whatsappEnabled', true)) {
    return;
} ?>
<?php
// The visitor's typed message is appended to the configured prefill so the
// business always gets context even if they send the greeting unchanged.
$prefill = (string) site('whatsappPrefill');
?>
<div class="bba-wa" data-wa-number="<?= esc(preg_replace('/\D+/', '', (string) site('whatsappNumber')), 'attr') ?>" data-wa-prefill="<?= esc($prefill, 'attr') ?>">

  <div class="bba-wa-panel" id="bba-wa-panel" role="dialog" aria-modal="false" aria-labelledby="bba-wa-title" hidden>
    <div class="bba-wa-head">
      <div class="bba-wa-avatar" aria-hidden="true"><i class="bi bi-whatsapp"></i></div>
      <div class="bba-wa-who">
        <p class="bba-wa-name" id="bba-wa-title"><?= esc(site('whatsappName')) ?></p>
        <p class="bba-wa-role"><?= esc(site('whatsappRole')) ?></p>
      </div>
      <button type="button" class="bba-wa-close" data-wa-close aria-label="Close chat">
        <i class="bi bi-x-lg" aria-hidden="true"></i>
      </button>
    </div>

    <div class="bba-wa-body">
      <div class="bba-wa-bubble">
        <?= nl2br(esc(site('whatsappGreeting'))) ?>
        <span class="bba-wa-time"><?= date('H:i') ?></span>
      </div>
    </div>

    <form class="bba-wa-foot" data-wa-form>
      <label class="visually-hidden" for="bba-wa-message">Your message</label>
      <textarea class="bba-wa-input" id="bba-wa-message" name="message" rows="1"
                placeholder="Type a message…" maxlength="900"></textarea>
      <button type="submit" class="bba-wa-send" aria-label="Start chat on WhatsApp">
        <i class="bi bi-send-fill" aria-hidden="true"></i>
      </button>
    </form>

    <p class="bba-wa-note">Opens WhatsApp · we usually reply within minutes</p>
  </div>

  <button type="button" class="bba-whatsapp" id="bba-wa-toggle"
          aria-expanded="false" aria-controls="bba-wa-panel"
          aria-label="Chat with us on WhatsApp">
    <i class="bi bi-whatsapp" aria-hidden="true"></i>
    <span class="bba-wa-dot" aria-hidden="true"></span>
  </button>
</div>
