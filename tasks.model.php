<?php

require_once "db.connect.php";

function getUserTasks($user_id, $sortOfType) {
    $db = connectDB();

    if (isset($sortOfType)) {
        switch ($sortOfType) {
            case 'date_created':
                $sortOfType = 'date_added';
            break;
            case 'status':
                $sortOfType = 'is_done';
            break;
            case 'description':
                $sortOfType = 'description';
            break;
        }

        $query = $db->prepare("SELECT a.*, b.login, c.login AS assignee FROM task AS a 
                LEFT JOIN user AS b ON (a.user_id = b.id) 
                LEFT JOIN user AS c ON (a.assigned_user_id = c.id) 
                WHERE a.user_id = :user_id
                ORDER BY a.".$sortOfType." ASC");
        $query->bindParam(':user_id', $user_id, PDO::FETCH_ASSOC);
        $query->execute();
        return $query->fetchAll();
    }
    else {
        $query = $db->prepare("SELECT a.*, b.login, c.login AS assignee FROM task AS a 
        LEFT JOIN user AS b ON (a.user_id = b.id) 
        LEFT JOIN user AS c ON (a.assigned_user_id = c.id) 
        WHERE a.user_id = :user_id");
        $query->bindParam(':user_id', $user_id, PDO::FETCH_ASSOC);
        $query->execute();
        return $query->fetchAll();
    }
}

function getAssignedTasks($user_id) {
    $db = connectDB();
    $query = $db->prepare("SELECT a.*, b.login FROM task AS a LEFT JOIN user AS b ON (a.user_id = b.id) WHERE a.assigned_user_id = :user_id");
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll();
}

function getTaskById($id) {
    $db = connectDB();
    $query = $db->prepare("SELECT id, description FROM task WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    if ($task = $query->fetch(PDO::FETCH_ASSOC)) {
        return $task;
    }
    return false;
}

function addTask($params = []) {
    $db = connectDB();
    $query = $db->prepare("INSERT INTO `task` (user_id, description) VALUES (:user_id, :description)");
    $query->bindParam(':user_id', $params['user_id'], PDO::PARAM_INT);
    $query->bindParam(':description', $params['description'], PDO::PARAM_STR);
    $query->execute();
}

function updateTask($params = []) {
    $db = connectDB();
    $query = $db->prepare("UPDATE `task` SET `description` = :description WHERE `id` = :id");
    $query->execute([':id' => $params['id'], ':description' => $params['description']]);
    redirect('tasks');
}

function assignTask($params = []) {
    $db = connectDB();
    $query = $db->prepare("UPDATE `task` SET `assigned_user_id` = :assignee WHERE `id` = :task_id");
    $query->execute([':assignee' => $params['assignee'], ':task_id' => $params['task_id']]);
}

function editTask($action, $param) {
    $db = connectDB();
    switch ($action) {
        case 'complete':
            $query = $db->prepare("UPDATE `task` SET `is_done` = '1' WHERE `id` = :id");
            $query->bindParam(':id', $param, PDO::PARAM_INT);
            $query->execute();
        break;
        case 'delete':
            $query = $db->prepare("DELETE FROM `task` WHERE `id` = :id");
            $query->bindParam(':id', $param, PDO::PARAM_INT);
            $query->execute();
        break;
    }
}
