<?php

require_once 'function.php';
require_once 'tasks.model.php';
require_once 'users.model.php';

if (!isAuthorized()) {
    http_response_code(403);
    die();
} else {
    $user = getUserByLogin($_SESSION['user']);
    $_SESSION['user_id'] = $user['id'];
}

if (!empty($_GET['action'])) {
    switch ($_GET['action']) {
        case 'complete':
            editTask('complete', $_GET['id']);
        break;
        case 'delete':
            editTask('delete', $_GET['id']);
        break;
    }
}

if (!empty($_POST['action'])) {
    $params = array_merge([], $_POST);
    switch ($_POST['action']) {
        case 'add':
            addTask($params);
        break;
        case 'update':
            updateTask($params);
        break;
        case 'assign':
            assignTask($params);
        break;
    }
}


?>


<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <title>Test</title>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1><?php echo $_SESSION['user']?>, Ваш список дел на сегодня:</h1>
                    <div class="row" style="padding: 20px 0;">
                        <div class="col-lg-4">
                            <form method="POST" action="" class="form-inline">
                                <?php
                                if (!empty($_GET['action']) && ( $_GET['action'] == 'update' )) {
                                    $update = getTaskById($_GET['id']);
                                    ?>
                                    <div class="form-group">
                                        <input type="text" name="description" placeholder="Описание задачи" value="<?= $update['description'] ?>" class="form-control">
                                        <input type="hidden" name="id" value="<?= $update['id'] ?>">
                                        <button type="submit" name="action" value="update" class="btn btn-success">Сохранить</button>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="form-group">
                                        <input type="text" name="description" placeholder="Описание задачи" value="" class="form-control">
                                        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                                        <button type="submit" name="action" value="add" class="btn btn-success">Добавить</button>
                                    </div>
                                    <?php
                                }
                                ?>
                            </form>
                        </div>
                        <div class="col-lg-8">
                            <form method="POST" class="form-inline">
                                <div class="form-group">
                                    <label for="sort">Сортировать по:</label>
                                    <select name="sort_by" id="sort" class="form-control">

                                        <option selected disabled>Выберите тип сортировки</option>
                                        <option value="date_created">Дате добавления</option>
                                        <option value="status">Статусу</option>
                                        <option value="description">Описанию</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="sort" value="Отсортировать" class="btn btn-default">
                                </div>

                                <?php

                                ?>
                            </form>
                        </div>
                    </div>
                    <table class="table table-striped m-t-xl">
                        <thead>
                        <tr>
                            <th>Описание задачи</th>
                            <th>Дата добавления</th>
                            <th>Статус</th>
                            <th>Действие</th>
                            <th>Ответственный</th>
                            <th>Автор</th>
                            <th>Закрепить задачу за пользователем</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <?php

                            if (isset($_POST['sort_by'])) {
                                $tasks = getUserTasks($_SESSION['user_id'], $_POST['sort_by']);
                            } else {
                                $tasks = getUserTasks($_SESSION['user_id'], null);
                            }

                            foreach ($tasks as $task) {
                            $status = ($task['is_done'] == 1) ? 'Выполнено' : 'В процессе';
                            $color = ($task['is_done'] == 1) ? 'green' : 'red';
                            ?>
                            <td><?= $task['description'] ?></td>
                            <td><?= $task['date_added'] ?></td>
                            <td style="color: <?= $color ?>"><?= $status ?></td>
                            <td>
                                <a href="?id=<?= $task['id'] ?>&action=update">Изменить</a>
                                <?php
                                if ($task['assigned_user_id'] == $task['user_id'] || $task['assigned_user_id'] === NULL) {
                                    ?>
                                    <a href="?id=<?= $task['id'] ?>&action=complete">Выполнить</a>
                                    <?php
                                }
                                ?>
                                <a href="?id=<?= $task['id'] ?>&action=delete">Удалить</a>
                            </td>
                            <td><?= ($task['assigned_user_id'] === NULL) ? 'Вы' : $task['assignee'] ?></td>
                            <td><?= $task['login'] ?></td>
                            <td>
                                <form method="POST" class="form-inline">
                                    <div class="form-group">
                                        <select name="assignee" class="form-control">
                                            <?php
                                            $users = getAllUsers($_SESSION['user_id']);

                                            while ($user = $users->fetch(PDO::FETCH_ASSOC)) {
                                                ?>
                                                <option value="<?= $user['id'] ?>"><?= $user['login'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                        <button type="submit" name="action" value="assign" class="btn btn-default">
                                            Переложить ответственность
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <h3>Также, посмотрите, что от Вас требуют другие люди:</h3>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Описание задачи</th>
                            <th>Дата добавления</th>
                            <th>Статус</th>
                            <th>Действие</th>
                            <th>Ответственный</th>
                            <th>Автор</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <?php
                            $tasks = getAssignedTasks($_SESSION['user_id']);

                            foreach ($tasks as $task) {
                            $status = ($task['is_done'] == 1) ? 'Выполнено' : 'В процессе';
                            $color = ($task['is_done'] == 1) ? 'green' : 'red';
                            ?>
                            <td><?= $task['description'] ?></td>
                            <td><?= $task['date_added']?></td>
                            <td style="color: <?= $color?>"><?= $status?></td>
                            <td>
                                <a href="?id=<?=$task['id']?>&action=update">Изменить</a>
                                <a href="?id=<?=$task['id']?>&action=complete">Выполнить</a>
                                <a href="?id=<?=$task['id']?>&action=delete">Удалить</a>
                            </td>
                            <td>Вы</td>
                            <td><?= $task['login']?></td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <a href="logout.php" class="btn btn-primary">Выйти</a>
                </div>
            </div>
        </div>
    </body>
</html>