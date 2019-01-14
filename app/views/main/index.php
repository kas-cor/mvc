<?php

use app\models\Tasks;
use app\core\Paginations;
?>
<form action="" method="POST">
    <table class="table">
        <thead>
            <tr>
                <th><?php Tasks::sortingWidget('ID', 'id', '/main/sort') ?></th>
                <th><?php Tasks::sortingWidget('Имя', 'name', '/main/sort') ?></th>
                <th><?php Tasks::sortingWidget('e-mail', 'email', '/main/sort') ?></th>
                <th>Текст</th>
                <th><?php Tasks::sortingWidget('Статус', 'status', '/main/sort') ?></th>
                <th><?php Tasks::sortingWidget('Создан', 'created_at', '/main/sort') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pagination['items'] as $task): ?>
                <tr>
                    <td><?= $task->getId() ?></td>
                    <td><?= $task->getName() ?></td>
                    <td><?= $task->getEmail() ?></td>
                    <td><?= $task->getText() ?></td>
                    <td><?= Tasks::getStatusList()[$task->getStatus()] ?></td>
                    <td><?= date('d.m.Y (H:i:s)', $task->getCreated_at()) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td></td>
                <td><input type="text" name="name" value="<?= $post['name'] ?>" class="form-control" /></td>
                <td><input type="text" name="email" value="<?= $post['email'] ?>" class="form-control" /></td>
                <td><textarea name="text" cols="5" rows="1" class="form-control"><?= $post['text'] ?></textarea></td>
                <td></td>
                <td><button type="submit" class="btn btn-primary">Добавить</button></td>
            </tr>
        </tbody>
    </table>
</form>

<?php Paginations::widget($pagination) ?>
