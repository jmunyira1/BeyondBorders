<?php
// Shared modal shell. Screens load panels into #bba-modal-body over htmx and the
// admin JS opens it automatically; controllers close it with HX-Trigger.
?>
<div class="modal fade" id="bba-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content" id="bba-modal-body">
      <!-- swapped in -->
    </div>
  </div>
</div>
