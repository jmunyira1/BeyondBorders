<?php
/**
 * Inline validation message for a single field.
 *
 * @var array  $errors
 * @var string $field
 */
if (! empty($errors[$field])): ?>
  <span class="bba-field-error"><?= esc($errors[$field]) ?></span>
<?php endif; ?>
