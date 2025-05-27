    <!-- header-start -->
    <header>
        <?php if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1): ?>
        <div class="header-logo">
            <a href="?p=home"><img src="assets/media/images/logo.svg" alt=""></a>
        </div>
        <?php endif; ?>
        <nav class="header-nav">
            <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                <a href="?p=profile">Профиль</a>
                <a href="?p=admin">Админ-панель</a>
            <?php else: ?>
                <a href="?p=home">Главная</a>
                <a href="?p=catalog">Фильмы</a>
                <a href="?p=contacts">Контакты</a>
            <?php endif; ?>
        </nav>
        <?php if(isset($_SESSION['user_id'])): ?>
        <div class="header-user">
            <a href="?p=profile">
                <p><?php echo $_SESSION['user_name']; ?></p>
                <?php 
                // Проверяем наличие аватара пользователя
                if(isset($_SESSION['user_avatar']) && !empty($_SESSION['user_avatar']) && $_SESSION['user_avatar'] != 'default.jpg') {
                    $avatar_path = './incl/userprofiles/' . $_SESSION['user_avatar'];
                    // Дополнительная проверка существования файла
                    if(!file_exists($avatar_path)) {
                        $avatar_path = './assets/media/images/profile/default-avatar.png';
                    }
                } else {
                    $avatar_path = './assets/media/images/profile/default-avatar.png';
                }
                ?>
                <img src="<?php echo $avatar_path; ?>" alt="Аватар пользователя" class="user-avatar">
            </a>
        </div>
        <?php else: ?>
        <div class="header-buttons">
            <a href="#" id="register-btn">Регистрация</a>
            <a href="#" id="login-btn">Войти</a>
        </div>
        <?php endif; ?>
        <div class="header-burger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </header>

    <?php if(isset($_SESSION['error'])): ?>
    <div class="error-message">
        <?php 
        echo $_SESSION['error'];
        unset($_SESSION['error']);
        ?>
    </div>
    <?php endif; ?>

    <div class="modal-overlay"></div>
    <div class="login-modal">
        <div>
            <form action="incl/connect/login.php" method="POST">
                <h4>Авторизация</h4>
                <input type="email" name="email" id="login_email" placeholder="E-mail" required>
                <input type="password" name="password" id="login_password" placeholder="Пароль" required>
                <div>
                    <input type="submit" value="Войти">
                    <a href="#" id="go-to-register">Нет аккаунта? <span>Регистрация</span></a>
                </div>
            </form>
            <img id="close-login" src="./assets/media/images/header/close.svg" alt="">
        </div>
    </div>
    <div class="registration-modal">
        <div>
            <form action="incl/connect/register.php" method="POST">
                <h4>Регистрация</h4>
                <input type="email" name="email" id="registration_email" placeholder="E-mail" required>
                <input type="text" name="name" id="registration_name" placeholder="Имя" required>
                <input type="password" name="password" id="registration_password" placeholder="Пароль" required>
                <input type="password" name="password_check" id="registration_password_check" placeholder="Повтор Пароля" required>
                <div>
                    <input type="submit" value="Зарегистрироваться">
                    <a href="#" id="go-to-login">Уже есть аккаунт? <span>Войти</span></a>
                </div>
            </form>
            <img id="close-registration" src="./assets/media/images/header/close.svg" alt="">
        </div>
    </div>

    <style>
        header {
            max-width: 1430px;
            margin: 0 auto;
            margin-top: 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .header-logo {
            position: relative;
            z-index: 999;
        }

        .header-logo img {
            width: 197px;
            height: 32px;
        }

        .header-nav {
            display: flex;
            gap: 64px;
        }

        .header-nav a {
            color: #FFF;
            font-family: 'Qanelas';
            font-size: 17px;
            font-style: normal;
            font-weight: 700;
            line-height: normal;
        }

        .header-buttons {
            width: 334px;
            display: flex;
            gap: 12px;
        }

        .header-buttons a:first-of-type {
            color: #3657CB;
            font-family: Qanelas;
            font-size: 16px;
            font-style: normal;
            font-weight: 700;
            line-height: 166.5%;
            /* 26.64px */
            padding: 13px 46px;
            border-radius: 10px;
            background: #FFF;
        }

        .header-buttons a:last-of-type {
            color: #FFF;
            font-family: Qanelas;
            font-size: 16px;
            font-style: normal;
            font-weight: 700;
            line-height: 166.5%;
            /* 26.64px */
            padding: 13px 46px;
            border-radius: 10px;
            background: #3657CB;
            box-shadow: 0px 0px 15px 0px rgba(72, 113, 255, 0.80);
        }

        .header-user a {
            width: 334px;
            justify-content: flex-end;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-user a p {
            color: #FFF;
            font-family: Qanelas;
            font-size: 15px;
            font-style: normal;
            font-weight: 600;
            line-height: 166.5%;
            /* 24.975px */
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #3657CB;
        }

        .header-burger {
            display: none;
            width: 30px;
            height: 20px;
            position: relative;
            cursor: pointer;
            z-index: 5;
        }

        .header-burger span {
            position: absolute;
            background-color: #FFF;
            left: 0;
            width: 100%;
            height: 2px;
            top: 9px;
            transition: all 0.3s ease;
        }

        .header-burger span:first-child {
            top: 0;
        }

        .header-burger span:last-child {
            top: auto;
            bottom: 0;
        }

        .header-burger.active span {
            transform: scale(0);
        }

        .header-burger.active span:first-child {
            transform: rotate(-45deg);
            top: 9px;
        }

        .header-burger.active span:last-child {
            transform: rotate(45deg);
            bottom: 9px;
        }

        @media (max-width: 1024px) {
            header {
                max-width: 80%;
            }

            .header-nav {
                position: fixed;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background-color: #111;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 30px;
                z-index: 3;
                transition: all 0.3s ease;
                padding: 100px 0;
            }

            .header-nav.active {
                left: 0;
            }

            .header-nav.active a {
                font-size: 24px;
            }

            .header-buttons {
                position: fixed;
                left: -100%;
                bottom: 150px;
                width: 100%;
                flex-direction: column;
                align-items: center;
                z-index: 3;
                transition: all 0.3s ease;
            }

            .header-buttons.active {
                left: 0;
            }

            .header-buttons a:first-of-type,
            .header-buttons a:last-of-type {
                width: 80%;
                text-align: center;
            }

            .header-user {
                position: fixed;
                left: -100%;
                bottom: 250px;
                width: 100%;
                z-index: 3;
                transition: all 0.3s ease;
                display: flex;
                justify-content: center;
            }

            .header-user.active {
                left: 0;
            }

            .header-burger {
                display: block;
            }

            body.lock {
                overflow: hidden;
            }
        }

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

        .login-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translateX(-50%) translateY(-50%);
            z-index: 999;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .login-modal.active {
            opacity: 1;
        }

        .login-modal>div {
            position: relative;
            padding: 200px;
            border-radius: 10px;
            background: #191E2E;
        }

        #close-login {
            position: absolute;
            top: 40px;
            right: 40px;
            cursor: pointer;
        }

        .login-modal form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .login-modal form h4 {
            color: #FFF;
            font-family: Qanelas;
            font-size: 30px;
            font-style: normal;
            font-weight: 900;
            line-height: normal;
        }

        .login-modal form>input:first-of-type {
            width: 478px;
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
            /* 28.305px */
            border: none;
        }

        .login-modal form>input:nth-of-type(2) {
            width: 478px;
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
            /* 28.305px */
            border: none;
        }

        .login-modal form>div {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .login-modal form>div>input[type="submit"] {
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
        }

        .login-modal form>div>a {
            color: #E0E0E0;
            font-family: Qanelas;
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
        }

        .login-modal form>div>a span {
            color: #F2F60F;
            font-family: Qanelas;
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
        }

        .registration-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translateX(-50%) translateY(-50%);
            z-index: 999;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .registration-modal.active {
            opacity: 1;
        }

        .registration-modal>div {
            position: relative;
            padding: 200px;
            border-radius: 10px;
            background: #191E2E;
        }

        #close-registration {
            position: absolute;
            top: 40px;
            right: 40px;
            cursor: pointer;
        }

        .registration-modal form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .registration-modal form h4 {
            color: #FFF;
            font-family: Qanelas;
            font-size: 30px;
            font-style: normal;
            font-weight: 900;
            line-height: normal;
        }

        .registration-modal form>input:first-of-type {
            width: 478px;
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
            /* 28.305px */
            border: none;
        }

        .registration-modal form>input:nth-of-type(2) {
            width: 478px;
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
            /* 28.305px */
            border: none;
        }

        .registration-modal form>input:nth-of-type(3) {
            width: 478px;
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
            /* 28.305px */
            border: none;
        }

        .registration-modal form>input:nth-of-type(4) {
            width: 478px;
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
            /* 28.305px */
            border: none;
        }

        .registration-modal form>div {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .registration-modal form>div>input[type="submit"] {
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
        }

        .registration-modal form>div>a {
            color: #E0E0E0;
            font-family: Qanelas;
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
        }

        .registration-modal form>div>a span {
            color: #F2F60F;
            font-family: Qanelas;
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
        }

        @media (max-width: 390px) {

            .login-modal>div,
            .registration-modal>div {
                margin: 0 auto;
                padding: 40px 20px;
                width: 370px;
                max-width: 390px;
            }

            .login-modal form>input:first-of-type,
            .login-modal form>input:nth-of-type(2),
            .registration-modal form>input:first-of-type,
            .registration-modal form>input:nth-of-type(2),
            .registration-modal form>input:nth-of-type(3),
            .registration-modal form>input:nth-of-type(4) {
                width: 100%;
                box-sizing: border-box;
            }

            .login-modal form>div,
            .registration-modal form>div {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .login-modal form h4,
            .registration-modal form h4 {
                font-size: 24px;
                text-align: center;
            }

            #close-login,
            #close-registration {
                top: 10px;
                right: 10px;
            }

            .login-modal {
                width: 100% !important;
                max-width: 100% !important;
            }
        }

        .error-message {
            max-width: 1430px;
            margin: 10px auto;
            padding: 10px;
            background-color: rgba(255, 0, 0, 0.2);
            border-radius: 5px;
            color: #fff;
            text-align: center;
            font-family: 'Qanelas';
            font-weight: 600;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const headerBurger = document.querySelector('.header-burger');
            const headerNav = document.querySelector('.header-nav');
            const headerButtons = document.querySelector('.header-buttons');
            const headerUser = document.querySelector('.header-user');
            const body = document.querySelector('body');

            // Модальные окна
            const modalOverlay = document.querySelector('.modal-overlay');
            const loginModal = document.querySelector('.login-modal');
            const registrationModal = document.querySelector('.registration-modal');
            const loginBtn = document.getElementById('login-btn');
            const registerBtn = document.getElementById('register-btn');
            const closeLogin = document.getElementById('close-login');
            const closeRegistration = document.getElementById('close-registration');
            const goToRegister = document.getElementById('go-to-register');
            const goToLogin = document.getElementById('go-to-login');

            // Функция открытия модального окна
            function openModal(modal) {
                modalOverlay.style.display = 'block';
                modal.style.display = 'block';
                body.classList.add('lock');

                // Задержка для анимации
                setTimeout(() => {
                    modalOverlay.classList.add('active');
                    modal.classList.add('active');
                }, 10);
            }

            // Функция закрытия модального окна
            function closeModal(modal) {
                modalOverlay.classList.remove('active');
                modal.classList.remove('active');

                // Задержка для анимации
                setTimeout(() => {
                    modalOverlay.style.display = 'none';
                    modal.style.display = 'none';
                    body.classList.remove('lock');
                }, 300);
            }

            // Открытие модального окна авторизации
            loginBtn.addEventListener('click', (e) => {
                e.preventDefault();
                openModal(loginModal);
            });

            // Открытие модального окна регистрации
            registerBtn.addEventListener('click', (e) => {
                e.preventDefault();
                openModal(registrationModal);
            });

            // Закрытие модального окна авторизации
            closeLogin.addEventListener('click', () => {
                closeModal(loginModal);
            });

            // Закрытие модального окна регистрации
            closeRegistration.addEventListener('click', () => {
                closeModal(registrationModal);
            });

            // Переход с авторизации на регистрацию
            goToRegister.addEventListener('click', (e) => {
                e.preventDefault();
                closeModal(loginModal);
                setTimeout(() => {
                    openModal(registrationModal);
                }, 300);
            });

            // Переход с регистрации на авторизацию
            goToLogin.addEventListener('click', (e) => {
                e.preventDefault();
                closeModal(registrationModal);
                setTimeout(() => {
                    openModal(loginModal);
                }, 300);
            });

            // Закрытие модальных окон при клике вне их области
            modalOverlay.addEventListener('click', () => {
                if (loginModal.classList.contains('active')) {
                    closeModal(loginModal);
                }
                if (registrationModal.classList.contains('active')) {
                    closeModal(registrationModal);
                }
            });

            headerBurger.addEventListener('click', () => {
                headerBurger.classList.toggle('active');
                headerNav.classList.toggle('active');
                headerButtons.classList.toggle('active');
                if (headerUser) {
                    headerUser.classList.toggle('active');
                }
                body.classList.toggle('lock');
            });

            // Закрытие меню при клике на ссылку
            const menuLinks = document.querySelectorAll('.header-nav a, .header-buttons a');
            menuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    headerBurger.classList.remove('active');
                    headerNav.classList.remove('active');
                    headerButtons.classList.remove('active');
                    if (headerUser) {
                        headerUser.classList.remove('active');
                    }
                    body.classList.remove('lock');
                });
            });
            
            // Проверяем наличие параметра action в URL для открытия соответствующего модального окна
            const urlParams = new URLSearchParams(window.location.search);
            const action = urlParams.get('action');
            
            if (action === 'login') {
                openModal(loginModal);
            } else if (action === 'register') {
                openModal(registrationModal);
            }
        });

        // Валидация формы регистрации
        document.addEventListener('DOMContentLoaded', function() {
            const registrationForm = document.querySelector('.registration-modal form');
            const loginForm = document.querySelector('.login-modal form');
            
            if (registrationForm) {
                registrationForm.addEventListener('submit', function(e) {
                    const nameInput = document.getElementById('registration_name');
                    const passwordInput = document.getElementById('registration_password');
                    const passwordCheckInput = document.getElementById('registration_password_check');
                    const emailInput = document.getElementById('registration_email');
                    
                    let isValid = true;
                    let errorMessage = '';
                    
                    // Проверка имени (минимум 3 символа)
                    if (nameInput.value.trim().length < 3) {
                        errorMessage = 'Имя должно содержать минимум 3 символа';
                        isValid = false;
                    }
                    
                    // Проверка пароля (английский язык, минимум одна заглавная буква и одна цифра)
                    const passwordRegex = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,}$/;
                    if (!passwordRegex.test(passwordInput.value)) {
                        errorMessage = 'Пароль должен содержать минимум 6 символов, только английские буквы, минимум одну заглавную букву и одну цифру';
                        isValid = false;
                    }
                    
                    // Проверка совпадения паролей
                    if (passwordInput.value !== passwordCheckInput.value) {
                        errorMessage = 'Пароли не совпадают';
                        isValid = false;
                    }
                    
                    // Проверка email
                    if (emailInput.value.trim() === '') {
                        errorMessage = 'Email не может быть пустым';
                        isValid = false;
                    }
                    
                    if (!isValid) {
                        e.preventDefault();
                        // Создаем временное сообщение об ошибке
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'form-error-message';
                        errorDiv.textContent = errorMessage;
                        errorDiv.style.color = '#ff6b6b';
                        errorDiv.style.fontSize = '14px';
                        errorDiv.style.marginTop = '10px';
                        errorDiv.style.marginBottom = '10px';
                        errorDiv.style.fontFamily = 'Qanelas';
                        
                        // Удаляем предыдущее сообщение об ошибке, если оно есть
                        const existingError = registrationForm.querySelector('.form-error-message');
                        if (existingError) {
                            existingError.remove();
                        }
                        
                        // Добавляем сообщение об ошибке перед кнопкой отправки
                        registrationForm.insertBefore(errorDiv, registrationForm.querySelector('div'));
                    }
                });
            }
            
            // Валидация формы авторизации
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const emailInput = document.getElementById('login_email');
                    const passwordInput = document.getElementById('login_password');
                    
                    let isValid = true;
                    let errorMessage = '';
                    
                    // Проверка email
                    if (emailInput.value.trim() === '') {
                        errorMessage = 'Email не может быть пустым';
                        isValid = false;
                    }
                    
                    // Проверка пароля
                    if (passwordInput.value.trim() === '') {
                        errorMessage = 'Пароль не может быть пустым';
                        isValid = false;
                    }
                    
                    if (!isValid) {
                        e.preventDefault();
                        // Создаем временное сообщение об ошибке
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'form-error-message';
                        errorDiv.textContent = errorMessage;
                        errorDiv.style.color = '#ff6b6b';
                        errorDiv.style.fontSize = '14px';
                        errorDiv.style.marginTop = '10px';
                        errorDiv.style.marginBottom = '10px';
                        errorDiv.style.fontFamily = 'Qanelas';
                        
                        // Удаляем предыдущее сообщение об ошибке, если оно есть
                        const existingError = loginForm.querySelector('.form-error-message');
                        if (existingError) {
                            existingError.remove();
                        }
                        
                        // Добавляем сообщение об ошибке перед кнопкой отправки
                        loginForm.insertBefore(errorDiv, loginForm.querySelector('div'));
                    }
                });
            }
        });
    </script>
    <!-- header-end -->