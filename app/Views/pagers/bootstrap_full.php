<?php if ($pager->hasPrevious() || $pager->hasNext()) : ?>

<nav>
    <ul class="pagination justify-content-center">

        <!-- PREVIOUS -->
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a class="page-link"
                   href="<?= $pager->getPreviousPage() ?>">
                    Sebelumnya
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link">Sebelumnya</span>
            </li>
        <?php endif ?>

        <!-- NUMBER -->
        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link"
                   href="<?= $link['uri'] ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <!-- NEXT -->
        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a class="page-link"
                   href="<?= $pager->getNextPage() ?>">
                    Selanjutnya
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link">Selanjutnya</span>
            </li>
        <?php endif ?>

    </ul>
</nav>

<?php endif ?>