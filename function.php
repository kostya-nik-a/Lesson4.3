<?php

session_start();

// Для того чтобы выводить все ошибки и предупреждения
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Перенаправление на другую страницу
function redirect($page) {
    header("Location: $page.php");
    die;
}

// Проверка, является ли пользователь просто аутоидентифицированным
function isAuthorized() {
    return !empty($_SESSION['user']);
}
