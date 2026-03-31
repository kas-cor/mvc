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
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
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
                <td><?= htmlspecialchars($task->getId(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($task->getName(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($task->getEmail(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($task->getText(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars(Tasks::getStatusList()[$task->getStatus()], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars(date('d.m.Y (H:i:s)', $task->getCreated_at()), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td></td>
            <td>
                <label>
                    <input type="text" name="name" value="<?= htmlspecialchars($post['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="form-control"/>
                </label>
            </td>
            <td>
                <label>
                    <input type="text" name="email" value="<?= htmlspecialchars($post['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="form-control"/>
                </label>
            </td>
            <td>
                <label>
                    <textarea name="text" cols="5" rows="1" class="form-control"><?= htmlspecialchars($post['text'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
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
