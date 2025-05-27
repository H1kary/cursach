<?php
session_start();
require_once 'connect.php';

// Проверяем авторизацию
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Необходимо авторизоваться";
    header('Location: ../../index.php?p=login');
    exit();
}

// Получаем данные из формы
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Проверяем обязательные поля
if (empty($name) || empty($email)) {
    $_SESSION['error'] = "Имя и email обязательны для заполнения";
    header('Location: ../../index.php?p=profile');
    exit();
}

// Проверяем формат email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Неверный формат email";
    header('Location: ../../index.php?p=profile');
    exit();
}

try {
    // Проверяем, не занят ли email другим пользователем
    $checkEmailQuery = "SELECT id FROM users WHERE email = :email AND id != :user_id";
    $checkEmailStmt = $connect->prepare($checkEmailQuery);
    $checkEmailStmt->bindParam(':email', $email);
    $checkEmailStmt->bindParam(':user_id', $_SESSION['user_id']);
    $checkEmailStmt->execute();

    if ($checkEmailStmt->rowCount() > 0) {
        $_SESSION['error'] = "Этот email уже используется другим пользователем";
        header('Location: ../../index.php?p=profile');
        exit();
    }

    // Если пользователь хочет изменить пароль
    if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
        // Проверяем, что все поля для смены пароля заполнены
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $_SESSION['error'] = "Для смены пароля необходимо заполнить все поля";
            header('Location: ../../index.php?p=profile');
            exit();
        }

        // Проверяем совпадение нового пароля и подтверждения
        if ($new_password !== $confirm_password) {
            $_SESSION['error'] = "Новый пароль и подтверждение не совпадают";
            header('Location: ../../index.php?p=profile');
            exit();
        }

        // Проверяем текущий пароль
        $checkPasswordQuery = "SELECT password FROM users WHERE id = :user_id";
        $checkPasswordStmt = $connect->prepare($checkPasswordQuery);
        $checkPasswordStmt->bindParam(':user_id', $_SESSION['user_id']);
        $checkPasswordStmt->execute();
        $user = $checkPasswordStmt->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($current_password, $user['password'])) {
            $_SESSION['error'] = "Неверный текущий пароль";
            header('Location: ../../index.php?p=profile');
            exit();
        }

        // Хешируем новый пароль
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Обновляем данные пользователя с новым паролем
        $updateQuery = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :user_id";
        $updateStmt = $connect->prepare($updateQuery);
        $updateStmt->bindParam(':name', $name);
        $updateStmt->bindParam(':email', $email);
        $updateStmt->bindParam(':password', $hashed_password);
        $updateStmt->bindParam(':user_id', $_SESSION['user_id']);
    } else {
        // Обновляем только имя и email
        $updateQuery = "UPDATE users SET name = :name, email = :email WHERE id = :user_id";
        $updateStmt = $connect->prepare($updateQuery);
        $updateStmt->bindParam(':name', $name);
        $updateStmt->bindParam(':email', $email);
        $updateStmt->bindParam(':user_id', $_SESSION['user_id']);
    }

    if ($updateStmt->execute()) {
        // Обновляем данные в сессии
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        
        $_SESSION['success'] = "Профиль успешно обновлен";
    } else {
        $_SESSION['error'] = "Ошибка при обновлении профиля";
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Ошибка базы данных: " . $e->getMessage();
}

header('Location: ../../index.php?p=profile');
exit(); 