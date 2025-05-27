<?php
session_start();
require_once 'connect.php';

// Проверяем, авторизован ли пользователь и является ли он администратором
if(!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    $_SESSION['error'] = "У вас нет прав для выполнения этого действия";
    echo "<script>window.location.href = '../../?p=home';</script>";
    exit();
}

// Проверяем, был ли отправлен GET-запрос с ID фильма
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $filmId = (int)$_GET['id'];
    
    // Проверяем, существует ли фильм с таким ID и получаем его постер
    $checkQuery = "SELECT * FROM films WHERE id = :id";
    $checkStmt = $connect->prepare($checkQuery);
    $checkStmt->bindParam(':id', $filmId);
    $checkStmt->execute();
    
    if($checkStmt->rowCount() == 0) {
        $_SESSION['error'] = "Фильм не найден";
        echo "<script>window.location.href = '../../?p=admin';</script>";
        exit();
    }
    
    $film = $checkStmt->fetch(PDO::FETCH_ASSOC);
    $posterFileName = $film['poster'];
    
    // Удаляем фильм из базы данных
    $deleteQuery = "DELETE FROM films WHERE id = :id";
    $deleteStmt = $connect->prepare($deleteQuery);
    $deleteStmt->bindParam(':id', $filmId);
    
    if($deleteStmt->execute()) {
        // Удаляем постер фильма, если это не дефолтный постер
        if($posterFileName != 'default-poster.jpg' && $posterFileName != 'catalog1.png' && $posterFileName != 'catalog2.png' && $posterFileName != 'catalog3.png') {
            $posterPath = '../../assets/media/images/catalog/' . $posterFileName;
            if(file_exists($posterPath)) {
                unlink($posterPath);
            }
        }
        
        $_SESSION['success'] = "Фильм успешно удален";
    } else {
        $_SESSION['error'] = "Ошибка при удалении фильма";
    }
}

// Перенаправляем обратно на страницу админа
echo "<script>window.location.href = '../../?p=admin';</script>";
exit();
?> 