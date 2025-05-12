<section class="contacts">
    <h1>Обратная связь</h1>
    <div class="contacts-socials">
        <a href="#" class="contacts-social">
            <img src="./assets/media/images/contacts/vk.svg" alt="">
            <p>ВКонтакте</p>
            <p>vk.com/clickon</p>
        </a>
        <a href="#" class="contacts-social">
            <img src="./assets/media/images/contacts/wa.svg" alt="">
            <p>WhatsApp</p>
            <p>@clickon</p>
        </a>
        <a href="#" class="contacts-social">
            <img src="./assets/media/images/contacts/tg.svg" alt="">
            <p>Telegram</p>
            <p>t.me/clickon</p>
        </a>
        <a href="#" class="contacts-social">
            <img src="./assets/media/images/contacts/ok.svg" alt="">
            <p>Odnoklassniki</p>
            <p>ok.ru/clickon</p>
        </a>
        <a href="#" class="contacts-social">
            <img src="./assets/media/images/contacts/email.svg" alt="">
            <p>E-mail</p>
            <p>direct@clickon.com</p>
        </a>
    </div>
</section>

<style>
    .contacts {
        max-width: 1430px;
        margin: 0 auto;
        margin-top: 30px;
    }

    .contacts>h1 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 65px;
        font-style: normal;
        font-weight: 900;
        line-height: normal;
    }

    .contacts-socials {
        margin-top: 30px;
        display: flex;
        justify-content: space-between;
    }

    .contacts-social {
        width: 271px;
        height: 271px;
        border-radius: 10px;
        background: #191E2E;
        position: relative;
    }

    .contacts-social>img {
        position: absolute;
        top: 100px;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .contacts-social>p:first-of-type {
        position: absolute;
        bottom: 96px;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #FFF;
        font-family: Qanelas;
        font-size: 18px;
        font-style: normal;
        font-weight: 600;
        line-height: normal;
    }

    .contacts-social>p:last-of-type {
        position: absolute;
        bottom: 24px;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #F2F60F;
        font-family: Qanelas;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }
    
    /* Медиа-запрос для устройств с шириной экрана 1024px */
    @media (max-width: 1024px) {
        .contacts {
            max-width: 980px;
            padding: 0 20px;
        }
        
        .contacts>h1 {
            font-size: 50px;
        }
        
        .contacts-socials {
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        
        .contacts-social {
            width: 220px;
            height: 220px;
        }
        
        .contacts-social>img {
            top: 80px;
            width: 60px;
        }
        
        .contacts-social>p:first-of-type {
            bottom: 85px;
            font-size: 16px;
        }
        
        .contacts-social>p:last-of-type {
            bottom: 20px;
            font-size: 14px;
        }
    }
    
    /* Медиа-запрос для устройств с шириной экрана 390px (мобильные) */
    @media (max-width: 390px) {
        .contacts {
            max-width: 370px;
            padding: 0 10px;
            margin-top: 20px;
        }
        
        .contacts>h1 {
            font-size: 36px;
            text-align: center;
        }
        
        .contacts-socials {
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin-top: 20px;
        }
        
        .contacts-social {
            width: 100%;
            max-width: 350px;
            height: 190px;
        }
        
        .contacts-social>img {
            top: 70px;
            width: 50px;
        }
        
        .contacts-social>p:first-of-type {
            bottom: 70px;
            font-size: 16px;
        }
        
        .contacts-social>p:last-of-type {
            bottom: 15px;
            font-size: 14px;
        }
    }
</style>