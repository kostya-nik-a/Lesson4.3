<?php

require_once 'function.php';
require_once 'users.model.php';

$errors = [];

if (!empty($_POST['action'])) {
    switch ($_POST['action']) {
        case 'auth':
            // Если передан пароль и логин
            if (!empty($_POST['user']['login']) && !empty($_POST['user']['password'])) {
                // Если пользователь существует
                if ($user = getUserByLogin($_POST['user']['login'])) {
                    // Проверяем пароль
                    // Если пароль совпадает, то
                    if (password_verify($_POST['user']['password'], $user['hash_password'])) {
                        // Пишем в сессию
                        $_SESSION['user'] = $user['login'];
                        // и редиректим на tasks.php
                        redirect('tasks');
                    } else {
                        // Если не совпадает, то выдаем ошибку
                        array_push($errors, "Неправильный логин или пароль");
                    }
                } else {
                    // Если пользователь не существует, то выдаем ошибку
                    array_push($errors, "Такого пользователя не существует. Зарегистрируйтесь.");
                }
            } else {
                // Показываем ошибку что нет логина или пароля
                array_push($errors, "Вы забыли указать логин или пароль");
            }
            break;
        case 'register':
            // Если передан пароль и логин
            if (!empty($_POST['user']['login']) && !empty($_POST['user']['password'])) {
                // Если пользователь существует
                if (getUserByLogin($_POST['user']['login'])) {
                    array_push($errors, "Такой пользователь уже существует в базе данных");
                } else {
                    // Если не существует, то создаем нового
                    // Если пользователь создан
                    if ($user = createUser($_POST['user'])) {
                        // Пишем в сессию
                        $_SESSION['user'] = $_POST['user']['login'];
                        // и редиректим на tasks.php
                        redirect('tasks');
                    } else {
                        // Если пользоватеь не создан
                        // Бросаем исключение
                        array_push($errors, "Ошибка регистрации пользователя");
                    }
                }
            } else {
                // Показываем ошибку что нет логина или пароля
                array_push($errors, "Введите все необходимые данные для регистрации");
            }
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>Авторизация</title>
</head>
<body>
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="form-wrap">
                    <h1>Авторизация</h1>
                    <?php foreach ($errors as $error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endforeach; ?>
                    <form method="POST">
                        <div class="form-group">
                            <label for="lg" class="sr-only">Логин</label>
                            <input type="text" placeholder="Логин" name="user[login]" id="lg" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="key" class="sr-only">Пароль</label>
                            <input type="password"  placeholder="Пароль" name="user[password]" id="key" class="form-control">
                        </div>
                        <button type="submit" name= "action" id="btn-login" class="btn btn-custom btn-lg btn-block" value="auth">Войти</button>
                        <button type="submit" name= "action" id="btn-registration" class="btn btn-custom btn-lg btn-block" value="register">Регистрация</button>
                    </form>
                    <hr>
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>
</body>
</html>
