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
            <a href="?page=profile">
                <img src="./assets/media/images/header/dropdown.svg" alt="">
                <p>Юрий</p>
                <img src="./assets/media/images/header/avatar.png" alt="">
            </a>
        </div> -->
        <div class="header-buttons">
            <a href="#">Регистрация</a>
            <a href="#">Войти</a>
        </div>
        <div class="header-burger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </header>

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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const headerBurger = document.querySelector('.header-burger');
            const headerNav = document.querySelector('.header-nav');
            const headerButtons = document.querySelector('.header-buttons');
            const headerUser = document.querySelector('.header-user');
            const body = document.querySelector('body');

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