<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Для оформления подписки необходимо авторизоваться";
    header('Location: ../../?p=subscribe');
    exit();
}

try {
    // Логируем входящие данные
    error_log("Попытка создания запроса на подписку. User ID: " . $_SESSION['user_id']);
    error_log("POST данные: " . print_r($_POST, true));

    // Проверяем, нет ли уже активного запроса
    $checkQuery = "SELECT * FROM subscription_requests 
                  WHERE user_id = :user_id AND status = 'pending'";
    $checkStmt = $connect->prepare($checkQuery);
    $checkStmt->bindParam(':user_id', $_SESSION['user_id']);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        $_SESSION['error'] = "У вас уже есть активный запрос на подписку";
        header('Location: ../../?p=subscribe');
        exit();
    }

    // Проверяем обязательные поля
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || 
        empty($_POST['card_number']) || empty($_POST['card_holder']) || empty($_POST['cvv'])) {
        $_SESSION['error'] = "Пожалуйста, заполните все поля формы";
        header('Location: ../../?p=subscribe');
        exit();
    }

    // Очищаем номер карты от пробелов
    $card_number = str_replace(' ', '', $_POST['card_number']);

    // Проверяем длину номера карты
    if (strlen($card_number) < 16 || strlen($card_number) > 19) {
        $_SESSION['error'] = "Неверный формат номера карты";
        header('Location: ../../?p=subscribe');
        exit();
    }

    // Проверяем формат email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Неверный формат email";
        header('Location: ../../?p=subscribe');
        exit();
    }

    // Проверяем формат телефона
    if (!preg_match('/^\+?[0-9]{10,15}$/', str_replace([' ', '(', ')', '-'], '', $_POST['phone']))) {
        $_SESSION['error'] = "Неверный формат номера телефона";
        header('Location: ../../?p=subscribe');
        exit();
    }

    // Создаем новый запрос
    $insertQuery = "INSERT INTO subscription_requests (user_id, name, email, phone, card_number, card_holder) 
                   VALUES (:user_id, :name, :email, :phone, :card_number, :card_holder)";
    
    error_log("SQL запрос: " . $insertQuery);
    
    $insertStmt = $connect->prepare($insertQuery);
    
    if (!$insertStmt) {
        throw new PDOException("Ошибка подготовки запроса: " . print_r($connect->errorInfo(), true));
    }

    $insertStmt->bindParam(':user_id', $_SESSION['user_id']);
    $insertStmt->bindParam(':name', $_POST['name']);
    $insertStmt->bindParam(':email', $_POST['email']);
    $insertStmt->bindParam(':phone', $_POST['phone']);
    $insertStmt->bindParam(':card_number', $card_number);
    $insertStmt->bindParam(':card_holder', $_POST['card_holder']);

    error_log("Параметры запроса: " . print_r([
        'user_id' => $_SESSION['user_id'],
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'card_number' => $card_number,
        'card_holder' => $_POST['card_holder']
    ], true));

    if (!$insertStmt->execute()) {
        throw new PDOException("Ошибка выполнения запроса: " . print_r($insertStmt->errorInfo(), true));
    }

    $_SESSION['success'] = "Ваш запрос на подписку успешно отправлен. Ожидайте подтверждения от администратора.";
    header('Location: ../../?p=subscribe');
    exit();

} catch (PDOException $e) {
    error_log("Ошибка при отправке запроса на подписку: " . $e->getMessage());
    error_log("Trace: " . $e->getTraceAsString());
    $_SESSION['error'] = "Произошла ошибка при отправке запроса. Пожалуйста, попробуйте позже.";
    header('Location: ../../?p=subscribe');
    exit();
}
?> 