<?php
session_start();
require_once 'connect.php';

// Проверяем, авторизован ли пользователь и является ли он администратором
if(!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    $_SESSION['error'] = "У вас нет прав для выполнения этого действия";
    echo "<script>window.location.href = '../../?p=home';</script>";
    exit();
}

// Проверяем, был ли отправлен GET-запрос с ID категории
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $categoryId = (int)$_GET['id'];
    
    // Проверяем, существует ли категория с таким ID
    $checkQuery = "SELECT * FROM categories WHERE id = :id";
    $checkStmt = $connect->prepare($checkQuery);
    $checkStmt->bindParam(':id', $categoryId);
    $checkStmt->execute();
    
    if($checkStmt->rowCount() == 0) {
        $_SESSION['error'] = "Категория не найдена";
        echo "<script>window.location.href = '../../?p=admin';</script>";
        exit();
    }
    
    // Удаляем категорию
    $deleteQuery = "DELETE FROM categories WHERE id = :id";
    $deleteStmt = $connect->prepare($deleteQuery);
    $deleteStmt->bindParam(':id', $categoryId);
    
    if($deleteStmt->execute()) {
        $_SESSION['success'] = "Категория успешно удалена";
    } else {
        $_SESSION['error'] = "Ошибка при удалении категории";
    }
}

// Перенаправляем обратно на страницу админа
echo "<script>window.location.href = '../../?p=admin';</script>";
exit();
?> 