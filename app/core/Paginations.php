<?php

namespace app\core;

use app\App;

class Paginations {

    static function widget($pagination) {
        $length = App::$components['db']['paginations'];
        ?>
        <nav aria-label="">
            <ul class="pagination">
                <?php if ($pagination['current'] - 1 > 0): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?= $pagination['current'] - 1 ?>">Предыдущая</a></li>
                <?php endif; ?>
                <?php for ($start = 0; $start < $pagination['total']; $start += $length): ?>
                    <?php $page = ceil($start / $length) + 1 ?>
                    <li class="page-item <?= $page == $pagination['current'] ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $page ?>"><?= $page ?></a></li>
                <?php endfor; ?>
                <?php if ($pagination['current'] * $length < $pagination['total']): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?= $pagination['current'] + 1 ?>">Следующая</a></li>
                    <?php endif; ?>
            </ul>
        </nav>
        <?php
    }

}
