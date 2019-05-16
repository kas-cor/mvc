<?php

/**
 * @var string $title
 * @var string $content
 */

use app\core\Assets;
use app\widgets\Alerts;
use app\models\Users;

$current_controller = app\App::$components['routes']['controller'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?= $title ?></title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous"/>
    <?php Assets::getCss() ?>
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/">Tasks</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?= $current_controller == 'main' ? 'active' : '' ?>">
                    <a class="nav-link" href="/">Задачи</a>
                </li>
                <li class="nav-item <?= $current_controller == 'admin' ? 'active' : '' ?>">
                    <!--suppress HtmlUnknownTarget -->
                    <a class="nav-link" href="/admin">Админка</a>
                </li>
            </ul>
            <?php if (Users::isAuth()): ?>
                <div><?= $_SESSION['user']['login'] ?> (<!--suppress HtmlUnknownTarget --><a href="/admin/logout">выйти</a>)</div>
            <?php endif; ?>
        </div>
    </nav>

    <br/>

    <?php Alerts::widget() ?>

    <?= $content ?>
</div>

<?php Assets::getJs() ?>
</body>
</html>
