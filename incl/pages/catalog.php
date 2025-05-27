<?php
// Подключаемся к базе данных
require_once 'incl/connect/connect.php';

// Получаем выбранную категорию
$selectedCategoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Получаем поисковый запрос
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// Получаем список всех категорий
$categoriesQuery = "SELECT * FROM categories ORDER BY name ASC";
$categoriesStmt = $connect->prepare($categoriesQuery);
$categoriesStmt->execute();
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

// Формируем SQL запрос в зависимости от фильтров
$filmsQuery = "SELECT * FROM films WHERE 1=1";
$params = [];

// Добавляем фильтр по категории, если выбрана
if ($selectedCategoryId > 0) {
    $filmsQuery .= " AND category_id = :category_id";
    $params[':category_id'] = $selectedCategoryId;
}

// Добавляем поиск, если есть запрос
if (!empty($searchQuery)) {
    $searchTerm = "%" . $searchQuery . "%";
    $filmsQuery .= " AND LOWER(title) LIKE LOWER(:search)";
    $params[':search'] = $searchTerm;
}

// Добавляем сортировку
$filmsQuery .= " ORDER BY created_at DESC";

// Подготавливаем и выполняем запрос
$filmsStmt = $connect->prepare($filmsQuery);
foreach ($params as $key => $value) {
    $filmsStmt->bindValue($key, $value);
}
$filmsStmt->execute();
$films = $filmsStmt->fetchAll(PDO::FETCH_ASSOC);

