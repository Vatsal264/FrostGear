<?php
session_start();
require_once __DIR__ . "/includes/db.php";

/**
 * Category map: id => label
 */
$categories = [
    0 => "All Products",
    1 => "Skis",
    2 => "Boots",
    3 => "Goggles",
    4 => "Ski Poles",
    5 => "Apparel",
    6 => "Jackets"
];

// ---- Read filters from URL ----
$selectedCategory = isset($_GET['category']) ? (int) $_GET['category'] : 0;
$sort             = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$page             = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) $page = 1;

// On sale filter
$onSale = isset($_GET['on_sale']) && $_GET['on_sale'] === '1';

// Sorting options (whitelisted)
$sortOptions = [
    'newest'     => 'Newest',
    'price_asc'  => 'Price (Low to High)',
    'price_desc' => 'Price (High to Low)',
    'name_asc'   => 'Name (A–Z)',
    'name_desc'  => 'Name (Z–A)',
];

if (!array_key_exists($sort, $sortOptions)) {
    $sort = 'newest';
}

$orderBySql = [
    'newest'     => 'created_at DESC',
    'price_asc'  => 'price ASC',
    'price_desc' => 'price DESC',
    'name_asc'   => 'name ASC',
    'name_desc'  => 'name DESC',
][$sort];

// Pagination settings
$perPage = 9;

// ---- Build dynamic WHERE clause & params (category + on_sale only) ----
$conditions = ['is_active = 1'];
$types = '';
$params = [];

// Category
if ($selectedCategory !== 0 && isset($categories[$selectedCategory])) {
    $conditions[] = 'category_id = ?';
    $types .= 'i';
    $params[] = $selectedCategory;
}

// On sale
if ($onSale) {
    $conditions[] = 'is_on_sale = 1';
}

$whereSql = implode(' AND ', $conditions);

// ---- COUNT query (for pagination) ----
$countSql = "SELECT COUNT(*) AS total FROM products WHERE $whereSql";
$stmtCount = $conn->prepare($countSql);
if ($types !== '') {
    $stmtCount->bind_param($types, ...$params);
}
$stmtCount->execute();
$countResult = $stmtCount->get_result();
$rowCount    = $countResult->fetch_assoc();
$totalItems  = (int) ($rowCount['total'] ?? 0);
$stmtCount->close();

$totalPages = max(1, (int) ceil($totalItems / $perPage));
if ($page > $totalPages) $page = $totalPages;
$offset = ($page - 1) * $perPage;

// ---- PRODUCT LIST query ----
$listSql = "
    SELECT id, name, description, price, old_price, stock, main_image, is_on_sale
    FROM products
    WHERE $whereSql
    ORDER BY $orderBySql
    LIMIT ? OFFSET ?
";

$listTypes = $types . 'ii';
$listParams = $params;
$listParams[] = $perPage;
$listParams[] = $offset;

$stmt = $conn->prepare($listSql);
$stmt->bind_param($listTypes, ...$listParams);
$stmt->execute();
$result   = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

/**
 * Helper: trim description for card
 */
