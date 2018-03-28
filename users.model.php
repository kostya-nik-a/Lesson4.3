<?php

require_once "db.connect.php";

function getAllUsers($id) {
    $db = connectDB();
    $users = $db->query("SELECT id, login FROM user WHERE id <> $id");
    if (!$users) {
        return [];
    }
    return $users;
}

function getUserById($id) {
    $db = connectDB();
    $query = $db->prepare("SELECT id, login FROM user WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    if ($user = $query->fetch(PDO::FETCH_ASSOC)) {
        return $user;
    }
    return false;
}

function getUserByLogin($login) {
    $db = connectDB();
    $sql = "SELECT id, login, hash_password FROM user WHERE `login` = :login";
    $query = $db->prepare($sql);
    $query->bindParam(':login', $login, PDO::PARAM_STR);
    $query->execute();
    if ($user = $query->fetch(PDO::FETCH_ASSOC)) {
        return $user;
    }
    return false;
}

function createUser($params = []) {
    $db = connectDB();
    $sql = "INSERT INTO `user` (login, password, hash_password) VALUES (:login, :password, :hash_password)";
    $hash_password = password_hash($params['password'], PASSWORD_DEFAULT);

    $query = $db->prepare($sql);
    $query->bindParam(':login', $params['login'], PDO::PARAM_STR);
    $query->bindParam(':password', $params['password'], PDO::PARAM_STR);
    $query->bindParam(':hash_password', $hash_password, PDO::PARAM_STR);
    return $query->execute();
}