<section class="catalog">
    <div class="catalog-head">
        <h1>Каталог</h1>
        <div class="catalog-head__sort">
            <a href="#">Фильмы</a>
            <a href="#">Сериалы</a>
            <a href="#">Документалистика</a>
            <a href="#">Мультфильмы</a>
        </div>
    </div>
    <div class="catalog-items">
        <div class="catalog-item">
            <img src="./assets/media/images/catalog/catalog1.png" alt="">
            <a href="#">Черное море</a>
        </div>
        <div class="catalog-item">
            <img src="./assets/media/images/catalog/catalog2.png" alt="">
            <a href="#">Отель у озера</a>
        </div>
        <div class="catalog-item">
            <img src="./assets/media/images/catalog/catalog3.png" alt="">
            <a href="#">Тень звезды</a>
        </div>
        <div class="catalog-item">
            <img src="./assets/media/images/catalog/catalog4.png" alt="">
            <a href="#">Оторви и выбрось</a>
        </div>
        <div class="catalog-item">
            <img src="./assets/media/images/catalog/catalog5.png" alt="">
            <a href="#">Космические войска </a>
        </div>
        <div class="catalog-item">
            <img src="./assets/media/images/catalog/catalog6.png" alt="">
            <a href="#">Алеф</a>
        </div>
        <div class="catalog-item">
            <img src="./assets/media/images/catalog/catalog7.png" alt="">
            <a href="#">Phobias</a>
        </div>
        <div class="catalog-item">
            <img src="./assets/media/images/catalog/catalog8.png" alt="">
            <a href="#">Random Shit</a>
        </div>
        <div class="catalog-item">
            <img src="./assets/media/images/catalog/catalog9.png" alt="">
            <a href="#">Believe In</a>
        </div>
    </div>
</section>

<style>
    .catalog {
        max-width: 1430px;
        margin: 0 auto;
        margin-top: 30px;
    }

    .catalog-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .catalog-head h1 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 65px;
        font-style: normal;
        font-weight: 900;
        line-height: normal;
    }

    .catalog-head__sort {
        display: flex;
        gap: 16px;
    }

    .catalog-head__sort a {
        color: rgba(255, 255, 255, 0.35);
        font-family: Qanelas;
        font-size: 17px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .catalog-items {
        margin-top: 64px;
        display: flex;
        gap: 23px;
        flex-wrap: wrap;
    }

    .catalog-item {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .catalog-item img {
        cursor: pointer;
    }

    .catalog-item a {
        max-width: fit-content;
        color: #FFF;
        font-family: Qanelas;
        font-size: 18px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    @media (max-width: 1024px) {
        .catalog {
            max-width: 90%;
            margin-top: 20px;
        }

        .catalog-head {
            flex-direction: column;
            gap: 20px;
            align-items: flex-start;
        }

        .catalog-head h1 {
            font-size: 45px;
        }

        .catalog-head__sort {
            width: 100%;
            flex-wrap: wrap;
        }

        .catalog-items {
            gap: 15px;
        }

        .catalog-item {
            width: calc(33.333% - 15px);
        }

        .catalog-item img {
            width: 100%;
            height: auto;
        }
    }

    @media (max-width: 390px) {
        .catalog {
            max-width: 95%;
            margin-top: 15px;
        }

        .catalog-head h1 {
            font-size: 30px;
        }

        .catalog-head__sort {
            flex-direction: column;
            gap: 10px;
        }

        .catalog-head__sort a {
            font-size: 15px;
        }

        .catalog-items {
            gap: 10px;
        }

        .catalog-item {
            width: calc(50% - 5px);
        }

        .catalog-item a {
            font-size: 14px;
        }
    }
</style>