function fg_trim_description(string $text, int $length = 80): string {
    $clean = trim(strip_tags($text));
    if (mb_strlen($clean) <= $length) {
        return $clean;
    }
    return mb_substr($clean, 0, $length) . '…';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop – FrostGear</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
          crossorigin="anonymous">
</head>
<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<!-- ================= TOP SHOP HEADER ================= -->
<section class="fg-shop-header">
    <div class="fg-shop-header__inner">
        <div>
            <h1>Shop</h1>
            <nav class="fg-breadcrumb">
                <a href="index.php">Home</a>
                <span>&raquo;</span>
                <span>Shop</span>
            </nav>
        </div>

        <form class="fg-shop-sort" method="get" action="shop.php">
            <?php if ($selectedCategory !== 0): ?>
                <input type="hidden" name="category" value="<?php echo $selectedCategory; ?>">
            <?php endif; ?>
            <?php if ($onSale): ?>
                <input type="hidden" name="on_sale" value="1">
            <?php endif; ?>

            <label for="sort">Sort by:</label>
            <select name="sort" id="sort" onchange="this.form.submit()">
                <?php foreach ($sortOptions as $key => $label): ?>
                    <option value="<?php echo $key; ?>" <?php if ($sort === $key) echo 'selected'; ?>>
                        <?php echo $label; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
</section>

<!-- ================= MAIN SHOP LAYOUT ================= -->
<main class="fg-shop-main">
    <!-- Sidebar -->
    <aside class="fg-shop-sidebar">
        <h2>Browse By</h2>

        <nav class="fg-sidebar-section">
            <?php foreach ($categories as $id => $label): ?>
                <a href="shop.php?category=<?php echo $id; ?>&sort=<?php echo urlencode($sort); ?><?php echo $onSale ? '&on_sale=1' : ''; ?>"
                   class="fg-sidebar-link <?php echo ($selectedCategory === $id) ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($label); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- On Sale toggle – independent -->
        <div class="fg-sidebar-section fg-sidebar-onsale">
            <h3>On Sale</h3>

            <form method="get" action="shop.php" class="fg-onsale-form">
                <input type="hidden" name="category" value="<?php echo $selectedCategory; ?>">
                <input type="hidden" name="sort" value="<?php echo $sort; ?>">

                <label class="fg-switch">
                    <input type="checkbox" name="on_sale" value="1"
                           onchange="this.form.submit()"
                           <?php if ($onSale) echo 'checked'; ?>>
                    <span class="fg-switch__slider"></span>
                </label>
            </form>
        </div>
    </aside>

    <!-- Products -->
    <section class="fg-shop-products">
        <header class="fg-shop-products__header">
            <h2><?php echo $categories[$selectedCategory] ?? 'All Products'; ?></h2>
            <span class="fg-shop-products__count">
                <?php echo $totalItems; ?> items
            </span>
        </header>

        <?php if (!empty($products)): ?>
            <div class="fg-product-grid">
                <?php foreach ($products as $p): ?>
                    <a href="product.php?id=<?php echo (int) $p['id']; ?>"
                       class="fg-product-card--grid">
                        <div class="fg-product-card__img">
                            <?php if (!empty($p['is_on_sale']) && $p['is_on_sale'] == 1): ?>
                                <span class="fg-sale-badge-img">SALE</span>
                            <?php endif; ?>
                            <img src="assets/images/products/<?php echo htmlspecialchars($p['main_image']); ?>"
                                 alt="<?php echo htmlspecialchars($p['name']); ?>">
                        </div>
                        <div class="fg-product-card__body">
                            <h3><?php echo htmlspecialchars($p['name']); ?></h3>
                            <p class="fg-product-card__desc">
                                <?php echo htmlspecialchars(fg_trim_description($p['description'] ?? '')); ?>
                            </p>
                            <div class="fg-product-card__meta">
                                <?php if (!empty($p['is_on_sale']) && $p['is_on_sale'] == 1): ?>
                                    <div class="fg-price-wrapper">
                                        <span class="price-new">
                                            Rs.<?php echo number_format((float) $p['price']); ?>
                                        </span>
                                        <?php if (!empty($p['old_price'])): ?>
                                            <span class="price-old">
                                                Rs.<?php echo number_format((float) $p['old_price']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="price">
                                        Rs.<?php echo number_format((float) $p['price']); ?>
                                    </span>
                                <?php endif; ?>

                                <span class="stock">
                                    In stock: <?php echo (int) $p['stock']; ?>
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="fg-no-products">No products found with these filters.</p>
        <?php endif; ?>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="fg-pagination">
                <?php
                $baseUrl = 'shop.php?sort=' . urlencode($sort)
                         . '&category=' . (int) $selectedCategory;
                if ($onSale) {
                    $baseUrl .= '&on_sale=1';
                }
                ?>

                <?php if ($page > 1): ?>
                    <a href="<?php echo $baseUrl . '&page=' . ($page - 1); ?>" class="fg-page-btn">&laquo;</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?php echo $baseUrl . '&page=' . $i; ?>"
                       class="fg-page-btn <?php echo ($i === $page) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="<?php echo $baseUrl . '&page=' . ($page + 1); ?>" class="fg-page-btn">&raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
