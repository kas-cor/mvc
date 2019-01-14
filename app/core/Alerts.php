<?php

namespace app\core;

class Alerts {

    const TYPE_PRIMARY = 'primary';
    const TYPE_SECONDARY = 'secondary';
    const TYPE_SUCCESS = 'success';
    const TYPE_DANGER = 'danger';
    const TYPE_WARNING = 'warning';
    const TYPE_INFO = 'info';
    const TYPE_LIGHT = 'light';
    const TYPE_DARK = 'dark';

    static function addFlash($type, $text) {
        $_SESSION['flashAlerts'][] = [
            'type' => $type,
            'text' => $text,
        ];
    }

    static function setFlash($type, $text) {
        $_SESSION['flashAlerts'] = [
            'type' => $type,
            'text' => $text,
        ];
    }
    
    static function isPresent() {
        return isset($_SESSION['flashAlerts']);
    }

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
                </div>
                <?php
            }
            unset($_SESSION['flashAlerts']);
        }
    }

}
