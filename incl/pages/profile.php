<?php
// Подключаемся к базе данных
require_once 'incl/connect/connect.php';

// Проверяем, авторизован ли пользователь
if(!isset($_SESSION['user_id'])) {
    header('Location: ?p=login');
    exit();
}

// Получаем данные пользователя
$userQuery = "SELECT * FROM users WHERE id = :id";
$userStmt = $connect->prepare($userQuery);
$userStmt->bindParam(':id', $_SESSION['user_id']);
$userStmt->execute();
$user = $userStmt->fetch(PDO::FETCH_ASSOC);
?>
<section class="profile">
    <div class="link-bar">
        <a href="?p=profile">
            <img src="./assets/media/images/profile/linkbar1.svg" alt="">
        </a>
        <?php if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1): ?>
        <a href="?p=subscribe">
            <img src="./assets/media/images/profile/linkbar2.svg" alt="">
        </a>
        <?php endif; ?>
        <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
        <a href="?p=admin">
            <img src="./assets/media/images/profile/linkbar3.svg" alt="">
        </a>
        <?php endif; ?>
    </div>
    <div class="profile-page">
        <?php if(isset($_SESSION['success'])): ?>
        <div class="success-message">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
        <div class="error-message">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
        <?php endif; ?>

        <h3>Ваш профиль</h3>
        
        <div class="profile-info">
            <div class="avatar-container">
                <?php 
                // Проверяем наличие аватара пользователя
                if(isset($_SESSION['user_avatar']) && !empty($_SESSION['user_avatar']) && $_SESSION['user_avatar'] != 'default.jpg') {
                    $avatar_path = 'incl/userprofiles/' . $_SESSION['user_avatar'];
                    // Дополнительная проверка существования файла
                    if(!file_exists($avatar_path)) {
                        $avatar_path = 'assets/media/images/profile/default-avatar.png';
                    }
                } else {
                    $avatar_path = 'assets/media/images/profile/default-avatar.png';
                }
                ?>
                <img src="<?php echo $avatar_path; ?>" alt="Аватар пользователя" class="profile-avatar">
                <form action="" method="POST" enctype="multipart/form-data" class="avatar-form">
                    <label for="avatar-upload" class="avatar-upload-label">Изменить аватар</label>
                    <input type="file" name="avatar" id="avatar-upload" class="avatar-upload-input">
                    <input type="submit" name="update_avatar" value="Загрузить" class="avatar-upload-submit">
                </form>
            </div>
            
            <div class="profile-data">
                <div class="profile-view">
                    <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                    <div class="profile-email">
                        <p>Почта:</p>
                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    <div class="profile-buttons">
                        <button class="edit-profile-btn" onclick="toggleEditMode()">Редактировать профиль</button>
                        <a href="incl/connect/logout.php" class="logout-btn">Выйти из аккаунта</a>
                    </div>
                </div>

                <form action="incl/connect/update_profile.php" method="POST" class="profile-form" style="display: none;">
                    <div class="form-group">
                        <label for="name">Имя</label>
                        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="current_password">Текущий пароль</label>
                        <input type="password" name="current_password" id="current_password" placeholder="Введите текущий пароль">
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">Новый пароль</label>
                        <input type="password" name="new_password" id="new_password" placeholder="Введите новый пароль">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Подтверждение пароля</label>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Подтвердите новый пароль">
                    </div>
                    
                    <div class="profile-buttons">
                        <button type="submit" class="update-profile-btn">Сохранить изменения</button>
                        <button type="button" class="cancel-btn" onclick="toggleEditMode()">Отмена</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
    .profile {
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

    .profile-page {
        padding: 30px 50px;
        background: #191E2E;
        border-radius: 10px;
        position: relative;
        width: 1314px;
    }

    .profile-page>h3 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 30px;
        font-weight: 900;
        margin-bottom: 30px;
    }

    .profile-info {
        display: flex;
        gap: 48px;
    }

    .avatar-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }
    
    .profile-avatar {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #3657CB;
    }
    
    .avatar-form {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    
    .avatar-upload-label {
        display: inline-block;
        padding: 8px 15px;
        background-color: #1E2538;
        color: #FFF;
        border-radius: 8px;
        font-family: Qanelas;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .avatar-upload-label:hover {
        background-color: #2A3048;
    }
    
    .avatar-upload-input {
        display: none;
    }
    
    .avatar-upload-submit {
        padding: 8px 15px;
        background-color: #3657CB;
        color: #FFF;
        border-radius: 8px;
        font-family: Qanelas;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .avatar-upload-submit:hover {
        background-color: #2A44A4;
    }

    .profile-data {
        flex: 1;
    }

    .profile-view {
        margin-top: 20px;
    }

    .profile-view h2 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 50px;
        font-weight: 900;
        margin-bottom: 20px;
    }

    .profile-email {
        display: flex;
        gap: 64px;
        margin-bottom: 30px;
    }

    .profile-email p:first-child {
        color: rgba(255, 255, 255, 0.80);
        font-family: Qanelas;
        font-size: 18px;
        font-weight: 600;
    }

    .profile-email p:last-child {
        color: #F2F60F;
        font-family: Qanelas;
        font-size: 18px;
        font-weight: 400;
    }

    .edit-profile-btn {
        padding: 12px 25px;
        background-color: #3657CB;
        color: #FFF;
        border: none;
        border-radius: 10px;
        font-family: Qanelas;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .edit-profile-btn:hover {
        background-color: #2A44A4;
    }

    .profile-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        color: rgba(255, 255, 255, 0.7);
        font-family: Qanelas;
        font-size: 16px;
    }

    .form-group input {
        padding: 12px 15px;
        border-radius: 8px;
        background: #1E2538;
        border: 1px solid rgba(54, 87, 203, 0.2);
        color: #FFF;
        font-family: Qanelas;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .form-group input:focus {
        border-color: #3657CB;
        outline: none;
    }

    .profile-buttons {
        display: flex;
        gap: 20px;
        margin-top: 10px;
    }

    .update-profile-btn, .cancel-btn, .edit-profile-btn, .logout-btn {
        padding: 12px 25px;
        border-radius: 10px;
        font-family: Qanelas;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
    }

    .update-profile-btn, .edit-profile-btn {
        background-color: #3657CB;
        color: #FFF;
        border: none;
    }

    .update-profile-btn:hover, .edit-profile-btn:hover {
        background-color: #2A44A4;
    }

    .cancel-btn, .logout-btn {
        background-color: #1E2538;
        color: #FFF;
        border: 1px solid #3657CB;
    }

    .cancel-btn:hover, .logout-btn:hover {
        background-color: #2A3048;
    }

    .success-message, .error-message {
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-family: Qanelas;
        font-size: 16px;
    }

    .success-message {
        background-color: rgba(46, 125, 50, 0.2);
        color: #4CAF50;
        border: 1px solid #4CAF50;
    }

    .error-message {
        background-color: rgba(198, 40, 40, 0.2);
        color: #F44336;
        border: 1px solid #F44336;
    }

    @media (max-width: 1024px) {
        .profile {
            max-width: 90%;
        }

        .profile-page {
            width: calc(100% - 107px);
            padding: 20px 30px;
        }

        .profile-info {
            flex-direction: column;
            align-items: center;
        }

        .profile-data {
            width: 100%;
        }

        .profile-view h2 {
            font-size: 40px;
        }

        .profile-email {
            gap: 40px;
        }
    }

    @media (max-width: 390px) {
        .profile {
            max-width: 95%;
            flex-direction: column;
            gap: 15px;
            margin-top: 15px;
        }

        .link-bar {
            flex-direction: row;
            justify-content: center;
        }

        .link-bar>a {
            width: 80px;
            height: 80px;
        }

        .profile-page {
            width: 100%;
            padding: 20px 15px;
        }

        .profile-page>h3 {
            font-size: 24px;
            text-align: center;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
        }

        .profile-view h2 {
            font-size: 30px;
            text-align: center;
        }

        .profile-email {
            flex-direction: column;
            gap: 5px;
            text-align: center;
        }

        .form-group input {
            font-size: 14px;
        }

        .profile-buttons {
            flex-direction: column;
        }

        .update-profile-btn, .cancel-btn, .edit-profile-btn, .logout-btn {
            width: 100%;
        }
    }
</style>

<script>
    // Показываем выбранный файл
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('avatar-upload');
        const fileLabel = document.querySelector('.avatar-upload-label');
        
        fileInput.addEventListener('change', function() {
            if(this.files && this.files[0]) {
                fileLabel.textContent = 'Выбран файл: ' + this.files[0].name;
            }
        });
    });

    // Функция переключения режима редактирования
    function toggleEditMode() {
        const profileView = document.querySelector('.profile-view');
        const profileForm = document.querySelector('.profile-form');
        
        if (profileView.style.display !== 'none') {
            profileView.style.display = 'none';
            profileForm.style.display = 'flex';
        } else {
            profileView.style.display = 'block';
            profileForm.style.display = 'none';
        }
    }
</script>
