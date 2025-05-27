<?php
session_start();

// Проверка роли администратора
if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    // Если это не страница профиля, админ-панели или страница фильма, перенаправляем на профиль
    if(!isset($_GET['p']) || ($_GET['p'] != 'profile' && $_GET['p'] != 'admin' && $_GET['p'] != 'film-page')) {
        header('Location: ?p=profile');
        exit();
    }
}

// Проверка прав доступа и авторизации
if (isset($_GET['p'])) {
    $page = $_GET['p'];
    
    // Проверка прав доступа для админ-панели
    if ($page === 'admin' && (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1)) {
        header('Location: ?p=403');
        exit();
    }
    
    // Проверка авторизации для защищенных страниц
    if (in_array($page, ['profile', 'subscribe', 'admin']) && !isset($_SESSION['user_id'])) {
        header('Location: ?p=login');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кликон - Главная</title>
    <link rel="stylesheet" href="assets/media/fonts/Qanelas/stylesheet.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/media/images/favicon.svg" type="image/x-icon">
    <script src="./incl/sr/ScrollReveal.js"></script>
</head>

<body>
    <?php include 'incl/pages/header.php'; ?>

    <?php
    if (isset($_GET['p'])) {
        $page = $_GET['p'];
        $page_file = "incl/pages/{$page}.php";
        if (file_exists($page_file)) {
            include $page_file;
        } else {
            include "incl/pages/404.php";
        }
    } else {
        include "incl/pages/main.php";
    }
    ?>

    <?php include 'incl/pages/footer.php'; ?>

    <!-- Кнопка прокрутки к верху страницы -->
    <div class="scroll-to-top" id="scrollToTop">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 4L4 12H9V20H15V12H20L12 4Z" fill="white" />
        </svg>
    </div>
</body>

</html>

<style>
    ::-webkit-scrollbar {
        width: 0;
    }

    /* Стили для кнопки прокрутки к верху */
    .scroll-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background-color: #3657CB;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        z-index: 999;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .scroll-to-top.visible {
        opacity: 1;
        visibility: visible;
    }

    .scroll-to-top:hover {
        background-color: #2a45a0;
        transform: translateY(-3px);
    }

    @media (max-width: 768px) {
        .scroll-to-top {
            width: 40px;
            height: 40px;
            bottom: 20px;
            right: 20px;
        }

        .scroll-to-top svg {
            width: 20px;
            height: 20px;
        }
    }
</style>

<script src="./incl/sr/sr.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Получаем кнопку прокрутки
        const scrollToTopBtn = document.getElementById('scrollToTop');

        // Функция для проверки положения прокрутки
        function checkScroll() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('visible');
            } else {
                scrollToTopBtn.classList.remove('visible');
            }
        }

        // Слушаем событие прокрутки страницы
        window.addEventListener('scroll', checkScroll);

        // Прокрутка к верху страницы при клике на кнопку
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Проверяем положение прокрутки при загрузке страницы
        checkScroll();
    });
</script>