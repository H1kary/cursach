<?php
session_start();

// Удаляем все переменные сессии
$_SESSION = array();

// Если есть cookie сессии, удаляем их тоже
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Уничтожаем сессию
session_destroy();

// Перенаправляем на главную страницу с помощью JavaScript
echo "<script>window.location.href = '../../?p=home';</script>";
exit();
?> 