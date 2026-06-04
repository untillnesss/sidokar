<?php if ($pager->hasPrevious()) : ?>
    <a href="<?= $pager->getPreviousPage() ?>" class="btn btn-outline-secondary btn-sm">
        ← Sebelumnya
    </a>
<?php endif ?>

<?php foreach ($pager->links() as $link) : ?>
    <a href="<?= $link['uri'] ?>"
       class="btn btn-sm <?= $link['active'] ? 'btn-primary' : 'btn-outline-primary' ?>">
        <?= $link['title'] ?>
    </a>
<?php endforeach ?>

<?php if ($pager->hasNext()) : ?>
    <a href="<?= $pager->getNextPage() ?>" class="btn btn-outline-secondary btn-sm">
        Selanjutnya →
    </a>
<?php endif ?>