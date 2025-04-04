    <!-- header-start -->
    <header>
        <div class="header-logo">
            <a href="index.html"><img src="assets/media/images/logo.svg" alt=""></a>
        </div>
        <nav class="header-nav">
            <a href="#">Каталог</a>
            <a href="#">Подписка</a>
            <a href="#">Контакты</a>
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
    </header>

    <style>
        header {
            max-width: 1430px;
            margin: 0 auto;
            margin-top: 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
    </style>
    <!-- header-end -->