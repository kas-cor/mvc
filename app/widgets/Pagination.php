<?php

namespace app\widgets;

use app\App;
use app\core\Widget;

/**
 * Class Pagination
 * @package app\widgets
 */
class Pagination extends Widget {

    /**
     * Render widget
     * ```php
     * Pagination::widget($pagination)
     * ```
     * @param array $pagination
     */
    static function widget(array $pagination) {
        $length = App::$components['db']['pagination'];
        ?>
        <nav aria-label="">
            <ul class="pagination">
                <?php if ($pagination['current'] - 1 > 0): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $pagination['current'] - 1 ?>">Предыдущая</a>
                    </li>
                <?php endif; ?>
                <?php for ($start = 0; $start < $pagination['total']; $start += $length): ?>
                    <?php $page = ceil($start / $length) + 1 ?>
                    <li class="page-item <?= $page == $pagination['current'] ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $page ?>"><?= $page ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($pagination['current'] * $length < $pagination['total']): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $pagination['current'] + 1 ?>">Следующая</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php
    }

}
