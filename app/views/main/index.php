<?php

use app\models\Tasks;
use app\widgets\{Pagination, Sorting};

/**
 * @var array $pagination
 * @var Tasks $task
 * @var array $post
 */
?>
<form action="" method="POST">
    <table class="table">
        <thead>
        <tr>
            <th><?php Sorting::widget(Tasks::className(), 'ID', 'id', '/main/sort') ?></th>
            <th><?php Sorting::widget(Tasks::className(), 'Имя', 'name', '/main/sort') ?></th>
            <th><?php Sorting::widget(Tasks::className(), 'e-mail', 'email', '/main/sort') ?></th>
            <th>Текст</th>
            <th><?php Sorting::widget(Tasks::className(), 'Статус', 'status', '/main/sort') ?></th>
            <th><?php Sorting::widget(Tasks::className(), 'Создан', 'created_at', '/main/sort') ?></th>
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
            <td>
                <label>
                    <input type="text" name="name" value="<?= $post['name'] ?>" class="form-control"/>
                </label>
            </td>
            <td>
                <label>
                    <input type="text" name="email" value="<?= $post['email'] ?>" class="form-control"/>
                </label>
            </td>
            <td>
                <label>
                    <textarea name="text" cols="5" rows="1" class="form-control"><?= $post['text'] ?></textarea>
                </label>
            </td>
            <td></td>
            <td>
                <button type="submit" class="btn btn-primary">Добавить</button>
            </td>
        </tr>
        </tbody>
    </table>
</form>

<?php Pagination::widget($pagination) ?>
