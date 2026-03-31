<?php

use app\models\Tasks;
use app\widgets\Pagination;
use app\widgets\Sorting;

/**
 * @var array $pagination
 * @var Tasks $task
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
                <td>
                    <label>
                        <input type="text" name="name[<?= $task->getId() ?>]" value="<?= htmlspecialchars($task->getName(), ENT_QUOTES, 'UTF-8') ?>" class="form-control"/>
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text" name="email[<?= $task->getId() ?>]" value="<?= htmlspecialchars($task->getEmail(), ENT_QUOTES, 'UTF-8') ?>" class="form-control"/>
                    </label>
                </td>
                <td>
                    <label>
                        <textarea name="text[<?= $task->getId() ?>]" cols="5" rows="1" class="form-control"><?= htmlspecialchars($task->getText(), ENT_QUOTES, 'UTF-8') ?></textarea>
                    </label>
                </td>
                <td>
                    <label>
                        <select name="status[<?= $task->getId() ?>]" class="form-control">
                            <?php foreach (Tasks::getStatusList() as $status => $name): ?>
                                <option <?= $status == $task->getStatus() ? 'selected' : '' ?> value="<?= $status ?>"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </td>
                <td><?= htmlspecialchars(date('d.m.Y (H:i:s)', $task->getCreated_at()), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="5"></td>
            <td>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </td>
        </tr>
        </tbody>
    </table>
</form>

<?php Pagination::widget($pagination) ?>
