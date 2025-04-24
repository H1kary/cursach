    <!-- header-start -->
    <header>
        <div class="header-logo">
            <a href="?p=home"><img src="assets/media/images/logo.svg" alt=""></a>
        </div>
        <nav class="header-nav">
            <a href="?p=catalog">Каталог</a>
            <a href="?p=subscribe">Подписка</a>
            <a href="?p=contacts">Контакты</a>
        </nav>
        <!-- <div class="header-user">
            <a href="?p=profile">
                <img src="./assets/media/images/header/dropdown.svg" alt="">
                <p>Юрий</p>
                <img src="./assets/media/images/header/avatar.png" alt="">
            </a>
        </div> -->
        <div class="header-buttons">
            <a href="#" id="register-btn">Регистрация</a>
            <a href="#" id="login-btn">Войти</a>
        </div>
        <div class="header-burger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </header>

    <div class="modal-overlay"></div>
    <div class="login-modal">
        <div>
            <form action="">
                <h4>Авторизация</h4>
                <input type="email" name="" id="login_email" placeholder="E-mail">
                <input type="password" name="" id="login_password" placeholder="Пароль">
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
            <form action="">
                <h4>Регистрация</h4>
                <input type="email" name="" id="registration_email" placeholder="E-mail">
                <input type="text" name="" id="registration_name" placeholder="Имя">
                <input type="password" name="" id="registration_password" placeholder="Пароль">
                <input type="password" name="" id="registration_password_check" placeholder="Повтор Пароля">
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
        });
    </script>
    <!-- header-end -->