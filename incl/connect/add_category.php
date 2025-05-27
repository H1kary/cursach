<?php
session_start();
require_once 'connect.php';

// Проверяем, авторизован ли пользователь и является ли он администратором
if(!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    $_SESSION['error'] = "У вас нет прав для выполнения этого действия";
    echo "<script>window.location.href = '../../?p=home';</script>";
    exit();
}

// Проверяем, был ли отправлен POST-запрос
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_name'])) {
    $categoryName = trim($_POST['category_name']);
    
    // Проверяем, что название категории не пустое
    if(empty($categoryName)) {
        $_SESSION['error'] = "Название категории не может быть пустым";
        echo "<script>window.location.href = '../../?p=admin';</script>";
        exit();
    }
    
    // Проверяем, существует ли уже такая категория
    $checkQuery = "SELECT * FROM categories WHERE name = :name";
    $checkStmt = $connect->prepare($checkQuery);
    $checkStmt->bindParam(':name', $categoryName);
    $checkStmt->execute();
    
    if($checkStmt->rowCount() > 0) {
        $_SESSION['error'] = "Категория с таким названием уже существует";
        echo "<script>window.location.href = '../../?p=admin';</script>";
        exit();
    }
    
    // Добавляем новую категорию
    $insertQuery = "INSERT INTO categories (name) VALUES (:name)";
    $insertStmt = $connect->prepare($insertQuery);
    $insertStmt->bindParam(':name', $categoryName);
    
    if($insertStmt->execute()) {
        $_SESSION['success'] = "Категория успешно добавлена";
    } else {
        $_SESSION['error'] = "Ошибка при добавлении категории";
    }
}

// Перенаправляем обратно на страницу админа
echo "<script>window.location.href = '../../?p=admin';</script>";
exit();
?> 