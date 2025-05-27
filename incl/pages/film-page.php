<?php
// Подключаемся к базе данных
require_once 'incl/connect/connect.php';

// Проверяем, передан ли ID фильма
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $filmId = (int)$_GET['id'];
    
    // Получаем информацию о фильме
    $filmQuery = "SELECT f.*, c.name as category_name FROM films f 
                 LEFT JOIN categories c ON f.category_id = c.id 
                 WHERE f.id = :id";
    $filmStmt = $connect->prepare($filmQuery);
    $filmStmt->bindParam(':id', $filmId);
    $filmStmt->execute();
    
    // Проверяем, существует ли фильм с таким ID
    if($filmStmt->rowCount() > 0) {
        $film = $filmStmt->fetch(PDO::FETCH_ASSOC);
        
        // Получаем список всех категорий для выпадающего списка при редактировании
        if(isset($_SESSION['user_id']) && $_SESSION['is_admin'] == 1) {
            $categoriesQuery = "SELECT * FROM categories ORDER BY name ASC";
            $categoriesStmt = $connect->prepare($categoriesQuery);
            $categoriesStmt->execute();
            $categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } else {
        // Если фильм не найден, перенаправляем на каталог
        $_SESSION['error'] = "Фильм не найден";
        echo "<script>window.location.href = '?p=catalog';</script>";
        exit();
    }
} else {
    // Если ID не передан, перенаправляем на каталог
    $_SESSION['error'] = "Некорректный идентификатор фильма";
    echo "<script>window.location.href = '?p=catalog';</script>";
    exit();
}
?>
<section class="film-page">
    <?php if(isset($_SESSION['success'])): ?>
    <div class="success-message">
        <?php 
        echo $_SESSION['success'];
        unset($_SESSION['success']);
        ?>
    </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
    <div class="error-message">
        <?php 
        echo $_SESSION['error'];
        unset($_SESSION['error']);
        ?>
    </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['user_id']) && $_SESSION['is_admin'] == 1): ?>
    <div class="admin-controls">
        <button id="edit-film-btn" class="edit-btn">Редактировать фильм</button>
        <a href="incl/connect/delete_film.php?id=<?php echo $film['id']; ?>" class="delete-btn" onclick="return confirm('Вы действительно хотите удалить этот фильм?')">Удалить фильм</a>
    </div>
    <?php endif; ?>
    
    <div class="film-page__main">
        <img src="./assets/media/images/catalog/<?php echo htmlspecialchars($film['poster']); ?>" alt="<?php echo htmlspecialchars($film['title']); ?>" class="film-poster">
        <div class="film-info">
            <h2><?php echo htmlspecialchars($film['title']); ?></h2>
            <h4><?php echo htmlspecialchars($film['original_title']); ?></h4>
            <p><?php echo nl2br(htmlspecialchars($film['description'])); ?></p>
            <div class="film-actions">
                <?php if(!empty($film['video_file'])): ?>
                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <a href="#" class="watch-btn" id="show-login-btn"><img src="./assets/media/images/film-page/play-button.svg" alt="">Смотреть</a>
                    <?php elseif($film['is_premium'] == 1 && (!isset($_SESSION['is_subscribed']) || $_SESSION['is_subscribed'] != 1)): ?>
                        <span class="watch-btn premium-locked">Только для подписчиков</span>
                        <a href="?p=subscribe" class="subscribe-btn">Оформить подписку</a>
                    <?php else: ?>
                        <a href="#" class="watch-btn" id="show-video-btn"><img src="./assets/media/images/film-page/play-button.svg" alt="">Смотреть</a>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="watch-btn disabled"><img src="./assets/media/images/film-page/play-button.svg" alt="">Видео не доступно</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="film-page__info">
        <div class="film-page__info-names">
            <p>Год:</p>
            <p>Страна:</p>
            <p>Слоган:</p>
            <p>Режиссер:</p>
            <?php if(!empty($film['category_name'])): ?>
            <p>Категория:</p>
            <?php endif; ?>
        </div>
        <div class="film-page__info-info">
            <p><?php echo htmlspecialchars($film['year']); ?></p>
            <p><?php echo htmlspecialchars($film['country']); ?></p>
            <p><?php echo !empty($film['slogan']) ? htmlspecialchars($film['slogan']) : 'Не указан'; ?></p>
            <p><?php echo htmlspecialchars($film['director']); ?></p>
            <?php if(!empty($film['category_name'])): ?>
            <p><?php echo htmlspecialchars($film['category_name']); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Модальное окно для просмотра видео -->