// Получаем название выбранной категории
$selectedCategoryName = "Все фильмы";
if ($selectedCategoryId > 0) {
    foreach ($categories as $category) {
        if ($category['id'] == $selectedCategoryId) {
            $selectedCategoryName = $category['name'];
            break;
        }
    }
}
?>
<section class="catalog">
    <div class="catalog-head">
        <h1>Фильмы<?php echo $selectedCategoryId > 0 ? ': ' . htmlspecialchars($selectedCategoryName) : ''; ?></h1>
        
        <!-- Форма поиска -->
        <div class="catalog-search">
            <form action="" method="GET">
                <input type="hidden" name="p" value="catalog">
                <?php if($selectedCategoryId > 0): ?>
                <input type="hidden" name="category" value="<?php echo $selectedCategoryId; ?>">
                <?php endif; ?>
                <input type="text" name="search" placeholder="Поиск фильмов..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit"><img src="./assets/media/images/search-icon.svg" alt="Поиск"></button>
            </form>
        </div>
        
        <!-- Категории -->
        <div class="catalog-categories">
            <a href="?p=catalog<?php echo !empty($searchQuery) ? '&search=' . urlencode($searchQuery) : ''; ?>" 
               class="category-item <?php echo $selectedCategoryId == 0 ? 'active' : ''; ?>">
                Все фильмы
            </a>
            <?php if(count($categories) > 0): ?>
                <?php foreach($categories as $category): ?>
                    <a href="?p=catalog&category=<?php echo $category['id']; ?><?php echo !empty($searchQuery) ? '&search=' . urlencode($searchQuery) : ''; ?>" 
                       class="category-item <?php echo $selectedCategoryId == $category['id'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Результаты поиска -->
    <?php if(!empty($searchQuery)): ?>
    <div class="search-results">
        <p>Результаты поиска по запросу: <span><?php echo htmlspecialchars($searchQuery); ?></span> 
        <?php if($selectedCategoryId > 0): ?>
        в категории <span><?php echo htmlspecialchars($selectedCategoryName); ?></span>
        <?php endif; ?>
        </p>
    </div>
    <?php endif; ?>
    
    <div class="catalog-items">
        <?php if(count($films) > 0): ?>
            <?php foreach($films as $film): ?>
            <div class="catalog-item">
                <div class="catalog-item-wrapper">
                    <a href="?p=film-page&id=<?php echo $film['id']; ?>">
                        <img src="./assets/media/images/catalog/<?php echo htmlspecialchars($film['poster']); ?>" alt="<?php echo htmlspecialchars($film['title']); ?>">
                        <?php if($film['is_premium'] == 1): ?>
                        <div class="premium-badge">
                            <span>Подписка</span>
                        </div>
                        <?php endif; ?>
                    </a>
                </div>
                <a href="?p=film-page&id=<?php echo $film['id']; ?>">
                    <p><?php echo htmlspecialchars($film['title']); ?></p>
                </a>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-films">Фильмы не найдены</p>
        <?php endif; ?>
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
        flex-direction: column;
        gap: 20px;
    }

    .catalog-head h1 {
        color: #FFF;
        font-family: Qanelas;
        font-size: 65px;
        font-style: normal;
        font-weight: 900;
        line-height: normal;
    }

    /* Стили для поисковой формы */
    .catalog-search {
        width: 100%;
        margin-bottom: 20px;
    }

    .catalog-search form {
        display: flex;
        position: relative;
        max-width: 600px;
    }

    .catalog-search input[type="text"] {
        width: 100%;
        padding: 15px 20px;
        border-radius: 10px;
        background: #1E2538;
        border: 1px solid rgba(54, 87, 203, 0.2);
        color: #fff;
        font-family: Qanelas;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .catalog-search input[type="text"]:focus {
        border-color: rgba(54, 87, 203, 0.7);
        outline: none;
    }

    .catalog-search button {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
    }

    .catalog-search button img {
        width: 20px;
        height: 20px;
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }

    .catalog-search button:hover img {
        opacity: 1;
    }

    /* Стили для категорий */
    .catalog-categories {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .catalog-categories a.category-item {
        color: rgba(255, 255, 255, 0.35);
        font-family: Qanelas;
        font-size: 17px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
        padding: 5px 10px;
        border-radius: 5px;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
    }
    
    .catalog-categories a.category-item:hover,
    .catalog-categories a.category-item.active {
        color: rgba(255, 255, 255, 0.9);
        background-color: rgba(54, 87, 203, 0.4);
    }

    /* Стили для результатов поиска */
    .search-results {
        margin: 10px 0 20px;
    }

    .search-results p {
        font-family: Qanelas;
        font-size: 16px;
        color: rgba(255, 255, 255, 0.7);
    }

    .search-results span {
        color: #F2F60F;
        font-weight: 600;
    }

    .catalog-items {
        margin-top: 30px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 23px;
    }

    .catalog-item {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .catalog-item-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
    }

    .catalog-item img {
        width: 100%;
        height: auto;
        aspect-ratio: 2/3;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    
    .catalog-item:hover img {
        transform: scale(1.03);
    }

    .catalog-item p {
        max-width: fit-content;
        color: #FFF;
        font-family: Qanelas;
        font-size: 18px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
        transition: color 0.3s ease;
    }
    
    .catalog-item:hover p {
        color: #3657CB;
    }
    
    .no-films {
        grid-column: 1 / -1;
        color: rgba(255, 255, 255, 0.5);
        font-family: Qanelas;
        font-size: 18px;
        font-style: italic;
        text-align: center;
        padding: 30px 0;
    }

    .premium-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        background-color: rgba(242, 246, 15, 0.9);
        color: #0F1527;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
        z-index: 5;
    }

    @media (max-width: 1024px) {
        .catalog {
            max-width: 90%;
            margin-top: 20px;
        }

        .catalog-head h1 {
            font-size: 45px;
        }

        .catalog-search form {
            max-width: 100%;
        }

        .catalog-categories {
            width: 100%;
            flex-wrap: wrap;
        }

        .catalog-items {
            gap: 15px;
            margin-top: 30px;
        }

        .catalog-item img {
            border-radius: 6px;
        }
        
        .catalog-item p {
            font-size: 16px;
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

        .catalog-search input[type="text"] {
            padding: 12px 15px;
            font-size: 14px;
        }

        .catalog-categories {
            flex-direction: column;
            gap: 10px;
        }

        .catalog-categories a.category-item {
            font-size: 15px;
        }

        .search-results p {
            font-size: 14px;
        }

        .catalog-items {
            gap: 10px;
            margin-top: 20px;
            grid-template-columns: repeat(2, 1fr);
        }

        .catalog-item p {
            font-size: 14px;
        }
    }
</style>