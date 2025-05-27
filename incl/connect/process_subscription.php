<?php
session_start();
require_once 'connect.php';

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Необходимо авторизоваться для оформления подписки";
    header("Location: ../../?p=home");
    exit();
}

// Проверяем метод запроса
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Получаем данные из формы
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $card_number = isset($_POST['card_number']) ? trim($_POST['card_number']) : '';
    $card_holder = isset($_POST['card_holder']) ? trim($_POST['card_holder']) : '';
    $cvv = isset($_POST['cvv']) ? trim($_POST['cvv']) : '';
    
    // Валидация полей (упрощенный вариант)
    if (empty($name) || empty($email) || empty($phone) || empty($card_number) || empty($card_holder) || empty($cvv)) {
        $_SESSION['error'] = "Все поля должны быть заполнены";
        header("Location: ../../?p=subscribe");
        exit();
    }
    
    // Валидация номера карты (пример: 16 цифр)
    $card_number = preg_replace('/\D/', '', $card_number); // Удаляем все нецифровые символы
    if (strlen($card_number) != 16) {
        $_SESSION['error'] = "Номер карты должен содержать 16 цифр";
        header("Location: ../../?p=subscribe");
        exit();
    }
    
    // Валидация CVV (3 цифры)
    if (!preg_match('/^\d{3}$/', $cvv)) {
        $_SESSION['error'] = "CVV должен содержать 3 цифры";
        header("Location: ../../?p=subscribe");
        exit();
    }
    
    try {
        // В реальном проекте здесь должна быть интеграция с платежной системой
        // Для демонстрации мы просто обновляем статус пользователя и добавляем запись о подписке
        
        // Начинаем транзакцию
        $connect->beginTransaction();
        
        // Обновляем статус подписки пользователя
        $updateUserQuery = "UPDATE users SET is_subscribed = 1 WHERE id = :user_id";
        $updateUserStmt = $connect->prepare($updateUserQuery);
        $updateUserStmt->bindParam(':user_id', $_SESSION['user_id']);
        $updateUserStmt->execute();
        
        // Устанавливаем дату окончания подписки (1 месяц от текущей даты)
        $endDate = date('Y-m-d H:i:s', strtotime('+1 month'));
        
        // Сохраняем информацию о платеже (в реальном проекте нужно шифровать или хранить токен)
        $paymentInfo = json_encode([
            'last_digits' => substr($card_number, -4),
            'holder' => $card_holder,
            'payment_date' => date('Y-m-d H:i:s')
        ]);
        
        // Добавляем запись о подписке
        $insertSubscriptionQuery = "INSERT INTO subscriptions (user_id, end_date, payment_info) VALUES (:user_id, :end_date, :payment_info)";
        $insertSubscriptionStmt = $connect->prepare($insertSubscriptionQuery);
        $insertSubscriptionStmt->bindParam(':user_id', $_SESSION['user_id']);
        $insertSubscriptionStmt->bindParam(':end_date', $endDate);
        $insertSubscriptionStmt->bindParam(':payment_info', $paymentInfo);
        $insertSubscriptionStmt->execute();
        
        // Завершаем транзакцию
        $connect->commit();
        
        // Обновляем информацию в сессии
        $_SESSION['is_subscribed'] = 1;
        
        // Перенаправляем на страницу подписки с сообщением об успехе
        $_SESSION['success'] = "Подписка успешно оформлена! Спасибо за поддержку.";
        header("Location: ../../?p=subscribe");
        exit();
        
    } catch (Exception $e) {
        // В случае ошибки отменяем транзакцию
        $connect->rollBack();
        
        // Записываем ошибку и перенаправляем пользователя
        error_log("Ошибка при оформлении подписки: " . $e->getMessage());
        $_SESSION['error'] = "Произошла ошибка при оформлении подписки. Пожалуйста, попробуйте позже.";
        header("Location: ../../?p=subscribe");
        exit();
    }
    
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'cancel') {
    
    // Обработка отмены подписки
    try {
        // Начинаем транзакцию
        $connect->beginTransaction();
        
        // Обновляем статус подписки пользователя
        $updateUserQuery = "UPDATE users SET is_subscribed = 0 WHERE id = :user_id";
        $updateUserStmt = $connect->prepare($updateUserQuery);
        $updateUserStmt->bindParam(':user_id', $_SESSION['user_id']);
        $updateUserStmt->execute();
        
        // Обновляем статус текущей активной подписки и устанавливаем дату окончания на текущую дату
        $currentDate = date('Y-m-d H:i:s');
        $updateSubscriptionQuery = "UPDATE subscriptions SET status = 'cancelled', end_date = :current_date WHERE user_id = :user_id AND status = 'active'";
        $updateSubscriptionStmt = $connect->prepare($updateSubscriptionQuery);
        $updateSubscriptionStmt->bindParam(':user_id', $_SESSION['user_id']);
        $updateSubscriptionStmt->bindParam(':current_date', $currentDate);
        $updateSubscriptionStmt->execute();
        
        // Завершаем транзакцию
        $connect->commit();
        
        // Обновляем информацию в сессии
        $_SESSION['is_subscribed'] = 0;
        
        // Перенаправляем на страницу подписки с сообщением об успехе
        $_SESSION['success'] = "Подписка успешно отменена. Доступ к премиум-контенту прекращен.";
        header("Location: ../../?p=subscribe");
        exit();
        
    } catch (Exception $e) {
        // В случае ошибки отменяем транзакцию
        $connect->rollBack();
        
        // Записываем ошибку и перенаправляем пользователя
        error_log("Ошибка при отмене подписки: " . $e->getMessage());
        $_SESSION['error'] = "Произошла ошибка при отмене подписки. Пожалуйста, попробуйте позже.";
        header("Location: ../../?p=subscribe");
        exit();
    }
    
} else {
    // Некорректный запрос
    $_SESSION['error'] = "Некорректный запрос";
    header("Location: ../../?p=home");
    exit();
} 