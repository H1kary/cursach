    <!-- footer -->
    <footer>
        <div class="footer-block">
            <div class="footer-top">
                <div class="footer-logo">
                    <a href="?p=home"><img src="assets/media/images/logo.svg" alt=""></a>
                </div>
                <div class="footer-links">
                    <a href="#"><img src="assets/media/images/footer/vk.svg" alt=""></a>
                    <a href="#"><img src="assets/media/images/footer/wa.svg" alt=""></a>
                    <a href="#"><img src="assets/media/images/footer/tg.svg" alt=""></a>
                    <a href="#"><img src="assets/media/images/footer/ok.svg" alt=""></a>
                    <a href="#"><img src="assets/media/images/footer/yt.svg" alt=""></a>
                </div>
                <div class="footer-nav">
                    <a href="?p=catalog">Каталог</a>
                    <a href="?p=subscribe">Подписка</a>
                    <a href="?p=contacts">Контакты</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>2025 © Кликон. Белов Юрий Алексеевич</p>
                <a href="#">Политика конфиденциальности</a>
                <a href="#">Условия пользования</a>
                <a href="#">Политика использования файлов cookie</a>
            </div>
        </div>
    </footer>

    <style>
        footer {
            margin-top: 70px;
            padding-top: 100px;
            padding-bottom: 50px;
            background: #151A26;
        }

        .footer-block {
            max-width: 1430px;
            margin: 0 auto;
        }

        .footer-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-logo {
            width: 337px;
        }

        .footer-links {
            display: flex;
            gap: 15px;
        }

        .footer-links a img {
            height: 23px;
        }

        .footer-links a img:first-of-type {
            width: 23px;
        }

        .footer-links a img:nth-of-type(2) {
            width: 18px;
        }

        .footer-links a img:nth-of-type(3) {
            width: 20px;
        }

        .footer-links a img:nth-of-type(4) {
            width: 13px;
        }

        .footer-links a img:last-of-type {
            width: 23px;
        }

        .footer-nav {
            display: flex;
            gap: 64px;
        }

        .footer-nav a {
            color: #3C4767;
            font-family: Qanelas;
            font-size: 17px;
            font-style: normal;
            font-weight: 700;
            line-height: normal;
        }

        .footer-bottom {
            display: flex;
            max-width: fit-content;
            margin: 0 auto;
            margin-top: 93px;
            gap: 24px;
        }

        .footer-bottom p {
            color: rgba(227, 230, 240, 0.72);
            text-align: center;
            font-family: Qanelas;
            font-size: 15px;
            font-style: normal;
            font-weight: 400;
            line-height: 119%;
            /* 17.85px */
        }

        .footer-bottom a {
            color: rgba(227, 230, 240, 0.72);
            text-align: center;
            font-family: Qanelas;
            font-size: 15px;
            font-style: normal;
            font-weight: 400;
            line-height: 119%;
            /* 17.85px */
            text-decoration-line: underline;
            text-decoration-style: solid;
            text-decoration-skip-ink: none;
            text-decoration-thickness: auto;
            text-underline-offset: auto;
            text-underline-position: from-font;
        }

        @media screen and (max-width: 1024px) {
            .footer-top {
                max-width: 768px;
                margin: 0 auto;
                display: flex;
                flex-direction: column;
                gap: 30px;
            }

            .footer-logo {
                width: fit-content;
            }

            .footer-bottom {
                flex-direction: column;

            }
        }
    </style>
    <!-- footer-end -->