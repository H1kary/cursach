<section class="film-page">
    <div class="film-page__main">
        <img src="./assets/media/images/film-page/film1.png" alt="" class="film-poster">
        <div class="film-info">
            <h2>Побег из Претории</h2>
            <h4>Escape from Pretoria</h4>
            <p>Двое борцов за свободу отбывают срок в одной из самых строгих тюрем мира — в «Претории». Вместе с другими узниками они планируют дерзкий и опасный побег. Но придумать план — это только первый шаг. Шаг второй — реализация плана.</p>
            <a href="#"><img src="./assets/media/images/film-page/play-button.svg" alt="">Смотреть</a>
        </div>
    </div>
    <div class="film-page__info">
        <div class="film-page__info-names">
            <p>Год:</p>
            <p>Страна:</p>
            <p>Слоган:</p>
            <p>Режиссер:</p>
        </div>
        <div class="film-page__info-info">
            <p>2020</p>
            <p>Великобритания, Австралия</p>
            <p>«Подбери ключ к свободе»</p>
            <p>Фрэнсис Аннан</p>
        </div>
    </div>
</section>

<style>
    .film-page {
        margin: 0 auto;
        margin-top: 35px;
        max-width: 1430px;
    }

    .film-page__main {
        display: flex;
        align-items: center;
    }

    .film-page__main>div {
        margin-left: 54px;
    }

    .film-page__main>div>h2 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 65px;
        font-style: normal;
        font-weight: 900;
        line-height: normal;
    }

    .film-page__main>div>h4 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 25px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }

    .film-page__main>div>p {
        margin-top: 95px;
        color: #FFF;
        font-family: Qanelas;
        font-size: 20px;
        font-style: normal;
        font-weight: 500;
        line-height: 166.5%;
        /* 33.3px */
    }

    .film-page__main>div>a {
        display: flex;
        gap: 12px;
        align-items: center;
        max-width: fit-content;
        margin-top: 30px;
        border-radius: 10px;
        border: 2px solid #FFF;
        color: #FFF;
        font-family: Qanelas;
        font-size: 18px;
        font-style: normal;
        font-weight: 700;
        line-height: 166.5%;
        padding: 20px 36px;
        /* 29.97px */
    }

    .film-page__info {
        margin-top: 130px;
        display: flex;
        gap: 64px;
    }

    .film-page__info-names {
        display: flex;
        flex-direction: column;
        gap: 12px;
        color: rgba(255, 255, 255, 0.90);
        font-family: Qanelas;
        font-size: 18px;
        font-style: normal;
        font-weight: 600;
        line-height: normal;
    }

    .film-page__info-info {
        display: flex;
        flex-direction: column;
        gap: 12px;
        color: #F2F60F;
        font-family: Qanelas;
        font-size: 18px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        text-decoration-line: underline;
        text-decoration-style: solid;
        text-decoration-skip-ink: none;
        text-decoration-thickness: auto;
        text-underline-offset: auto;
        text-underline-position: from-font;
    }
    
    /* Медиа-запрос для устройств с шириной экрана 1024px */
    @media (max-width: 1024px) {
        .film-page {
            max-width: 980px;
            padding: 0 20px;
        }
        
        .film-page__main .film-poster {
            max-width: 450px;
        }
        
        .film-page__main>div>h2 {
            font-size: 50px;
        }
        
        .film-page__main>div>h4 {
            font-size: 22px;
        }
        
        .film-page__main>div>p {
            margin-top: 50px;
            font-size: 18px;
        }
        
        .film-page__info {
            margin-top: 80px;
            gap: 30px;
        }
    }
    
    /* Медиа-запрос для устройств с шириной экрана 390px (мобильные) */
    @media (max-width: 390px) {
        .film-page {
            max-width: 370px;
            padding: 0 10px;
            margin-top: 20px;
        }
        
        .film-page__main {
            flex-direction: column;
            align-items: center;
        }
        
        .film-page__main .film-poster {
            max-width: 100%;
        }
        
        .film-page__main>div {
            margin-left: 0;
            margin-top: 20px;
            text-align: center;
        }
        
        .film-page__main>div>h2 {
            font-size: 32px;
        }
        
        .film-page__main>div>h4 {
            font-size: 18px;
        }
        
        .film-page__main>div>p {
            margin-top: 30px;
            font-size: 16px;
            text-align: left;
        }
        
        .film-page__main>div>a {
            margin: 25px auto 0;
            padding: 15px 30px;
            font-size: 16px;
        }
        
        .film-page__info {
            margin-top: 50px;
            gap: 20px;
        }
        
        .film-page__info-names, 
        .film-page__info-info {
            font-size: 16px;
        }
    }
</style>