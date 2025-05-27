<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Для отмены подписки необходимо авторизоваться";
    header('Location: ../../?p=subscribe');
    exit();
}

try {
    // Обновляем статус последней одобренной подписки на rejected
    $updateQuery = "UPDATE subscription_requests 
                   SET status = 'rejected' 
                   WHERE user_id = :user_id 
                   AND status = 'approved' 
                   ORDER BY created_at DESC 
                   LIMIT 1";
    $updateStmt = $connect->prepare($updateQuery);
    $updateStmt->bindParam(':user_id', $_SESSION['user_id']);
    $updateStmt->execute();

    // Обновляем статус подписки пользователя
    $updateUserQuery = "UPDATE users SET is_subscribed = 0 WHERE id = :user_id";
    $updateUserStmt = $connect->prepare($updateUserQuery);
    $updateUserStmt->bindParam(':user_id', $_SESSION['user_id']);
    $updateUserStmt->execute();

    $_SESSION['success'] = "Ваша подписка успешно отменена";
} catch (PDOException $e) {
    $_SESSION['error'] = "Произошла ошибка при отмене подписки";
}

header('Location: ../../?p=subscribe');
exit();
?> 