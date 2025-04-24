<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кликон - Главная</title>
    <link rel="stylesheet" href="assets/media/fonts/Qanelas/stylesheet.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/media/images/favicon.svg" type="image/x-icon">
</head>

<body>
    <?php include 'incl/pages/header.php'; ?>

    <?php
    if (isset($_GET['p'])) {
        if ($_GET['p'] == 'home') {
            include 'incl/pages/home.php';
        } elseif ($_GET['p'] == 'catalog') {
            include 'incl/pages/catalog.php';
        } elseif ($_GET['p'] == 'contacts') {
            include 'incl/pages/contacts.php';
        } elseif ($_GET['p'] == 'profile') {
            include 'incl/pages/profile.php';
        } elseif ($_GET['p'] == 'subscribe') {
            include 'incl/pages/subscribe.php';
        } elseif ($_GET['p'] == 'admin') {
            include 'incl/pages/admin.php';
        } elseif ($_GET['p'] == 'film-page') {
            include 'incl/pages/film-page.php';
        } else {
            include 'incl/pages/404.php';
        }
    } else {
        include 'incl/pages/home.php';
    }
    ?>

    <?php include 'incl/pages/footer.php'; ?>
</body>

</html>

<style>
    ::-webkit-scrollbar {
        width: 0;
    }
</style>