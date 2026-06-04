<nav>
    <ul class="pagination justify-content-center">

        <!-- Sebelumnya -->
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getPreviousPageURI() ?>">
                    ← Sebelumnya
                </a>
            </li>
        <?php endif ?>

        <!-- Nomor -->
        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link" href="<?= $link['uri'] ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <!-- Selanjutnya -->
        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getNextPageURI() ?>">
                    Selanjutnya →
                </a>
            </li>
        <?php endif ?>

    </ul>
</nav>