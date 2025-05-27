<?php
// Подключаемся к базе данных
require_once __DIR__ . '/../connect/connect.php';

// Проверяем, авторизован ли пользователь
if(!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Для оформления подписки необходимо авторизоваться";
    header('Location: ?p=profile');
    exit();
}

// Проверяем, есть ли уже активный запрос
$checkQuery = "SELECT * FROM subscription_requests 
              WHERE user_id = :user_id AND status = 'pending'";
$checkStmt = $connect->prepare($checkQuery);
$checkStmt->bindParam(':user_id', $_SESSION['user_id']);
$checkStmt->execute();
$hasActiveRequest = $checkStmt->rowCount() > 0;

// Проверяем наличие активной подписки
$subscriptionQuery = "SELECT * FROM subscription_requests 
                     WHERE user_id = :user_id AND status = 'approved' 
                     ORDER BY created_at DESC LIMIT 1";
$subscriptionStmt = $connect->prepare($subscriptionQuery);
$subscriptionStmt->bindParam(':user_id', $_SESSION['user_id']);
$subscriptionStmt->execute();
$hasSubscription = $subscriptionStmt->rowCount() > 0;
$subscriptionInfo = $hasSubscription ? $subscriptionStmt->fetch(PDO::FETCH_ASSOC) : null;
?>
<section class="subscribe">
    <div class="link-bar">
        <a href="?p=profile">
            <img src="./assets/media/images/profile/linkbar1.svg" alt="">
        </a>
        <a href="?p=subscribe">
            <img src="./assets/media/images/profile/linkbar2.svg" alt="">
        </a>
        <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
        <a href="?p=admin">
            <img src="./assets/media/images/profile/linkbar3.svg" alt="">
        </a>
        <?php endif; ?>
    </div>
    <div class="subscribe-page">
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
        
        <?php if($hasSubscription): ?>
            <h4>Управление подпиской</h4>
            <div class="subscription-info">
                <div class="subscription-status">
                    <div class="status-badge active">Активна</div>
                    <h5>Ваша подписка на "Кликон"</h5>
                    <p class="subscription-period">
                        Действует с: <?php echo date('d.m.Y', strtotime($subscriptionInfo['created_at'])); ?>
                    </p>
                    <div class="subscription-benefits">
                        <p>✓ Доступ ко всем фильмам и сериалам</p>
                        <p>✓ Просмотр в HD и Full HD качестве</p>
                        <p>✓ Скачивание фильмов для офлайн-просмотра</p>
                    </div>
                </div>
                <div class="subscription-actions">
                    <form action="incl/connect/cancel_subscription.php" method="POST">
                        <button type="submit" class="cancel-subscription-btn" onclick="return confirm('Вы действительно хотите отменить подписку?')">Отменить подписку</button>
                    </form>
                </div>
            </div>
        <?php elseif($hasActiveRequest): ?>
            <div class="pending-request">
                <p>У вас уже есть активный запрос на подписку. Ожидайте подтверждения от администратора.</p>
            </div>
        <?php else: ?>
            <h4>Оформление подписки</h4>
            <div class="subscription-plans">
                <div class="plan">
                    <h5>Стандартная подписка</h5>
                    <ul class="benefits">
                        <li>Доступ ко всем фильмам и сериалам</li>
                        <li>Просмотр в HD и Full HD качестве</li>
                        <li>Скачивание фильмов для офлайн-просмотра</li>
                    </ul>
                </div>
            </div>
            <div class="subscribe-form">
                <p>Для оформления подписки заполните форму и отправьте запрос администратору. После одобрения вы получите доступ к премиум-контенту.</p>
                <form action="incl/connect/subscribe_request.php" method="POST">
                    <div class="form-column">
                        <input type="text" name="name" id="name" placeholder="Имя" value="<?php echo $_SESSION['user_name']; ?>">
                        <input type="email" name="email" id="email" placeholder="E-mail" value="<?php echo $_SESSION['user_email']; ?>">
                        <input type="tel" name="phone" id="phone" placeholder="Контактный номер">
                    </div>
                    <div class="form-column">
                        <input type="text" name="card_number" id="card_number" placeholder="Номер карты" maxlength="19">
                        <input type="text" name="card_holder" id="card_holder" placeholder="Имя держателя карты">
                        <input type="text" name="cvv" id="cvv" placeholder="Код на обратной стороне (CVV)" maxlength="3">
                        <button type="submit" class="subscribe-btn">Отправить запрос на подписку</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
    .subscribe {
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

    .subscribe-page {
        padding-top: 30px;
        padding-bottom: 100px;
        padding-left: 50px;
        padding-right: 50px;
        width: 1314px;
        background-color: #191E2E;
        border-radius: 10px;
    }

    .subscribe-page>h4 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 30px;
        font-style: normal;
        font-weight: 900;
        line-height: normal;
    }

    .subscribe-form {
        margin-top: 30px;
    }

    .subscribe-form > p{
        color: #FFF;
        margin-bottom: 20px;
    }

    .subscribe-form form {
        display: flex;
        gap: 30px;
        width: 100%;
    }

    .form-column {
        display: flex;
        flex-direction: column;
        gap: 20px;
        flex: 1;
    }

    .form-column input {
        width: 100%;
        height: 54px;
        border-radius: 10px;
        background: #1E2538;
        color: rgba(255, 255, 255, 0.60);
        font-family: Qanelas;
        font-size: 17px;
        font-style: normal;
        font-weight: 400;
        line-height: 166.5%;
        padding-left: 26px;
        display: flex;
        align-items: center;
        border: none;
    }

    .subscribe-btn {
        width: 100%;
        height: 52px;
        border-radius: 10px;
        background: #3657CB;
        color: #FFF;
        font-family: Qanelas;
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .subscribe-btn:hover {
        background: #2A44A4;
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

    /* Стили для подписки */
    .subscription-plans {
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .pending-request>p{
        color: #fff;
        font-size: 18px;
    }

    .plan {
        background-color: #1E2538;
        border-radius: 10px;
        padding: 25px;
    }

    .plan h5 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .price {
        color: #F2F60F;
        font-family: Qanelas;
        font-size: 36px;
        font-weight: 900;
        margin-bottom: 20px;
    }

    .benefits {
        list-style-type: none;
        padding: 0;
    }

    .benefits li {
        color: #FFF;
        font-family: Qanelas;
        font-size: 16px;
        margin-bottom: 10px;
        padding-left: 30px;
        position: relative;
    }

    .benefits li:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: #4CAF50;
    }

    /* Стили для информации о подписке */
    .subscription-info {
        margin-top: 30px;
        display: flex;
        gap: 40px;
    }

    .subscription-status {
        flex: 1;
        background-color: #1E2538;
        border-radius: 10px;
        padding: 25px;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-family: Qanelas;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .status-badge.active {
        background-color: rgba(76, 175, 80, 0.2);
        color: #4CAF50;
        border: 1px solid #4CAF50;
    }

    .status-badge.cancelled {
        background-color: rgba(244, 67, 54, 0.2);
        color: #F44336;
        border: 1px solid #F44336;
    }

    .subscription-status h5 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .subscription-period {
        color: #F2F60F;
        font-family: Qanelas;
        font-size: 18px;
        margin-bottom: 20px;
    }

    .subscription-benefits {
        margin-top: 20px;
    }

    .subscription-benefits p {
        color: #FFF;
        font-family: Qanelas;
        font-size: 16px;
        margin-bottom: 10px;
    }

    .subscription-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .cancel-subscription-btn {
        display: inline-block;
        padding: 15px 30px;
        background-color: #F44336;
        color: #FFF;
        border: none;
        border-radius: 10px;
        font-family: Qanelas;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .cancel-subscription-btn:hover {
        background-color: #D32F2F;
    }

    .cancel-note {
        color: rgba(255, 255, 255, 0.6);
        font-family: Qanelas;
        font-size: 14px;
        max-width: 300px;
    }

    /* Медиа-запрос для устройств с шириной экрана 1024px */
    @media (max-width: 1024px) {
        .subscribe {
            max-width: 980px;
            flex-direction: row;
        }

        .subscribe-page {
            width: 860px;
            padding-left: 30px;
            padding-right: 30px;
        }

        .subscription-info {
            flex-direction: column;
        }

        .subscription-actions {
            align-items: center;
        }

        .cancel-note {
            text-align: center;
            max-width: 100%;
        }

        .subscribe-form form {
            flex-direction: column;
        }
    }

    /* Медиа-запрос для устройств с шириной экрана 390px (мобильные) */
    @media (max-width: 390px) {
        .subscribe {
            max-width: 370px;
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

        .link-bar>a img {
            width: 40px;
        }

        .subscribe-page {
            width: 100%;
            padding: 20px 15px 50px;
        }

        .subscribe-page>h4 {
            font-size: 24px;
            text-align: center;
        }

        .subscribe-form {
            flex-direction: column;
            gap: 15px;
        }

        .form-column>input {
            width: 100%;
            height: 50px;
            font-size: 16px;
        }

        .form-column>input[type="submit"] {
            width: 100%;
        }

        .plan h5 {
            font-size: 20px;
        }

        .price {
            font-size: 28px;
        }

        .benefits li {
            font-size: 14px;
        }

        .subscription-status h5 {
            font-size: 20px;
        }

        .subscription-period {
            font-size: 16px;
        }

        .cancel-subscription-btn {
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 768px) {
        .subscribe-form form {
            flex-direction: column;
        }
    }
</style>

<script>
// Форматирование номера карты с пробелами
document.addEventListener('DOMContentLoaded', function() {
    const cardNumberInput = document.getElementById('card_number');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            // Удаляем все нецифровые символы
            let value = this.value.replace(/\D/g, '');
            
            // Добавляем пробелы после каждых 4 цифр
            if (value.length > 0) {
                value = value.match(/.{1,4}/g).join(' ');
            }
            
            // Обновляем значение поля
            this.value = value;
        });
    }
});
</script>