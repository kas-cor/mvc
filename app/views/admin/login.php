<form action="" method="POST">
    <div class="form-group">
        <label for="login">Логин</label>
        <input type="text" name="login" value="<?= $post['login'] ?>" class="form-control" id="login" placeholder="Введите логин" />
    </div>
    <div class="form-group">
        <label for="password">Пароль</label>
        <input type="password" name="password" class="form-control" id="password" placeholder="Введите пароль">
    </div>
    <button type="submit" class="btn btn-primary">Вход</button>
</form>