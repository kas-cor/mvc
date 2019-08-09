<?php

namespace app\widgets;

/**
 * Class Alerts
 *
 * @package app\widgets
 */
class Alerts {

    /**
     * Type (primary)
     */
    const TYPE_PRIMARY = 'primary';

    /**
     * Type (secondary)
     */
    const TYPE_SECONDARY = 'secondary';

    /**
     * Type (success)
     */
    const TYPE_SUCCESS = 'success';

    /**
     * Type (danger)
     */
    const TYPE_DANGER = 'danger';

    /**
     * Type (warning)
     */
    const TYPE_WARNING = 'warning';

    /**
     * Type (info)
     */
    const TYPE_INFO = 'info';

    /**
     * Type (light)
     */
    const TYPE_LIGHT = 'light';

    /**
     * Type (dark)
     */
    const TYPE_DARK = 'dark';

    /**
     * Add flash message
     *
     * @param string $type
     * @param string $text
     */
    static function addFlash($type, $text) {
        $_SESSION['flashAlerts'][] = [
            'type' => $type,
            'text' => $text,
        ];
    }

    /**
     * Set flash message
     *
     * @param string $type
     * @param string $text
     */
    static function setFlash($type, $text) {
        $_SESSION['flashAlerts'] = [
            'type' => $type,
            'text' => $text,
        ];
    }

    /**
     * Getting is present flash messages
     *
     * @return bool
     */
    static function isPresent() {
        return isset($_SESSION['flashAlerts']);
    }

    /**
     * Render widget
     * ```php
     * Alerts::widget()
     * ```
     */
    static function widget() {
        if ($_SESSION['flashAlerts']) {
            if (is_array($_SESSION['flashAlerts'])) {
                $alerts = $_SESSION['flashAlerts'];
            } else {
                $alerts[] = $_SESSION['flashAlerts'];
            }
            foreach ($alerts as $alert) {
                ?>
                <div class="alert alert-<?= $alert['type'] ?>" role="alert">
                    <?= $alert['text'] ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php
            }
            unset($_SESSION['flashAlerts']);
        }
    }

}
