<?php

namespace app\widgets;

use app\core\Widget;

/**
 * Class Sorting
 *
 * @package app\widgets
 */
class Sorting extends Widget {

    /**
     * Ordering (asc)
     */
    const ORDER_ASC = 'ASC';

    /**
     * Ordering (desc)
     */
    const ORDER_DESC = 'DESC';

    /**
     * Setting sorting
     *
     * @param string $modelName
     * @param string $column
     * @param string $order
     */
    static function setSort($modelName, $column, $order) {
        if (self::checkSortColumn($modelName, $column)) {
            $_SESSION['ordering'][$modelName][$column] = $order;
        }
    }

    /**
     * Getting sorting
     *
     * @param string $modelName
     * @param string $column
     *
     * @return string|null
     */
    static function getSort($modelName, $column) {
        return self::checkSortColumn($modelName, $column) ? $_SESSION['ordering'][$modelName][$column] : null;
    }

    /**
     * Setting sorting (toggle)
     *
     * @param string $modelName
     * @param string $column
     */
    static function setSortToggle($modelName, $column) {
        if (self::checkSortColumn($modelName, $column)) {
            if (!self::getSort($modelName, $column)) {
                $_SESSION['ordering'][$modelName][$column] = self::ORDER_ASC;
            } elseif (self::getSort($modelName, $column) == self::ORDER_ASC) {
                $_SESSION['ordering'][$modelName][$column] = self::ORDER_DESC;
            } elseif (self::getSort($modelName, $column) == self::ORDER_DESC) {
                unset($_SESSION['ordering'][$modelName][$column]);
            }
        }
    }

    /**
     * Getting all sorting by model name
     *
     * @param string $modelName
     *
     * @return array
     */
    static function getSorts($modelName) {
        return $_SESSION['ordering'][$modelName] ?: [];
    }

    /**
     * Render widget
     *
     * @param string $modelName
     * @param string $title
     * @param string $column
     * @param string $path
     */
    static function widget($modelName, $title, $column, $path) {
        if (self::checkSortColumn($modelName, $column)) {
            if (self::getSort($modelName, $column) == self::ORDER_ASC) {
                $icon = 'fas fa-sort-amount-down';
            } elseif (self::getSort($modelName, $column) == self::ORDER_DESC) {
                $icon = 'fas fa-sort-amount-up';
            } else {
                $icon = '';
            }
            ?>
            <a href="<?= $path ?>?column=<?= $column ?>"><?= $title ?></a><i class="<?= $icon ?>"></i>
            <?php
        }
    }

    /**
     * Checking sorting
     *
     * @param string $modelName
     * @param string $column
     *
     * @return bool
     */
    static function checkSortColumn($modelName, $column) {
        return property_exists($modelName, $column) OR die('Column "' . $column . '" not fount in model "' . $modelName . '"!');
    }

}