<div class="modal-overlay" id="video-modal-overlay"></div>
<div class="video-modal" id="video-modal">
    <div>
        <?php if(!empty($film['video_file'])): ?>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <div class="login-message">
                    <h3>Для просмотра фильма необходима авторизация</h3>
                    <p>Войдите в свой аккаунт или зарегистрируйтесь, чтобы получить доступ к просмотру</p>
                    <div class="login-buttons">
                        <button class="login-btn" data-action="login">Вход</button>
                        <button class="register-btn" data-action="register">Регистрация</button>
                    </div>
                </div>
            <?php elseif($film['is_premium'] == 1 && (!isset($_SESSION['is_subscribed']) || $_SESSION['is_subscribed'] != 1)): ?>
                <div class="premium-content-message">
                    <h3>Доступно только для подписчиков</h3>
                    <p>Оформите подписку, чтобы получить доступ к этому фильму и другому премиум-контенту</p>
                    <a href="?p=subscribe" class="subscribe-btn">Оформить подписку</a>
                </div>
            <?php else: ?>
                <div class="custom-video-player">
                    <video id="film-video">
                        <?php 
                        $videoPath = './assets/media/videos/' . htmlspecialchars($film['video_file']);
                        $extension = pathinfo($film['video_file'], PATHINFO_EXTENSION);
                        $type = 'video/mp4'; // По умолчанию
                        
                        // Определяем тип видео на основе расширения
                        switch(strtolower($extension)) {
                            case 'webm':
                                $type = 'video/webm';
                                break;
                            case 'ogg':
                            case 'ogv':
                                $type = 'video/ogg';
                                break;
                            case 'mov':
                                $type = 'video/quicktime';
                                break;
                            case 'avi':
                                $type = 'video/avi';
                                break;
                        }
                        ?>
                        <source src="<?php echo $videoPath; ?>" type="<?php echo $type; ?>">
                        Ваш браузер не поддерживает видео HTML5.
                    </video>
                    
                    <div class="video-controls">
                        <div class="video-progress">
                            <div class="video-progress-filled"></div>
                        </div>
                        
                        <div class="bottom-controls">
                            <div class="left-controls">
                                <button class="play-pause-btn">
                                    <svg class="play-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 5V19L19 12L8 5Z" fill="currentColor"/>
                                    </svg>
                                    <svg class="pause-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 4H10V20H6V4ZM14 4H18V20H14V4Z" fill="currentColor"/>
                                    </svg>
                                </button>
                                
                                <div class="volume-container">
                                    <button class="volume-btn">
                                        <svg class="volume-high-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.5 3L4.5 9H1.5V15H4.5L14.5 21V3ZM16.5 7.55V16.45C17.53 15.8 18.25 14.26 18.25 12C18.25 9.74 17.53 8.2 16.5 7.55ZM16.5 2.91C18.88 3.93 20.5 7.57 20.5 12C20.5 16.43 18.88 20.07 16.5 21.09V2.91Z" fill="currentColor"/>
                                        </svg>
                                        <svg class="volume-low-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.5 3L4.5 9H1.5V15H4.5L14.5 21V3ZM16.5 7.55V16.45C17.53 15.8 18.25 14.26 18.25 12C18.25 9.74 17.53 8.2 16.5 7.55Z" fill="currentColor"/>
                                        </svg>
                                        <svg class="volume-muted-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.5 3L4.5 9H1.5V15H4.5L14.5 21V3ZM21.5 12L19.5 10L17.5 12L19.5 14L21.5 12ZM17.5 7L19.5 9L21.5 7L19.5 5L17.5 7ZM19.5 19L17.5 17L15.5 19L17.5 21L19.5 19Z" fill="currentColor"/>
                                        </svg>
                                    </button>
                                    <div class="volume-slider">
                                        <div class="volume-slider-filled"></div>
                                    </div>
                                </div>
                                
                                <div class="time-display">
                                    <span class="current-time">0:00</span>
                                    <span class="time-separator">/</span>
                                    <span class="total-time">0:00</span>
                                </div>
                            </div>
                            
                            <div class="right-controls">
                                <button class="speed-btn">1x</button>
                                <button class="fullscreen-btn">
                                    <svg class="fullscreen-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7 14H5V19H10V17H7V14ZM5 10H7V7H10V5H5V10ZM17 17H14V19H19V14H17V17ZM14 5V7H17V10H19V5H14Z" fill="currentColor"/>
                                    </svg>
                                    <svg class="fullscreen-exit-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 16H8V19H10V14H5V16ZM8 8H5V10H10V5H8V8ZM14 19H16V16H19V14H14V19ZM16 8V5H14V10H19V8H16Z" fill="currentColor"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p class="no-video-message">Видео не доступно</p>
        <?php endif; ?>
        <img id="close-video-modal" src="./assets/media/images/header/close.svg" alt="">
    </div>
</div>

