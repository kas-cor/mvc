<?php

namespace app\core;

class Sorting {

    const ORDER_ASC = 'ASC';
    const ORDER_DESC = 'DESC';

    static function setSort($column, $order) {
        if (self::checkSortColumn($column)) {
            $_SESSION['ordering'][get_called_class()][$column] = $order;
        }
    }

    static function getSort($column) {
        if (self::checkSortColumn($column)) {
            return $_SESSION['ordering'][get_called_class()][$column];
        }
    }

    static function setSortToggle($column) {
        if (self::checkSortColumn($column)) {
            if (!self::getSort($column)) {
                $_SESSION['ordering'][get_called_class()][$column] = self::ORDER_ASC;
            } elseif (self::getSort($column) == self::ORDER_ASC) {
                $_SESSION['ordering'][get_called_class()][$column] = self::ORDER_DESC;
            } elseif (self::getSort($column) == self::ORDER_DESC) {
                unset($_SESSION['ordering'][get_called_class()][$column]);
            }
        }
    }

    static function getSorts() {
        return $_SESSION['ordering'][get_called_class()];
    }

    static function sortingWidget($title, $column, $path) {
        if (self::checkSortColumn($column)) {
            if (self::getSort($column) == self::ORDER_ASC) {
                $icon = '<i class="fas fa-sort-amount-down">';
            } elseif (self::getSort($column) == self::ORDER_DESC) {
                $icon = '<i class="fas fa-sort-amount-up">';
            } else {
                $icon = '';
            }
            ?>
            <a href="<?= $path ?>?column=<?= $column ?>"><?= $title ?></a><?= $icon ?></i>
            <?php
        }
    }

    static function checkSortColumn($column) {
        if (property_exists(get_called_class(), $column)) {
            return true;
        } else {
            throw new \ErrorException('Column "' . $column . '" not fount in model "' . get_called_class() . '"!');
        }
    }

}
