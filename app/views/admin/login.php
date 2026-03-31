<?php
/**
 * @var array $post
 */
?>
<form action="" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <div class="form-group">
        <label for="login">Логин</label>
        <input type="text" name="login" value="<?= htmlspecialchars($post['login'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="form-control" id="login" placeholder="Введите логин"/>
    </div>
    <div class="form-group">
        <label for="password">Пароль</label>
        <input type="password" name="password" class="form-control" id="password" placeholder="Введите пароль">
    </div>
    <button type="submit" class="btn btn-primary">Вход</button>
</form>
