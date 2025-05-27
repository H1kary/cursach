<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    $_SESSION['error'] = "У вас нет прав для выполнения этого действия";
    header('Location: ../../?p=admin');
    exit();
}

if (!isset($_POST['request_id']) || !isset($_POST['action'])) {
    $_SESSION['error'] = "Неверные параметры запроса";
    header('Location: ../../?p=admin');
    exit();
}

$requestId = $_POST['request_id'];
$action = $_POST['action'];

try {
    if ($action === 'approve') {
        // Получаем информацию о запросе
        $getRequestQuery = "SELECT user_id FROM subscription_requests WHERE id = :request_id";
        $getRequestStmt = $connect->prepare($getRequestQuery);
        $getRequestStmt->bindParam(':request_id', $requestId);
        $getRequestStmt->execute();
        $request = $getRequestStmt->fetch(PDO::FETCH_ASSOC);

        // Обновляем статус запроса
        $updateRequestQuery = "UPDATE subscription_requests SET status = 'approved' WHERE id = :request_id";
        $updateRequestStmt = $connect->prepare($updateRequestQuery);
        $updateRequestStmt->bindParam(':request_id', $requestId);
        $updateRequestStmt->execute();

        // Обновляем статус подписки пользователя
        $updateUserQuery = "UPDATE users SET is_subscribed = 1 WHERE id = :user_id";
        $updateUserStmt = $connect->prepare($updateUserQuery);
        $updateUserStmt->bindParam(':user_id', $request['user_id']);
        $updateUserStmt->execute();

        $_SESSION['success'] = "Запрос на подписку одобрен";
    } elseif ($action === 'reject') {
        // Обновляем статус запроса
        $updateRequestQuery = "UPDATE subscription_requests SET status = 'rejected' WHERE id = :request_id";
        $updateRequestStmt = $connect->prepare($updateRequestQuery);
        $updateRequestStmt->bindParam(':request_id', $requestId);
        $updateRequestStmt->execute();

        $_SESSION['success'] = "Запрос на подписку отклонен";
    } else {
        $_SESSION['error'] = "Неверное действие";
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Произошла ошибка при обработке запроса";
}

header('Location: ../../?p=admin');
exit();
?> 