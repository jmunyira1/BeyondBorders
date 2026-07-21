<?php
/**
 * Same look as bba_pager, but ordinary links — used where there is no htmx
 * fragment endpoint to swap into.
 *
 * @var CodeIgniter\Pager\PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>
<ul class="pagination mb-0">
  <?php if ($pager->hasPrevious()): ?>
    <li class="page-item"><a class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="Previous page"><i class="bi bi-chevron-left" aria-hidden="true"></i></a></li>
  <?php else: ?>
    <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-left" aria-hidden="true"></i></span></li>
  <?php endif; ?>

  <?php foreach ($pager->links() as $link): ?>
    <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
      <?php if ($link['active']): ?>
        <span class="page-link" aria-current="page"><?= esc($link['title']) ?></span>
      <?php else: ?>
        <a class="page-link" href="<?= $link['uri'] ?>"><?= esc($link['title']) ?></a>
      <?php endif; ?>
    </li>
  <?php endforeach; ?>

  <?php if ($pager->hasNext()): ?>
    <li class="page-item"><a class="page-link" href="<?= $pager->getNext() ?>" aria-label="Next page"><i class="bi bi-chevron-right" aria-hidden="true"></i></a></li>
  <?php else: ?>
    <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-right" aria-hidden="true"></i></span></li>
  <?php endif; ?>
</ul>
