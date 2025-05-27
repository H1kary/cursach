<?php
// Проверяем, авторизован ли пользователь и является ли он администратором
if(!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    $_SESSION['error'] = "У вас нет прав для доступа к этой странице";
    echo "<script>window.location.href = '?p=403';</script>";
    exit();
}

// Подключаемся к базе данных
require_once 'incl/connect/connect.php';

// Получаем список всех категорий
$categoriesQuery = "SELECT * FROM categories ORDER BY name ASC";
$categoriesStmt = $connect->prepare($categoriesQuery);
$categoriesStmt->execute();
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

// После получения списка категорий добавляем получение списка фильмов
$filmsQuery = "SELECT f.*, c.name as category_name FROM films f 
               LEFT JOIN categories c ON f.category_id = c.id 
               ORDER BY f.created_at DESC";
$filmsStmt = $connect->prepare($filmsQuery);
$filmsStmt->execute();
$films = $filmsStmt->fetchAll(PDO::FETCH_ASSOC);

// Получаем список запросов на подписку
$subscriptionRequestsQuery = "SELECT sr.*, u.name, u.email 
                            FROM subscription_requests sr 
                            JOIN users u ON sr.user_id = u.id 
                            WHERE sr.status = 'pending' 
                            ORDER BY sr.created_at DESC";
$subscriptionRequestsStmt = $connect->prepare($subscriptionRequestsQuery);
$subscriptionRequestsStmt->execute();
$subscriptionRequests = $subscriptionRequestsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="admin">
    <div class="link-bar">
        <a href="?p=profile">
            <img src="./assets/media/images/profile/linkbar1.svg" alt="">
        </a>
        <a href="?p=admin">
            <img src="./assets/media/images/profile/linkbar3.svg" alt="">
        </a>
    </div>
    <div class="admin-page">
        <h4>Панель администратора</h4>
        <?php if(isset($_SESSION['success'])): ?>
        <div class="success-message">
            <?php 
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
        <?php endif; ?>
        <div class="requested-users">
            <h5>Запросы на подписку</h5>
            <div class="requested-users__items">
                <?php if(count($subscriptionRequests) > 0): ?>
                    <?php foreach($subscriptionRequests as $request): ?>
                    <div class="requested-users__item">
                        <div>
                            <h6><?php echo htmlspecialchars($request['name']); ?></h6>
                            <p><?php echo htmlspecialchars($request['email']); ?></p>
                            <p class="request-date">Запрос от: <?php echo date('d.m.Y H:i', strtotime($request['created_at'])); ?></p>
                        </div>
                        <form action="incl/connect/handle_subscription.php" method="POST" class="request-actions">
                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                            <button type="submit" name="action" value="approve" class="approve-btn">
                                <img src="./assets/media/images/admin/approve.svg" alt="Одобрить">
                            </button>
                            <button type="submit" name="action" value="reject" class="reject-btn">
                                <img src="./assets/media/images/admin/deny.svg" alt="Отклонить">
                            </button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-requests">Нет активных запросов на подписку</p>
                <?php endif; ?>
            </div>
        </div>


        
        <!-- <div class="all-users">
            <h5>Все пользователи</h5>
            <div class="all-users__items">
                <div class="all-users__item">
                    <img src="./assets/media/images/profile/profileimg.png" alt="">
                    <div>
                        <h6>Юрий Белов</h6>
                        <p>babylya03@yandex.ru</p>
                        <img src="./assets/media/images/admin/delete.svg" alt="">
                    </div>
                    <input type="text" list="role-datalist" id="role-select" name="role-select" placeholder="Пользователь">
                    <datalist id="role-datalist">
                        <option value="Пользователь">
                        <option value="Админ">
                    </datalist>
                </div>
                <div class="all-users__item">
                    <img src="./assets/media/images/profile/profileimg.png" alt="">
                    <div>
                        <h6>Юрий Белов</h6>
                        <p>babylya03@yandex.ru</p>
                        <img src="./assets/media/images/admin/delete.svg" alt="">
                    </div>
                    <input type="text" list="role-datalist" id="role-select" name="role-select" placeholder="Пользователь">
                    <datalist id="role-datalist">
                        <option value="Пользователь">
                        <option value="Админ">
                    </datalist>
                </div>
                <div class="all-users__item">
                    <img src="./assets/media/images/profile/profileimg.png" alt="">
                    <div>
                        <h6>Юрий Белов</h6>
                        <p>babylya03@yandex.ru</p>
                        <img src="./assets/media/images/admin/delete.svg" alt="">
                    </div>
                    <input type="text" list="role-datalist" id="role-select" name="role-select" placeholder="Пользователь">
                    <datalist id="role-datalist">
                        <option value="Пользователь">
                        <option value="Админ">
                    </datalist>
                </div>
            </div>
        </div> -->
        <div class="categories">
            <div>
                <h5>Категории</h5>
                <img src="./assets/media/images/admin/add.svg" alt="" id="add-category-btn">
            </div>
            <div class="categories__items">
                <?php if(count($categories) > 0): ?>
                    <?php foreach($categories as $category): ?>
                    <div class="categories__item">
                        <p><?php echo htmlspecialchars($category['name']); ?></p>
                        <a href="incl/connect/delete_category.php?id=<?php echo $category['id']; ?>">
                            <img src="./assets/media/images/admin/delete.svg" alt="Удалить">
                        </a>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-categories">Нет категорий</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="materials">
            <div>
                <h5>Фильмы</h5>
                <img src="./assets/media/images/admin/add.svg" alt="" id="add-film-btn">
            </div>
            <div class="materials__items">
                <?php if(count($films) > 0): ?>
                    <?php foreach($films as $film): ?>
                    <div class="materials__item">
                        <a href="?p=film-page&id=<?php echo $film['id']; ?>" class="film-link">
                            <img src="./assets/media/images/catalog/<?php echo htmlspecialchars($film['poster']); ?>" alt="<?php echo htmlspecialchars($film['title']); ?>">
                            <p><?php echo htmlspecialchars($film['title']); ?></p>
                        </a>
                        <a href="incl/connect/delete_film.php?id=<?php echo $film['id']; ?>" class="delete-film">
                            <img src="./assets/media/images/admin/delete.svg" alt="Удалить" class="delete-icon">
                        </a>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-films">Нет фильмов</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Модальное окно для добавления категории -->