<!-- Модальное окно для редактирования фильма, видимое только администраторам -->
<?php if(isset($_SESSION['user_id']) && $_SESSION['is_admin'] == 1): ?>
<div class="modal-overlay" id="edit-film-overlay"></div>
<div class="edit-film-modal" id="edit-film-modal">
    <div>
        <form action="incl/connect/edit_film.php" method="POST" enctype="multipart/form-data">
            <h4>Редактирование фильма</h4>
            <input type="hidden" name="film_id" value="<?php echo $film['id']; ?>">
            
            <div class="form-group">
                <label for="title">Название на русском*</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($film['title']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="original_title">Оригинальное название*</label>
                <input type="text" name="original_title" id="original_title" value="<?php echo htmlspecialchars($film['original_title']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Описание*</label>
                <textarea name="description" id="description" required><?php echo htmlspecialchars($film['description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="year">Год создания*</label>
                <input type="number" name="year" id="year" value="<?php echo htmlspecialchars($film['year']); ?>" min="1900" max="2100" required>
            </div>
            
            <div class="form-group">
                <label for="country">Страна*</label>
                <input type="text" name="country" id="country" value="<?php echo htmlspecialchars($film['country']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="slogan">Слоган</label>
                <input type="text" name="slogan" id="slogan" value="<?php echo htmlspecialchars($film['slogan']); ?>">
            </div>
            
            <div class="form-group">
                <label for="director">Режиссёр*</label>
                <input type="text" name="director" id="director" value="<?php echo htmlspecialchars($film['director']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="category_id">Категория</label>
                <select name="category_id" id="category_id">
                    <option value="">Выберите категорию</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo $film['category_id'] == $category['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="poster">Новый постер</label>
                <input type="file" name="poster" id="poster" accept="image/*">
                <p class="form-hint">Оставьте пустым, чтобы сохранить текущий постер</p>
                <div class="current-poster">
                    <p>Текущий постер:</p>
                    <img src="./assets/media/images/catalog/<?php echo htmlspecialchars($film['poster']); ?>" alt="Текущий постер">
                </div>
            </div>
            
            <div class="form-group">
                <label for="video_file">Видеофайл фильма</label>
                <input type="file" name="video_file" id="video_file" accept="video/mp4,video/webm,video/avi,video/quicktime,video/ogg">
                <p class="form-hint">Поддерживаются файлы формата MP4, WebM, AVI, MOV и OGG. Оставьте пустым, чтобы сохранить текущий видеофайл</p>
                <?php if(!empty($film['video_file'])): ?>
                <div class="current-video">
                    <p>Текущий видеофайл: <?php echo htmlspecialchars($film['video_file']); ?></p>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="is_premium">Доступ к фильму</label>
                <select name="is_premium" id="is_premium">
                    <option value="0" <?php echo $film['is_premium'] == 0 ? 'selected' : ''; ?>>Доступен всем пользователям</option>
                    <option value="1" <?php echo $film['is_premium'] == 1 ? 'selected' : ''; ?>>Только для подписчиков</option>
                </select>
                <p class="form-hint">Укажите, кому будет доступен фильм</p>
            </div>
            
            <div class="form-buttons">
                <input type="submit" value="Сохранить изменения">
                <a href="#" id="cancel-edit">Отмена</a>
            </div>
        </form>
        <img id="close-edit-film-modal" src="./assets/media/images/header/close.svg" alt="">
    </div>
</div>
<?php endif; ?>

<style>
    .film-page {
        margin: 0 auto;
        margin-top: 35px;
        max-width: 1430px;
    }
    
    .success-message,
    .error-message {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        text-align: center;
        font-family: Qanelas;
    }
    
    .success-message {
        background-color: rgba(0, 255, 0, 0.2);
        color: #fff;
    }
    
    .error-message {
        background-color: rgba(255, 0, 0, 0.2);
        color: #fff;
    }
    
    .admin-controls {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .admin-controls .edit-btn,
    .admin-controls .delete-btn {
        padding: 10px 20px;
        border-radius: 5px;
        font-family: Qanelas;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    
    .admin-controls .edit-btn {
        background-color: #3657CB;
        color: #FFF;
        border: none;
    }
    
    .admin-controls .delete-btn {
        background-color: #CB3F36;
        color: #FFF;
    }

    .film-page__main {
        display: flex;
        align-items: flex-start;
    }

    .film-page__main .film-poster {
        max-width: 400px;
        height: auto;
        border-radius: 15px;
        object-fit: cover;
    }

    .film-page__main>div {
        margin-left: 54px;
    }

    .film-page__main>div>h2 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 65px;
        font-style: normal;
        font-weight: 900;
        line-height: normal;
    }

    .film-page__main>div>h4 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 25px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
        margin-top: 10px;
    }

    .film-page__main>div>p {
        margin-top: 50px;
        color: #FFF;
        font-family: Qanelas;
        font-size: 20px;
        font-style: normal;
        font-weight: 500;
        line-height: 166.5%;
    }

    .film-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 30px;
    }

    .film-page__main .watch-btn {
        display: flex;
        gap: 12px;
        align-items: center;
        max-width: fit-content;
        border-radius: 10px;
        border: 2px solid #FFF;
        color: #FFF;
        font-family: Qanelas;
        font-size: 18px;
        font-style: normal;
        font-weight: 700;
        line-height: 166.5%;
        padding: 20px 36px;
        transition: all 0.3s ease;
    }
    
    .film-page__main .watch-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .film-page__info {
        margin-top: 80px;
        display: flex;
        gap: 64px;
    }

    .film-page__info-names {
        display: flex;
        flex-direction: column;
        gap: 12px;
        color: rgba(255, 255, 255, 0.90);
        font-family: Qanelas;
        font-size: 18px;
        font-style: normal;
        font-weight: 600;
        line-height: normal;
    }

    .film-page__info-info {
        display: flex;
        flex-direction: column;
        gap: 12px;
        color: #F2F60F;
        font-family: Qanelas;
        font-size: 18px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        text-decoration-line: underline;
    }
    
    /* Модальное окно для редактирования фильма */
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
    
    .edit-film-modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 999;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .edit-film-modal.active {
        opacity: 1;
    }
    
    .edit-film-modal > div {
        position: relative;
        padding: 30px;
        border-radius: 10px;
        background: #191E2E;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    #close-edit-film-modal {
        position: absolute;
        top: 20px;
        right: 20px;
        cursor: pointer;
    }
    
    .edit-film-modal form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .edit-film-modal form h4 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 24px;
        font-style: normal;
        font-weight: 900;
        line-height: normal;
        text-align: center;
        margin-bottom: 10px;
    }
    
    .edit-film-modal .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .edit-film-modal .form-group label {
        color: #FFF;
        font-family: Qanelas;
        font-size: 16px;
        font-weight: 600;
    }
    
    .edit-film-modal .form-group input[type="text"],
    .edit-film-modal .form-group input[type="number"],
    .edit-film-modal .form-group select,
    .edit-film-modal .form-group textarea {
        width: 100%;
        padding: 10px 15px;
        border-radius: 5px;
        background: #1E2538;
        color: rgba(255, 255, 255, 0.9);
        font-family: Qanelas;
        font-size: 15px;
        border: none;
    }
    
    .edit-film-modal .form-group textarea {
        min-height: 150px;
        resize: vertical;
    }
    
    .edit-film-modal .form-hint {
        color: rgba(255, 255, 255, 0.5);
        font-size: 12px;
        margin-top: 5px;
    }
    
    .edit-film-modal .current-poster {
        margin-top: 10px;
    }
    
    .edit-film-modal .current-poster p {
        color: #FFF;
        font-family: Qanelas;
        font-size: 14px;
        margin-bottom: 10px;
    }
    
    .edit-film-modal .current-poster img {
        max-width: 200px;
        max-height: 300px;
        border-radius: 5px;
    }
    
    .edit-film-modal .form-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
    }
    
    .edit-film-modal .form-buttons input[type="submit"] {
        border-radius: 10px;
        background: #3657CB;
        padding: 15px 30px;
        border: none;
        color: #FFF;
        font-family: Qanelas;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
    }
    
    .edit-film-modal .form-buttons a {
        color: #E0E0E0;
        font-family: Qanelas;
        font-size: 16px;
        font-weight: 400;
        cursor: pointer;
        text-decoration: none;
    }
    
    body.lock {
        overflow: hidden;
    }
    
    /* Медиа-запрос для устройств с шириной экрана 1024px */
    @media (max-width: 1024px) {
        .film-page {
            max-width: 980px;
            padding: 0 20px;
        }
        
        .film-page__main .film-poster {
            max-width: 350px;
        }
        
        .film-page__main>div>h2 {
            font-size: 50px;
        }
        
        .film-page__main>div>h4 {
            font-size: 22px;
        }
        
        .film-page__main>div>p {
            margin-top: 30px;
            font-size: 18px;
        }
        
        .film-actions {
            margin-top: 25px;
        }
        
        .film-page__main .watch-btn {
            padding: 15px 25px;
            font-size: 16px;
        }
        
        .film-page__info {
            margin-top: 60px;
            gap: 30px;
        }
        
        .edit-film-modal > div {
            max-width: 90%;
            padding: 20px;
        }
    }
    
    /* Медиа-запрос для устройств с шириной экрана 768px (планшеты) */
    @media (max-width: 768px) {
        .film-page__main {
            flex-direction: column;
            align-items: center;
        }
        
        .film-page__main .film-poster {
            max-width: 400px;
            width: 100%;
        }
        
        .film-page__main>div {
            margin-left: 0;
            margin-top: 30px;
            width: 100%;
        }
        
        .film-page__main>div>h2 {
            font-size: 40px;
            text-align: center;
        }
        
        .film-page__main>div>h4 {
            font-size: 20px;
            text-align: center;
        }
        
        .film-actions {
            justify-content: center;
        }
        
        .admin-controls {
            flex-direction: column;
            gap: 10px;
        }
    }
    
    /* Медиа-запрос для устройств с шириной экрана 390px (мобильные) */
    @media (max-width: 390px) {
        .film-page {
            max-width: 370px;
            padding: 0 10px;
            margin-top: 20px;
        }
        
        .film-page__main>div>h2 {
            font-size: 32px;
        }
        
        .film-page__main>div>h4 {
            font-size: 18px;
        }
        
        .film-page__main>div>p {
            margin-top: 20px;
            font-size: 16px;
        }
        
        .film-actions {
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        
        .film-page__main .watch-btn {
            width: 100%;
            justify-content: center;
            padding: 15px 20px;
            font-size: 16px;
        }
        
        .film-page__info {
            margin-top: 40px;
            gap: 20px;
        }
        
        .film-page__info-names, 
        .film-page__info-info {
            font-size: 16px;
        }
    }
    
    /* Стили для видео модального окна */
    .video-modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 999;
        opacity: 0;
        transition: opacity 0.3s ease;
        width: 90vw;
        max-width: 1280px;
    }
    
    .video-modal.active {
        opacity: 1;
    }
    
    .video-modal > div {
        position: relative;
        background: #000;
        border-radius: 10px;
        overflow: hidden;
        width: 100%;
        height: 0;
        padding-bottom: 56.25%; /* 16:9 соотношение сторон */
    }
    
    /* Кастомный видеоплеер */
    .custom-video-player {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: #000;
        overflow: hidden;
    }
    
    .custom-video-player video {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    
    .video-controls {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0) 100%);
        padding: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 2;
    }
    
    .custom-video-player:hover .video-controls,
    .video-controls.active {
        opacity: 1;
    }
    
    .video-progress {
        width: 100%;
        height: 5px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 5px;
        margin-bottom: 15px;
        cursor: pointer;
        position: relative;
    }
    
    .video-progress-filled {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 0;
        background: #3657CB;
        border-radius: 5px;
        transition: width 0.1s ease;
    }
    
    .bottom-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .left-controls, .right-controls {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .play-pause-btn, .volume-btn, .fullscreen-btn, .speed-btn {
        background: none;
        border: none;
        color: white;
        font-size: 16px;
        cursor: pointer;
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        transition: background-color 0.3s ease;
    }
    
    .play-pause-btn:hover, .volume-btn:hover, .fullscreen-btn:hover, .speed-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
    
    .play-pause-btn svg, .volume-btn svg, .fullscreen-btn svg {
        width: 24px;
        height: 24px;
    }
    
    .pause-icon, .fullscreen-exit-icon {
        display: none;
    }
    
    .volume-low-icon, .volume-muted-icon {
        display: none;
    }
    
    .custom-video-player.paused .play-icon {
        display: block;
    }
    
    .custom-video-player.paused .pause-icon {
        display: none;
    }
    
    .custom-video-player:not(.paused) .play-icon {
        display: none;
    }
    
    .custom-video-player:not(.paused) .pause-icon {
        display: block;
    }
    
    .volume-container {
        display: flex;
        align-items: center;
    }
    
    .volume-slider {
        width: 0;
        height: 5px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 5px;
        margin-left: 10px;
        position: relative;
        cursor: pointer;
        transition: width 0.3s ease;
        overflow: hidden;
    }
    
    .volume-container:hover .volume-slider {
        width: 60px;
    }
    
    .volume-slider-filled {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: #3657CB;
        border-radius: 5px;
    }
    
    .time-display {
        color: white;
        font-family: Qanelas;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .speed-btn {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        padding: 6px 8px;
        width: auto;
        height: auto;
        font-family: Qanelas;
        font-weight: 600;
    }
    
    #close-video-modal {
        position: absolute;
        top: 20px;
        right: 20px;
        cursor: pointer;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        padding: 5px;
        z-index: 3;
    }
    
    /* Медиа-запросы для видеоплеера */
    @media (max-width: 768px) {
        .right-controls {
            gap: 10px;
        }
        
        .left-controls {
            gap: 10px;
        }
        
        .speed-btn {
            display: none;
        }
        
        .time-display {
            font-size: 12px;
        }
        
        .play-pause-btn, .volume-btn, .fullscreen-btn {
            width: 32px;
            height: 32px;
        }
        
        .play-pause-btn svg, .volume-btn svg, .fullscreen-btn svg {
            width: 20px;
            height: 20px;
        }
    }
    
    @media (max-width: 480px) {
        .video-controls {
            padding: 10px;
        }
        
        .volume-container {
            display: none;
        }
        
        .time-display {
            margin-left: 10px;
        }
        
        .video-progress {
            margin-bottom: 10px;
        }
    }
    
    .no-video-message {
        color: #FFF;
        font-family: Qanelas;
        font-size: 18px;
        padding: 30px;
        text-align: center;
    }
    
    .watch-btn.disabled {
        opacity: 0.5;
        cursor: default;
    }
    
    .watch-btn.disabled:hover {
        background-color: transparent;
    }
    
    .watch-btn.premium-locked {
        background-color: #1E2538;
        color: #F2F60F;
        border: 1px solid #F2F60F;
        cursor: not-allowed;
    }
    
    .watch-btn.premium-locked img {
        margin-right: 10px;
        width: 16px;
        height: 16px;
    }
    
    .subscribe-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px 20px;
        background-color: #F2F60F;
        color: #0F1527;
        border-radius: 50px;
        text-decoration: none;
        font-family: Qanelas;
        font-weight: 500;
        margin-left: 15px;
        transition: background-color 0.3s;
    }
    
    .subscribe-btn:hover {
        background-color: #D6D91F;
    }
    
    .premium-content-message {
        color: #FFF;
        font-family: Qanelas;
        text-align: center;
        padding: 40px;
    }
    
    .premium-content-message h3 {
        color: #F2F60F;
        font-size: 24px;
        margin-bottom: 15px;
    }
    
    .premium-content-message p {
        margin-bottom: 25px;
        font-size: 18px;
        color: rgba(255, 255, 255, 0.8);
    }
    
    .premium-content-message .subscribe-btn {
        padding: 12px 30px;
        font-size: 18px;
    }
    
    /* Стили для блока авторизации */
    .login-message {
        color: #FFF;
        font-family: Qanelas;
        text-align: center;
        padding: 40px;
        max-width: fit-content;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .login-message h3 {
        font-size: 24px;
        margin-bottom: 15px;
        color: #FFF;
    }
    
    .login-message p {
        margin-bottom: 25px;
        font-size: 18px;
        color: rgba(255, 255, 255, 0.8);
    }
    
    .login-buttons {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }
    
    .login-btn, .register-btn {
        padding: 12px 30px;
        font-size: 16px;
        font-family: Qanelas;
        font-weight: 600;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    
    .login-btn {
        background-color: #3657CB;
        color: #FFF;
    }
    
    .login-btn:hover {
        background-color: #2A44A4;
    }
    
    .register-btn {
        background-color: #F2F60F;
        color: #0F1527;
    }
    
    .register-btn:hover {
        background-color: #D6D91F;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Редактирование фильма
        const editFilmBtn = document.getElementById('edit-film-btn');
        const editFilmModal = document.getElementById('edit-film-modal');
        const editFilmOverlay = document.getElementById('edit-film-overlay');
        const closeEditFilmModal = document.getElementById('close-edit-film-modal');
        const cancelEditFilm = document.getElementById('cancel-edit');
        
        if(editFilmBtn) {
            editFilmBtn.addEventListener('click', function() {
                editFilmModal.style.display = 'block';
                editFilmOverlay.style.display = 'block';
                document.body.classList.add('lock');
                
                // Добавляем класс active с небольшой задержкой для плавной анимации
                setTimeout(() => {
                    editFilmModal.classList.add('active');
                    editFilmOverlay.classList.add('active');
                }, 10);
            });
        }
        
        if(closeEditFilmModal) {
            closeEditFilmModal.addEventListener('click', closeEditModal);
        }
        
        if(cancelEditFilm) {
            cancelEditFilm.addEventListener('click', function(e) {
                e.preventDefault();
                closeEditModal();
            });
        }
        
        function closeEditModal() {
            editFilmModal.classList.remove('active');
            editFilmOverlay.classList.remove('active');
            
            setTimeout(() => {
                editFilmModal.style.display = 'none';
                editFilmOverlay.style.display = 'none';
                document.body.classList.remove('lock');
            }, 300);
        }
        
        // Просмотр видео
        const showVideoBtn = document.getElementById('show-video-btn');
        const videoModal = document.getElementById('video-modal');
        const videoModalOverlay = document.getElementById('video-modal-overlay');
        const closeVideoModal = document.getElementById('close-video-modal');
        const filmVideo = document.getElementById('film-video');
        
        if(showVideoBtn) {
            showVideoBtn.addEventListener('click', function(e) {
                e.preventDefault();
                videoModal.style.display = 'block';
                videoModalOverlay.style.display = 'block';
                document.body.classList.add('lock');
                
                // Добавляем класс active с небольшой задержкой для плавной анимации
                setTimeout(() => {
                    videoModal.classList.add('active');
                    videoModalOverlay.classList.add('active');
                    
                    // Автоматическое воспроизведение видео после открытия модального окна
                    if (filmVideo) {
                        filmVideo.play()
                            .then(() => {
                                // Успешное автовоспроизведение
                                videoPlayer.classList.remove('paused');
                            })
                            .catch(error => {
                                console.error('Ошибка автовоспроизведения:', error);
                                // Ничего не делаем, пользователь может включить видео сам
                            });
                    }
                }, 10);
            });
        }
        
        if(closeVideoModal) {
            closeVideoModal.addEventListener('click', closeVideoPlayerModal);
        }
        
        if(videoModalOverlay) {
            videoModalOverlay.addEventListener('click', closeVideoPlayerModal);
        }
        
        function closeVideoPlayerModal() {
            if(filmVideo) {
                filmVideo.pause();
            }
            
            videoModal.classList.remove('active');
            videoModalOverlay.classList.remove('active');
            
            setTimeout(() => {
                videoModal.style.display = 'none';
                videoModalOverlay.style.display = 'none';
                document.body.classList.remove('lock');
            }, 300);
        }
        
        // Обработка клика на кнопке просмотра для неавторизованных пользователей
        const showLoginBtn = document.getElementById('show-login-btn');
        if(showLoginBtn) {
            showLoginBtn.addEventListener('click', function(e) {
                e.preventDefault();
                videoModal.style.display = 'block';
                videoModalOverlay.style.display = 'block';
                document.body.classList.add('lock');
                
                // Добавляем класс active с небольшой задержкой для плавной анимации
                setTimeout(() => {
                    videoModal.classList.add('active');
                    videoModalOverlay.classList.add('active');
                }, 10);
            });
        }
        
        // Обработка клика на кнопках авторизации/регистрации
        const loginButtons = document.querySelectorAll('.login-btn, .register-btn');
        if(loginButtons.length > 0) {
            loginButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const action = this.getAttribute('data-action');
                    
                    // Закрываем модальное окно
                    closeVideoPlayerModal();
                    
                    // После закрытия и небольшой задержки перенаправляем на главную страницу
                    setTimeout(() => {
                        window.location.href = '?p=home&action=' + action;
                    }, 300);
                });
            });
        }
        
        // Инициализация кастомного видеоплеера
        const videoPlayer = document.querySelector('.custom-video-player');
        const video = document.getElementById('film-video');
        
        if(videoPlayer && video) {
            const playPauseBtn = videoPlayer.querySelector('.play-pause-btn');
            const volumeBtn = videoPlayer.querySelector('.volume-btn');
            const fullscreenBtn = videoPlayer.querySelector('.fullscreen-btn');
            const speedBtn = videoPlayer.querySelector('.speed-btn');
            const progress = videoPlayer.querySelector('.video-progress');
            const progressFilled = videoPlayer.querySelector('.video-progress-filled');
            const volumeSlider = videoPlayer.querySelector('.volume-slider');
            const volumeFilled = videoPlayer.querySelector('.volume-slider-filled');
            const currentTimeEl = videoPlayer.querySelector('.current-time');
            const totalTimeEl = videoPlayer.querySelector('.total-time');
            const videoControls = videoPlayer.querySelector('.video-controls');
            
            // Состояние видеоплеера
            let isFullScreen = false;
            let controlsTimeout;
            
            // Кастомные элементы управления
            videoPlayer.classList.add('paused');
            
            // Функция для форматирования времени
            function formatTime(seconds) {
                const minutes = Math.floor(seconds / 60);
                seconds = Math.floor(seconds % 60);
                return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            }
            
            // Обновление времени
            function updateTime() {
                currentTimeEl.textContent = formatTime(video.currentTime);
                totalTimeEl.textContent = formatTime(video.duration);
                
                // Обновление прогресса
                const percent = (video.currentTime / video.duration) * 100;
                progressFilled.style.width = `${percent}%`;
            }
            
            // Загрузка метаданных видео
            video.addEventListener('loadedmetadata', function() {
                totalTimeEl.textContent = formatTime(video.duration);
                updateTime();
            });
            
            // Воспроизведение/пауза
            playPauseBtn.addEventListener('click', function() {
                if(video.paused) {
                    video.play();
                    videoPlayer.classList.remove('paused');
                } else {
                    video.pause();
                    videoPlayer.classList.add('paused');
                }
            });
            
            // Двойной клик по видео для полноэкранного режима
            video.addEventListener('dblclick', function() {
                toggleFullScreen();
            });
            
            // Клик по видео для воспроизведения/паузы
            video.addEventListener('click', function() {
                if(video.paused) {
                    video.play();
                    videoPlayer.classList.remove('paused');
                } else {
                    video.pause();
                    videoPlayer.classList.add('paused');
                }
            });
            
            // Обновление прогресса видео
            video.addEventListener('timeupdate', updateTime);
            
            // Управление громкостью
            volumeBtn.addEventListener('click', function() {
                if(video.volume === 0) {
                    // Если звук выключен, возвращаем его к предыдущему уровню
                    video.volume = 1;
                    volumeFilled.style.width = '100%';
                    volumeBtn.querySelector('.volume-muted-icon').style.display = 'none';
                    volumeBtn.querySelector('.volume-high-icon').style.display = 'block';
                } else {
                    // Если звук включен, выключаем его
                    video.volume = 0;
                    volumeFilled.style.width = '0%';
                    volumeBtn.querySelector('.volume-high-icon').style.display = 'none';
                    volumeBtn.querySelector('.volume-low-icon').style.display = 'none';
                    volumeBtn.querySelector('.volume-muted-icon').style.display = 'block';
                }
            });
            
            // Регулировка громкости с помощью слайдера
            volumeSlider.addEventListener('mousedown', function(e) {
                // Отменяем другие события мыши внутри элемента
                e.stopPropagation();
                
                function adjustVolume(e) {
                    const rect = volumeSlider.getBoundingClientRect();
                    let volume = (e.clientX - rect.left) / rect.width;
                    
                    // Ограничиваем значение от 0 до 1
                    volume = Math.max(0, Math.min(1, volume));
                    
                    // Устанавливаем громкость и обновляем интерфейс
                    video.volume = volume;
                    volumeFilled.style.width = `${volume * 100}%`;
                    
                    // Обновляем иконку в зависимости от уровня громкости
                    if(volume === 0) {
                        volumeBtn.querySelector('.volume-high-icon').style.display = 'none';
                        volumeBtn.querySelector('.volume-low-icon').style.display = 'none';
                        volumeBtn.querySelector('.volume-muted-icon').style.display = 'block';
                    } else if(volume < 0.5) {
                        volumeBtn.querySelector('.volume-high-icon').style.display = 'none';
                        volumeBtn.querySelector('.volume-low-icon').style.display = 'block';
                        volumeBtn.querySelector('.volume-muted-icon').style.display = 'none';
                    } else {
                        volumeBtn.querySelector('.volume-high-icon').style.display = 'block';
                        volumeBtn.querySelector('.volume-low-icon').style.display = 'none';
                        volumeBtn.querySelector('.volume-muted-icon').style.display = 'none';
                    }
                }
                
                adjustVolume(e);
                
                function onMouseMove(e) {
                    adjustVolume(e);
                }
                
                function onMouseUp() {
                    document.removeEventListener('mousemove', onMouseMove);
                    document.removeEventListener('mouseup', onMouseUp);
                }
                
                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
            });
            
            // Клик на полосе прогресса для перемотки
            progress.addEventListener('mousedown', function(e) {
                // Отменяем другие события мыши внутри элемента
                e.stopPropagation();
                
                function scrub(e) {
                    const rect = progress.getBoundingClientRect();
                    const scrubTime = ((e.clientX - rect.left) / rect.width) * video.duration;
                    
                    // Устанавливаем текущее время видео
                    video.currentTime = scrubTime;
                }
                
                scrub(e);
                
                function onMouseMove(e) {
                    scrub(e);
                }
                
                function onMouseUp() {
                    document.removeEventListener('mousemove', onMouseMove);
                    document.removeEventListener('mouseup', onMouseUp);
                }
                
                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
            });
            
            // Управление скоростью воспроизведения
            speedBtn.addEventListener('click', function() {
                let speed = video.playbackRate;
                
                // Циклически меняем скорость: 1 -> 1.5 -> 2 -> 0.5 -> 0.75 -> 1
                switch(speed) {
                    case 1:
                        speed = 1.5;
                        break;
                    case 1.5:
                        speed = 2;
                        break;
                    case 2:
                        speed = 0.5;
                        break;
                    case 0.5:
                        speed = 0.75;
                        break;
                    default:
                        speed = 1;
                }
                
                video.playbackRate = speed;
                speedBtn.textContent = `${speed}x`;
            });
            
            // Функция для переключения полноэкранного режима
            function toggleFullScreen() {
                if (!isFullScreen) {
                    if (videoPlayer.requestFullscreen) {
                        videoPlayer.requestFullscreen();
                    } else if (videoPlayer.mozRequestFullScreen) {
                        videoPlayer.mozRequestFullScreen();
                    } else if (videoPlayer.webkitRequestFullscreen) {
                        videoPlayer.webkitRequestFullscreen();
                    } else if (videoPlayer.msRequestFullscreen) {
                        videoPlayer.msRequestFullscreen();
                    }
                    
                    fullscreenBtn.querySelector('.fullscreen-icon').style.display = 'none';
                    fullscreenBtn.querySelector('.fullscreen-exit-icon').style.display = 'block';
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }
                    
                    fullscreenBtn.querySelector('.fullscreen-icon').style.display = 'block';
                    fullscreenBtn.querySelector('.fullscreen-exit-icon').style.display = 'none';
                }
                
                isFullScreen = !isFullScreen;
            }
            
            // Кнопка полноэкранного режима
            fullscreenBtn.addEventListener('click', toggleFullScreen);
            
            // Обработка изменения состояния полноэкранного режима
            document.addEventListener('fullscreenchange', function() {
                isFullScreen = !!document.fullscreenElement;
                
                if (isFullScreen) {
                    fullscreenBtn.querySelector('.fullscreen-icon').style.display = 'none';
                    fullscreenBtn.querySelector('.fullscreen-exit-icon').style.display = 'block';
                } else {
                    fullscreenBtn.querySelector('.fullscreen-icon').style.display = 'block';
                    fullscreenBtn.querySelector('.fullscreen-exit-icon').style.display = 'none';
                }
            });
            
            // Скрытие элементов управления при неактивности
            function hideControls() {
                if (!video.paused) {
                    videoControls.classList.remove('active');
                }
            }
            
            function resetControlsTimeout() {
                videoControls.classList.add('active');
                clearTimeout(controlsTimeout);
                controlsTimeout = setTimeout(hideControls, 3000);
            }
            
            videoPlayer.addEventListener('mousemove', resetControlsTimeout);
            videoPlayer.addEventListener('touchstart', resetControlsTimeout, { passive: true });
            
            // Предотвращаем исчезновение панели при наведении на нее
            videoControls.addEventListener('mouseenter', function() {
                clearTimeout(controlsTimeout);
            });
            
            // После ухода с панели возобновляем таймер
            videoControls.addEventListener('mouseleave', function() {
                if (!video.paused) {
                    controlsTimeout = setTimeout(hideControls, 3000);
                }
            });
            
            // Отображаем элементы управления изначально
            resetControlsTimeout();
            
            // Отображаем панель управления при паузе
            video.addEventListener('pause', function() {
                videoControls.classList.add('active');
                clearTimeout(controlsTimeout);
            });
            
            // Скрываем панель управления при воспроизведении
            video.addEventListener('play', function() {
                resetControlsTimeout();
            });
            
            // Обработка завершения видео
            video.addEventListener('ended', function() {
                videoPlayer.classList.add('paused');
                video.currentTime = 0;
            });
        }
    });
</script>