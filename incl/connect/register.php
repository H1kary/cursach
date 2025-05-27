<?php
session_start();
require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $name = trim($_POST['name']);
    $password = $_POST['password'];
    $password_check = $_POST['password_check'];
    
    // Проверка наличия всех полей
    if (empty($email) || empty($name) || empty($password) || empty($password_check)) {
        $_SESSION['error'] = "Все поля должны быть заполнены";
        echo "<script>window.location.href = '../../?p=home';</script>";
        exit();
    }
    
    // Проверка длины имени (минимум 3 символа)
    if (strlen($name) < 3) {
        $_SESSION['error'] = "Имя должно содержать минимум 3 символа";
        echo "<script>window.location.href = '../../?p=home';</script>";
        exit();
    }
    
    // Проверка пароля (английский язык, минимум одна заглавная буква и одна цифра)
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,}$/', $password)) {
        $_SESSION['error'] = "Пароль должен содержать минимум 6 символов, только английские буквы, минимум одну заглавную букву и одну цифру";
        echo "<script>window.location.href = '../../?p=home';</script>";
        exit();
    }
    
    // Проверка совпадения паролей
    if ($password !== $password_check) {
        $_SESSION['error'] = "Пароли не совпадают";
        echo "<script>window.location.href = '../../?p=home';</script>";
        exit();
    }
    
    // Проверка наличия пользователя с таким email
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $connect->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        // Пользователь с таким email уже существует
        $_SESSION['error'] = "Пользователь с таким email уже зарегистрирован";
        echo "<script>window.location.href = '../../?p=home';</script>";
        exit();
    } else {
        // Хеширование пароля
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Добавление пользователя в базу данных с аватаркой по умолчанию
        $query = "INSERT INTO users (email, name, password, avatar, is_admin) VALUES (:email, :name, :password, 'default.jpg', 0)";
        $stmt = $connect->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $password_hash);
        
        if ($stmt->execute()) {
            // Получение ID нового пользователя
            $user_id = $connect->lastInsertId();
            
            // Авторизация пользователя после регистрации
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['is_admin'] = 0;
            $_SESSION['is_subscribed'] = 0;
            $_SESSION['user_avatar'] = 'default.jpg';
            
            echo "<script>window.location.href = '../../?p=home';</script>";
            exit();
        } else {
            $_SESSION['error'] = "Ошибка при регистрации. Попробуйте позже.";
            echo "<script>window.location.href = '../../?p=home';</script>";
            exit();
        }
    }
} else {
    // Неверный метод запроса
    echo "<script>window.location.href = '../../?p=home';</script>";
    exit();
}
?> 