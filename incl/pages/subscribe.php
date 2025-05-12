<section class="subscribe">
    <div class="link-bar">
        <a href="?p=profile">
            <img src="./assets/media/images/profile/linkbar1.svg" alt="">
        </a>
        <a href="?p=subscribe">
            <img src="./assets/media/images/profile/linkbar2.svg" alt="">
        </a>
        <a href="?p=admin">
            <img src="./assets/media/images/profile/linkbar3.svg" alt="">
        </a>
    </div>
    <div class="subscribe-page">
        <h4>Оформление подписки</h4>
        <div class="subscribe-form">
            <div class="form-column">
                <input type="text" name="" id="" placeholder="Имя">
                <input type="email" name="" id="" placeholder="E-mail">
                <input type="tel" name="" id="" placeholder="Контактный номер">
            </div>
            <div class="form-column">
                <input type="text" name="" id="" placeholder="Номер карты">
                <input type="text" name="" id="" placeholder="Имя держателя карты">
                <input type="text" name="" id="" placeholder="Код на обратной стороне (CVV)">
                <input type="submit" value="Перейти к оплате">
            </div>
        </div>
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
        display: flex;
        gap: 30px;
    }

    .form-column {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-column>input {
        width: 478px;
        height: 54px;
        border-radius: 10px;
        background: #1E2538;
        color: rgba(255, 255, 255, 0.60);
        font-family: Qanelas;
        font-size: 17px;
        font-style: normal;
        font-weight: 400;
        line-height: 166.5%;
        /* 28.305px */
        padding-left: 26px;
        display: flex;
        align-items: center;
        border: none;
    }

    .form-column>input[type="submit"] {
        width: 192px;
        height: 52px;
        border-radius: 10px;
        background: #3657CB;
        color: #FFF;
        font-family: Qanelas;
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
        padding: 0;
        cursor: pointer;
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

        .form-column>input {
            width: 380px;
        }

        .subscribe-form {
            gap: 20px;
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
    }
</style>