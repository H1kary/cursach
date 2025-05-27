<?php
session_start();
require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Проверка наличия всех полей
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Все поля должны быть заполнены";
        echo "<script>window.location.href = '../../?p=home';</script>";
        exit();
    }

    // Проверка наличия пользователя с указанным email
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $connect->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Проверка пароля
        if (password_verify($password, $user['password'])) {
            // Авторизация успешна
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['is_admin'] = $user['is_admin'];
            $_SESSION['user_avatar'] = $user['avatar'];
            $_SESSION['is_subscribed'] = $user['is_subscribed'];
            
            // Перенаправление на главную страницу с помощью JavaScript
            echo "<script>window.location.href = '../../?p=home';</script>";
            exit();
        } else {
            // Неверный пароль
            $_SESSION['error'] = "Неверный пароль";
            echo "<script>window.location.href = '../../?p=home';</script>";
            exit();
        }
    } else {
        // Пользователь не найден
        $_SESSION['error'] = "Пользователь с таким email не найден";
        echo "<script>window.location.href = '../../?p=home';</script>";
        exit();
    }
} else {
    // Неверный метод запроса
    echo "<script>window.location.href = '../../?p=home';</script>";
    exit();
}
?> 