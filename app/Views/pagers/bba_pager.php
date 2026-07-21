<?php
/**
 * Pagination that swaps through htmx like the rest of the filter, while staying
 * a set of ordinary links for anyone without JavaScript.
 *
 * @var CodeIgniter\Pager\PagerRenderer $pager
 */
$pager->setSurroundCount(2);

// Page links carry the current filters already (the pager reuses the query
// string), so we only need to redirect them at the fragment endpoint.
$asFragment = static fn (string $url): string => str_replace(
    url_to('packages'),
    url_to('packages-filter'),
    $url
);
?>
<ul class="pagination mb-0">
  <?php if ($pager->hasPrevious()): ?>
    <li class="page-item">
      <a class="page-link" href="<?= $pager->getPrevious() ?>"
         hx-get="<?= esc($asFragment($pager->getPrevious()), 'attr') ?>"
         hx-target="#packages-results" hx-push-url="<?= esc($pager->getPrevious(), 'attr') ?>"
         aria-label="Previous page">
        <i class="bi bi-chevron-left" aria-hidden="true"></i>
      </a>
    </li>
  <?php else: ?>
    <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-left" aria-hidden="true"></i></span></li>
  <?php endif; ?>

  <?php foreach ($pager->links() as $link): ?>
    <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
      <?php if ($link['active']): ?>
        <span class="page-link" aria-current="page"><?= esc($link['title']) ?></span>
      <?php else: ?>
        <a class="page-link" href="<?= $link['uri'] ?>"
           hx-get="<?= esc($asFragment($link['uri']), 'attr') ?>"
           hx-target="#packages-results" hx-push-url="<?= esc($link['uri'], 'attr') ?>"><?= esc($link['title']) ?></a>
      <?php endif; ?>
    </li>
  <?php endforeach; ?>

  <?php if ($pager->hasNext()): ?>
    <li class="page-item">
      <a class="page-link" href="<?= $pager->getNext() ?>"
         hx-get="<?= esc($asFragment($pager->getNext()), 'attr') ?>"
         hx-target="#packages-results" hx-push-url="<?= esc($pager->getNext(), 'attr') ?>"
         aria-label="Next page">
        <i class="bi bi-chevron-right" aria-hidden="true"></i>
      </a>
    </li>
  <?php else: ?>
    <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-right" aria-hidden="true"></i></span></li>
  <?php endif; ?>
</ul>