<div class="modal-overlay" id="category-modal-overlay"></div>
<div class="category-modal" id="category-modal">
    <div>
        <form action="incl/connect/add_category.php" method="POST">
            <h4>Добавление категории</h4>
            <input type="text" name="category_name" id="category_name" placeholder="Название категории" required>
            <div>
                <input type="submit" value="Добавить">
                <a href="#" id="cancel-category">Отмена</a>
            </div>
        </form>
        <img id="close-category-modal" src="./assets/media/images/header/close.svg" alt="">
    </div>
</div>

<!-- Модальное окно для добавления фильма -->
<div class="modal-overlay" id="film-modal-overlay"></div>
<div class="film-modal" id="film-modal">
    <div>
        <form action="incl/connect/add_film.php" method="POST" enctype="multipart/form-data">
            <h4>Добавление фильма</h4>
            <div class="form-group">
                <label for="title">Название на русском*</label>
                <input type="text" name="title" id="title" placeholder="Введите название" required>
            </div>
            <div class="form-group">
                <label for="original_title">Оригинальное название*</label>
                <input type="text" name="original_title" id="original_title" placeholder="Введите оригинальное название" required>
            </div>
            <div class="form-group">
                <label for="description">Описание*</label>
                <textarea name="description" id="description" placeholder="Введите описание" required></textarea>
            </div>
            <div class="form-group">
                <label for="year">Год создания*</label>
                <input type="number" name="year" id="year" placeholder="Введите год" min="1900" max="2100" required>
            </div>
            <div class="form-group">
                <label for="country">Страна*</label>
                <input type="text" name="country" id="country" placeholder="Введите страну" required>
            </div>
            <div class="form-group">
                <label for="slogan">Слоган</label>
                <input type="text" name="slogan" id="slogan" placeholder="Введите слоган">
            </div>
            <div class="form-group">
                <label for="director">Режиссёр*</label>
                <input type="text" name="director" id="director" placeholder="Введите имя режиссёра" required>
            </div>
            <div class="form-group">
                <label for="category_id">Категория</label>
                <select name="category_id" id="category_id">
                    <option value="">Выберите категорию</option>
                    <?php foreach($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="poster">Постер</label>
                <input type="file" name="poster" id="poster" accept="image/*">
                <p class="form-hint">Рекомендуемый размер: 300x400 пикселей</p>
            </div>
            <div class="form-group">
                <label for="video_file">Видеофайл фильма</label>
                <input type="file" name="video_file" id="video_file" accept="video/mp4,video/webm,video/avi,video/quicktime,video/ogg">
                <p class="form-hint">Поддерживаются файлы формата MP4, WebM, AVI, MOV и OGG</p>
            </div>
            <div class="form-group">
                <label for="is_premium">Доступ к фильму</label>
                <select name="is_premium" id="is_premium">
                    <option value="0">Доступен всем пользователям</option>
                    <option value="1">Только для подписчиков</option>
                </select>
                <p class="form-hint">Укажите, кому будет доступен фильм</p>
            </div>
            <div class="form-buttons">
                <input type="submit" value="Добавить фильм">
                <a href="#" id="cancel-film">Отмена</a>
            </div>
        </form>
        <img id="close-film-modal" src="./assets/media/images/header/close.svg" alt="">
    </div>
</div>

<style>
    .admin {
        margin: 0 auto;
        margin-top: 22px;
        max-width: 1430px;
        display: flex;
        gap: 7px;
    }

    .link-bar {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .link-bar>a {
        width: 107.411px;
        height: 104.08px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 10px;
        background: #191E2E;
    }

    .link-bar>a:hover {
        background: #3657CB;
    }

    .admin-page {
        padding-top: 30px;
        padding-bottom: 100px;
        padding-left: 50px;
        width: 1314px;
        background-color: #191E2E;
        border-radius: 10px;
    }

    .admin-page>h4 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 30px;
        font-style: normal;
        font-weight: 900;
        line-height: normal;
    }

    .no-requests{
        color: #fff;
    }

    .success-message {
        max-width: 1200px;
        margin: 10px 0;
        padding: 10px;
        background-color: rgba(0, 255, 0, 0.2);
        border-radius: 5px;
        color: #fff;
        text-align: center;
        font-family: 'Qanelas';
        font-weight: 600;
    }

    .requested-users {
        margin-top: 20px;
    }

    .requested-users>h5 {
        color: rgba(227, 230, 240, 0.72);
        font-family: Qanelas;
        font-size: 25px;
        font-style: normal;
        font-weight: 700;
        line-height: 166.5%;
        /* 41.625px */
    }

    .requested-users__items {
        margin-top: 20px;
        display: flex;
        gap: 32px;
        flex-wrap: wrap;
    }

    .requested-users__item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 587px;
        height: 67px;
        background: #1E2538;
        border-radius: 10px;
        padding: 10px;
    }

    .requested-users__item>img {
        width: 67px;
        height: 67px;
        border-radius: 10px;
    }

    
    /* Модальное окно для добавления категории */
    .category-modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translateX(-50%) translateY(-50%);
        z-index: 999;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .category-modal.active {
        opacity: 1;
    }

    .category-modal>div {
        position: relative;
        padding: 50px;
        border-radius: 10px;
        background: #191E2E;
    }

    #close-category-modal {
        position: absolute;
        top: 20px;
        right: 20px;
        cursor: pointer;
    }

    .category-modal form {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: 400px;
    }

    .category-modal form h4 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 24px;
        font-style: normal;
        font-weight: 900;
        line-height: normal;
        text-align: center;
    }

    .category-modal form input[type="text"] {
        width: 100%;
        height: 54px;
        padding-left: 26px;
        border-radius: 10px;
        background: #1E2538;
        color: rgba(255, 255, 255, 0.60);
        font-family: Qanelas;
        font-size: 17px;
        font-style: normal;
        font-weight: 400;
        line-height: 166.5%;
        border: none;
    }

    .category-modal form>div {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .category-modal form>div>input[type="submit"] {
        border-radius: 10px;
        background: #3657CB;
        padding: 18px 40px;
        border: none;
        color: #FFF;
        font-family: Qanelas;
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
        cursor: pointer;
    }

    .category-modal form>div>a {
        color: #E0E0E0;
        font-family: Qanelas;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        cursor: pointer;
    }
    
    .no-categories {
        color: rgba(255, 255, 255, 0.5);
        font-family: Qanelas;
        font-size: 16px;
        font-style: italic;
        padding: 10px 0;
    }
    
    .categories__item img {
        cursor: pointer;
    }

    /* Модальное окно и наложение */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 998;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal-overlay.active {
        opacity: 1;
    }
    
    body.lock {
        overflow: hidden;
    }

    /* Стили для категорий */
    .categories {
        margin-top: 40px;
    }

    .categories>div {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .categories>div>h5 {
        color: rgba(227, 230, 240, 0.72);
        font-family: Qanelas;
        font-size: 25px;
        font-style: normal;
        font-weight: 700;
        line-height: 166.5%;
    }
    
    #add-category-btn {
        cursor: pointer;
    }

    .categories__items {
        margin-top: 20px;
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .categories__item {
        padding: 20px 28px;
        display: flex;
        align-items: center;
        gap: 8px;
        border-radius: 10px;
        background: #1E2538;
    }

    .categories__item p {
        color: #FFF;
        font-family: Qanelas;
        font-size: 17px;
        font-style: normal;
        font-weight: 400;
        line-height: 166.5%;
    }
    
    /* Возвращаем стили для запрошенных пользователей */
    .requested-users__item>div:first-of-type h6 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 17px;
        font-style: normal;
        font-weight: 400;
        line-height: 166.5%;
    }

    .requested-users__item>div:first-of-type p {
        color: #F2F60F;
        font-family: Qanelas;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .requested-users__item>div:nth-of-type(2) {
        width: 67px;
        height: 67px;
        border-radius: 10px;
        background: #3657CB;
        box-shadow: 0px 0px 10px 0px rgba(54, 87, 203, 0.40);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* .requested-users__item>div:last-of-type {
        width: 67px;
        height: 67px;
        border-radius: 10px;
        background: #CB3F36;
        box-shadow: 0px 0px 10px 0px rgba(203, 63, 54, 0.40);
        display: flex;
        justify-content: center;
        align-items: center;
    } */

    .approve-btn{
        background: green;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .reject-btn{
        background: red;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    
    /* Стили для всех пользователей */
    .all-users {
        margin-top: 40px;
    }

    .all-users>h5 {
        color: rgba(227, 230, 240, 0.72);
        font-family: Qanelas;
        font-size: 25px;
        font-style: normal;
        font-weight: 700;
        line-height: 166.5%;
    }

    .all-users__items {
        margin-top: 20px;
        display: flex;
        gap: 32px;
        flex-wrap: wrap;
    }

    .all-users__item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 587px;
        height: 67px;
    }

    .all-users__item>img {
        width: 67px;
        height: 67px;
        border-radius: 10px;
    }

    .all-users__item>div:first-of-type {
        padding-top: 10px;
        padding-bottom: 10px;
        padding-left: 20px;
        width: 342px;
        height: 67px;
        border-radius: 10px;
        background: #1E2538;
        position: relative;
    }

    .all-users__item>div:first-of-type h6 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 17px;
        font-style: normal;
        font-weight: 400;
        line-height: 166.5%;
    }

    .all-users__item>div:first-of-type p {
        color: #F2F60F;
        font-family: Qanelas;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .all-users__item>div:first-of-type img {
        position: absolute;
        top: 8px;
        right: 8px;
        cursor: pointer;
    }

    .all-users__item>input {
        width: 162px;
        height: 67px;
        border-radius: 10px;
        background: #1E2538;
        border: 0;
        color: #FFF;
        font-family: Qanelas;
        font-size: 17px;
        font-style: normal;
        font-weight: 500;
        line-height: 166.5%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding-left: 28px;
    }
    
    /* Стили для материалов */
    .materials {
        margin-top: 40px;
    }

    .materials>div {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .materials>div>h5 {
        color: rgba(227, 230, 240, 0.72);
        font-family: Qanelas;
        font-size: 25px;
        font-style: normal;
        font-weight: 700;
        line-height: 166.5%;
    }

    .materials__items {
        margin-top: 20px;
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .materials__item {
        display: flex;
        flex-direction: column;
        gap: 10px;
        position: relative;
        max-width: 200px;
    }
    
    .materials__item .film-link {
        display: flex;
        flex-direction: column;
        text-decoration: none;
        transition: transform 0.3s ease;
    }
    
    .materials__item .film-link:hover {
        transform: scale(1.05);
    }

    .materials__item img:first-child {
        width: 100%;
        height: auto;
        border-radius: 8px;
        object-fit: cover;
        aspect-ratio: 2/3;
    }

    .materials__item p {
        color: #FFF;
        font-family: Qanelas;
        font-size: 15px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
        margin: 5px 0;
        text-align: center;
    }
    
    .materials__item .delete-film {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
    }
    
    .materials__item .delete-icon {
        width: 24px;
        height: 24px;
        background: rgba(0, 0, 0, 0.6);
        border-radius: 5px;
        padding: 5px;
        cursor: pointer;
    }

    /* Модальное окно и наложение */
    .film-modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translateX(-50%) translateY(-50%);
        z-index: 999;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .film-modal.active {
        opacity: 1;
    }

    .film-modal>div {
        position: relative;
        padding: 30px;
        border-radius: 10px;
        background: #191E2E;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
    }

    #close-film-modal {
        position: absolute;
        top: 20px;
        right: 20px;
        cursor: pointer;
    }

    .film-modal form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .film-modal form h4 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 24px;
        font-style: normal;
        font-weight: 900;
        line-height: normal;
        text-align: center;
        margin-bottom: 10px;
    }

    .film-modal .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .film-modal .form-group label {
        color: #FFF;
        font-family: Qanelas;
        font-size: 16px;
        font-weight: 600;
    }

    .film-modal .form-group input[type="text"],
    .film-modal .form-group input[type="number"],
    .film-modal .form-group select,
    .film-modal .form-group textarea {
        width: 100%;
        padding: 10px 15px;
        border-radius: 10px;
        background: #1E2538;
        color: rgba(255, 255, 255, 0.60);
        font-family: Qanelas;
        font-size: 15px;
        border: none;
    }

    .film-modal .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }

    .film-modal .form-hint {
        color: rgba(255, 255, 255, 0.5);
        font-size: 12px;
        margin-top: 5px;
    }

    .film-modal .form-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
    }

    .film-modal .form-buttons input[type="submit"] {
        border-radius: 10px;
        background: #3657CB;
        padding: 15px 30px;
        border: none;
        color: #FFF;
        font-family: Qanelas;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
    }

    .film-modal .form-buttons a {
        color: #E0E0E0;
        font-family: Qanelas;
        font-size: 16px;
        font-weight: 400;
        cursor: pointer;
    }
    
    /* Стили для фильмов */
    .no-films {
        color: rgba(255, 255, 255, 0.5);
        font-family: Qanelas;
        font-size: 16px;
        font-style: italic;
        padding: 10px 0;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Константы для модального окна категорий
        const modalOverlay = document.getElementById('category-modal-overlay');
        const categoryModal = document.getElementById('category-modal');
        const addCategoryBtn = document.getElementById('add-category-btn');
        const closeCategoryModal = document.getElementById('close-category-modal');
        const cancelCategory = document.getElementById('cancel-category');
        
        // Константы для модального окна фильмов
        const filmModalOverlay = document.getElementById('film-modal-overlay');
        const filmModal = document.getElementById('film-modal');
        const addFilmBtn = document.getElementById('add-film-btn');
        const closeFilmModal = document.getElementById('close-film-modal');
        const cancelFilm = document.getElementById('cancel-film');
        
        // Функция открытия модального окна
        function openModal(modal, overlay) {
            overlay.style.display = 'block';
            modal.style.display = 'block';
            document.body.classList.add('lock');

            // Задержка для анимации
            setTimeout(() => {
                overlay.classList.add('active');
                modal.classList.add('active');
            }, 10);
        }

        // Функция закрытия модального окна
        function closeModal(modal, overlay) {
            overlay.classList.remove('active');
            modal.classList.remove('active');

            // Задержка для анимации
            setTimeout(() => {
                overlay.style.display = 'none';
                modal.style.display = 'none';
                document.body.classList.remove('lock');
            }, 300);
        }
        
        // Модальное окно категорий
        // Открытие модального окна при клике на кнопку добавления категории
        addCategoryBtn.addEventListener('click', () => {
            openModal(categoryModal, modalOverlay);
        });
        
        // Закрытие модального окна при клике на крестик
        closeCategoryModal.addEventListener('click', () => {
            closeModal(categoryModal, modalOverlay);
        });
        
        // Закрытие модального окна при клике на кнопку "Отмена"
        cancelCategory.addEventListener('click', (e) => {
            e.preventDefault();
            closeModal(categoryModal, modalOverlay);
        });
        
        // Закрытие модального окна при клике вне его области
        modalOverlay.addEventListener('click', () => {
            closeModal(categoryModal, modalOverlay);
        });
        
        // Модальное окно фильмов
        // Открытие модального окна при клике на кнопку добавления фильма
        addFilmBtn.addEventListener('click', () => {
            openModal(filmModal, filmModalOverlay);
        });
        
        // Закрытие модального окна при клике на крестик
        closeFilmModal.addEventListener('click', () => {
            closeModal(filmModal, filmModalOverlay);
        });
        
        // Закрытие модального окна при клике на кнопку "Отмена"
        cancelFilm.addEventListener('click', (e) => {
            e.preventDefault();
            closeModal(filmModal, filmModalOverlay);
        });
        
        // Закрытие модального окна при клике вне его области
        filmModalOverlay.addEventListener('click', () => {
            closeModal(filmModal, filmModalOverlay);
        });
        
        // Добавляем подтверждение удаления фильма
        const deleteFilmLinks = document.querySelectorAll('.delete-film');
        deleteFilmLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if(!confirm('Вы действительно хотите удалить этот фильм?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>