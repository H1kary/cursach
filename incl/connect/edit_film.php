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
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['film_id'])) {
    // Получаем данные из формы
    $filmId = (int)$_POST['film_id'];
    $title = trim($_POST['title']);
    $originalTitle = trim($_POST['original_title']);
    $description = trim($_POST['description']);
    $year = (int)$_POST['year'];
    $country = trim($_POST['country']);
    $slogan = trim($_POST['slogan']);
    $director = trim($_POST['director']);
    $categoryId = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? (int)$_POST['category_id'] : null;
    $isPremium = isset($_POST['is_premium']) ? (int)$_POST['is_premium'] : 0;
    
    // Проверяем, что все обязательные поля заполнены
    if(empty($title) || empty($originalTitle) || empty($description) || empty($year) || empty($country) || empty($director)) {
        $_SESSION['error'] = "Все обязательные поля должны быть заполнены";
        echo "<script>window.location.href = '../../?p=film-page&id=" . $filmId . "';</script>";
        exit();
    }
    
    // Проверяем, существует ли фильм с таким ID
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
    $videoFileName = $film['video_file'];
    
    // Проверяем и загружаем новый постер, если он есть
    if(isset($_FILES['poster']) && $_FILES['poster']['error'] === 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        if(in_array($_FILES['poster']['type'], $allowedTypes)) {
            // Генерируем уникальное имя файла
            $extension = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid('film_') . '.' . $extension;
            $uploadPath = '../../assets/media/images/catalog/' . $newFileName;
            
            // Перемещаем загруженный файл
            if(move_uploaded_file($_FILES['poster']['tmp_name'], $uploadPath)) {
                // Удаляем старый постер, если это не дефолтный постер или не постер другого фильма
                if($posterFileName != 'default-poster.jpg' && !in_array($posterFileName, ['catalog1.png', 'catalog2.png', 'catalog3.png'])) {
                    $oldPosterPath = '../../assets/media/images/catalog/' . $posterFileName;
                    if(file_exists($oldPosterPath)) {
                        unlink($oldPosterPath);
                    }
                }
                
                $posterFileName = $newFileName;
            } else {
                $_SESSION['error'] = "Ошибка при загрузке постера";
                echo "<script>window.location.href = '../../?p=film-page&id=" . $filmId . "';</script>";
                exit();
            }
        } else {
            $_SESSION['error'] = "Разрешены только изображения формата JPG, PNG и GIF";
            echo "<script>window.location.href = '../../?p=film-page&id=" . $filmId . "';</script>";
            exit();
        }
    }
    
    // Проверяем и загружаем новый видеофайл, если он есть
    if(isset($_FILES['video_file']) && $_FILES['video_file']['error'] === 0) {
        $allowedTypes = ['video/mp4', 'video/webm', 'video/avi', 'video/quicktime', 'video/ogg'];
        if(in_array($_FILES['video_file']['type'], $allowedTypes)) {
            // Создаем директорию для видео, если она не существует
            $uploadDir = '../../assets/media/videos/';
            if(!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Генерируем уникальное имя файла
            $extension = pathinfo($_FILES['video_file']['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid('video_') . '.' . $extension;
            $uploadPath = $uploadDir . $newFileName;
            
            // Перемещаем загруженный файл
            if(move_uploaded_file($_FILES['video_file']['tmp_name'], $uploadPath)) {
                // Если был предыдущий видеофайл, удаляем его
                if($videoFileName) {
                    $oldVideoPath = '../../assets/media/videos/' . $videoFileName;
                    if(file_exists($oldVideoPath)) {
                        unlink($oldVideoPath);
                    }
                }
                
                $videoFileName = $newFileName;
            } else {
                $_SESSION['error'] = "Ошибка при загрузке видеофайла";
                echo "<script>window.location.href = '../../?p=film-page&id=" . $filmId . "';</script>";
                exit();
            }
        } else {
            $_SESSION['error'] = "Разрешены только видеофайлы формата MP4, WebM, AVI, MOV и OGG";
            echo "<script>window.location.href = '../../?p=film-page&id=" . $filmId . "';</script>";
            exit();
        }
    }
    
    // Обновляем информацию о фильме в базе данных
    $query = "UPDATE films SET 
              title = :title, 
              original_title = :original_title, 
              description = :description, 
              year = :year, 
              country = :country, 
              slogan = :slogan, 
              director = :director, 
              poster = :poster, 
              video_file = :video_file,
              is_premium = :is_premium,
              category_id = :category_id 
              WHERE id = :id";
              
    $stmt = $connect->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':original_title', $originalTitle);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':country', $country);
    $stmt->bindParam(':slogan', $slogan);
    $stmt->bindParam(':director', $director);
    $stmt->bindParam(':poster', $posterFileName);
    $stmt->bindParam(':video_file', $videoFileName);
    $stmt->bindParam(':is_premium', $isPremium);
    $stmt->bindParam(':category_id', $categoryId);
    $stmt->bindParam(':id', $filmId);
    
    if($stmt->execute()) {
        $_SESSION['success'] = "Информация о фильме успешно обновлена";
        echo "<script>window.location.href = '../../?p=film-page&id=" . $filmId . "';</script>";
    } else {
        $_SESSION['error'] = "Ошибка при обновлении информации о фильме";
        echo "<script>window.location.href = '../../?p=film-page&id=" . $filmId . "';</script>";
    }
    
    exit();
}

// Если запрос не POST или не передан ID фильма, перенаправляем на главную
echo "<script>window.location.href = '../../?p=home';</script>";
exit(); 