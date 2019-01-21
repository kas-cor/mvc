<?php

namespace app\widgets;

use app\core\Widget;

class Sorting extends Widget {

    const ORDER_ASC = 'ASC';
    const ORDER_DESC = 'DESC';

    static function setSort($modelName, $column, $order) {
        if (self::checkSortColumn($modelName, $column)) {
            $_SESSION['ordering'][$modelName][$column] = $order;
        }
    }

    static function getSort($modelName, $column) {
        if (self::checkSortColumn($modelName, $column)) {
            return $_SESSION['ordering'][$modelName][$column];
        }
    }

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

    static function getSorts($modelName) {
        return $_SESSION['ordering'][$modelName] ?: [];
    }

    static function widget($modelName, $title, $column, $path) {
        if (self::checkSortColumn($modelName, $column)) {
            if (self::getSort($modelName, $column) == self::ORDER_ASC) {
                $icon = '<i class="fas fa-sort-amount-down">';
            } elseif (self::getSort($modelName, $column) == self::ORDER_DESC) {
                $icon = '<i class="fas fa-sort-amount-up">';
            } else {
                $icon = '';
            }
            ?>
            <a href="<?= $path ?>?column=<?= $column ?>"><?= $title ?></a><?= $icon ?></i>
            <?php
        }
    }

    static function checkSortColumn($modelName, $column) {
        if (property_exists($modelName, $column)) {
            return true;
        } else {
            throw new \ErrorException('Column "' . $column . '" not fount in model "' . $modelName . '"!');
        }
    }

}
