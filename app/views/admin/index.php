<?php /** @noinspection PhpUnhandledExceptionInspection */

/**
 * @var array $pagination
 * @var $task Tasks
 */

use app\models\Tasks;
use app\widgets\Pagination;
use app\widgets\Sorting;

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
                <td>
                    <label>
                        <input type="text" name="name[<?= $task->getId() ?>]" value="<?= $task->getName() ?>" class="form-control"/>
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text" name="email[<?= $task->getId() ?>]" value="<?= $task->getEmail() ?>" class="form-control"/>
                    </label>
                </td>
                <td>
                    <label>
                        <textarea name="text[<?= $task->getId() ?>]" cols="5" rows="1" class="form-control"><?= $task->getText() ?></textarea>
                    </label>
                </td>
                <td>
                    <label>
                        <select name="status[<?= $task->getId() ?>]" class="form-control">
                            <?php foreach (Tasks::getStatusList() as $status => $name): ?>
                                <option <?= $status == $task->getStatus() ? 'selected' : '' ?> value="<?= $status ?>"><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </td>
                <td><?= date('d.m.Y (H:i:s)', $task->getCreated_at()) ?></td>
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
