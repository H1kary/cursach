<section class="profile">
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
    <div class="profile-page">
        <h3>Ваш профиль</h3>
        <div class="profile-info">
            <img src="./assets/media/images/profile/profileimg.png" alt="" class="profile-avatar">
            <div class="profile-data">
                <h2>Юрий Белов</h2>
                <div class="profile-email">
                    <p>Почта:</p>
                    <p>babylya03@yandex.ru</p>
                </div>
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
        padding-top: 30px;
        padding-bottom: 100px;
        padding-left: 50px;
        background: #191E2E;
        border-radius: 10px;
        position: relative;
        width: 1314px;
    }

    .profile-page>h3 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 30px;
        font-style: normal;
        font-weight: 900;
        line-height: normal;
    }

    .profile-info {
        margin-top: 30px;
        display: flex;
        gap: 48px;
    }

    .profile-info>div {
        margin-top: 40px;
    }

    .profile-info>div>h2 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 50px;
        font-style: normal;
        font-weight: 900;
        line-height: normal;
    }

    .profile-info>div>div {
        margin-top: 20px;
        display: flex;
        gap: 64px;
    }

    .profile-info>div>div>p:first-of-type {
        color: rgba(255, 255, 255, 0.80);
        font-family: Qanelas;
        font-size: 18px;
        font-style: normal;
        font-weight: 600;
        line-height: normal;
    }

    .profile-info>div>div>p:last-of-type {
        color: #F2F60F;
        font-family: Qanelas;
        font-size: 18px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }
    
    /* Медиа-запрос для устройств с шириной экрана 1024px */
    @media (max-width: 1024px) {
        .profile {
            max-width: 980px;
            gap: 7px;
        }
        
        .profile-page {
            width: 860px;
            padding-left: 30px;
            padding-right: 30px;
            padding-bottom: 70px;
        }
        
        .profile-info {
            gap: 30px;
        }
        
        .profile-avatar {
            max-width: 180px;
        }
        
        .profile-info>div>h2 {
            font-size: 40px;
        }
        
        .profile-info>div>div {
            gap: 40px;
        }
        
        .profile-info>div>div>p:first-of-type,
        .profile-info>div>div>p:last-of-type {
            font-size: 16px;
        }
    }
    
    /* Медиа-запрос для устройств с шириной экрана 390px (мобильные) */
    @media (max-width: 390px) {
        .profile {
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
        
        .profile-page {
            width: 100%;
            padding: 20px 15px 50px;
        }
        
        .profile-page>h3 {
            font-size: 24px;
            text-align: center;
        }
        
        .profile-info {
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 15px;
        }
        
        .profile-avatar {
            max-width: 150px;
        }
        
        .profile-info>div {
            margin-top: 0;
        }
        
        .profile-info>div>h2 {
            font-size: 30px;
        }
        
        .profile-info>div>div {
            flex-direction: column;
            gap: 5px;
            margin-top: 15px;
        }
        
        .profile-info>div>div>p:first-of-type,
        .profile-info>div>div>p:last-of-type {
            font-size: 16px;
        }
    }
</style>