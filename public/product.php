<?php
session_start();
require_once __DIR__ . "/includes/db.php";

$isLoggedIn = isset($_SESSION['user_id']);

$cartError = "";

if (isset($_SESSION['cart_error'])) {
    $cartError = $_SESSION['cart_error'];
    unset($_SESSION['cart_error']);
}

// Get product ID from URL
$productId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($productId <= 0) {
    http_response_code(404);
    $product = null;
} else {
    // Fetch product with category
    $stmt = $conn->prepare("
        SELECT p.id, p.name, p.description, p.price, p.old_price, p.stock,
               p.main_image, p.is_on_sale, p.category_id,
               c.name AS category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.id = ? AND p.is_active = 1
        LIMIT 1
    ");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

// If no product found
if (!$product) {
    http_response_code(404);
}

// Fetch related products (same category, exclude current)
$relatedProducts = [];
if ($product && !empty($product['category_id'])) {
    $catId = (int) $product['category_id'];
    $stmt2 = $conn->prepare("
        SELECT id, name, price, old_price, main_image, is_on_sale
        FROM products
        WHERE is_active = 1
          AND category_id = ?
          AND id != ?
        ORDER BY created_at DESC
        LIMIT 4
    ");
    $stmt2->bind_param("ii", $catId, $productId);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $relatedProducts = $res2->fetch_all(MYSQLI_ASSOC);
    $stmt2->close();
}

// Helper: safe text
function e(string $text): string {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        <?php if ($product): ?>
            <?php echo e($product['name']); ?> – FrostGear
        <?php else: ?>
            Product Not Found – FrostGear
        <?php endif; ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
          crossorigin="anonymous">
</head>
<body>

<?php include __DIR__ . "/includes/header.php"; ?>
<?php if (!empty($cartError)): ?>
    <div class="fg-global-alert">
        <?php echo htmlspecialchars($cartError); ?>
    </div>
<?php endif; ?>


<?php if (!$product): ?>
    <section class="fg-product-notfound">
        <div class="fg-product-notfound__inner">
            <h1>Product not found</h1>
            <p>The item you’re looking for may have been removed or is currently unavailable.</p>
            <a href="shop.php" class="fg-btn fg-btn--gold">Back to Shop</a>
        </div>
    </section>
<?php else: ?>

    <!-- ================= PRODUCT HEADER ================= -->
    <section class="fg-product-header">
        <div class="fg-product-header__inner">
            <div>
                <nav class="fg-breadcrumb">
                    <a href="index.php">Home</a>
                    <span>&raquo;</span>
                    <a href="shop.php">Shop</a>
                    <?php if (!empty($product['category_name'])): ?>
                        <span>&raquo;</span>
                        <a href="shop.php?category=<?php echo (int) $product['category_id']; ?>">
                            <?php echo e($product['category_name']); ?>
                        </a>
                    <?php endif; ?>
                    <span>&raquo;</span>
                    <span><?php echo e($product['name']); ?></span>
                </nav>
                <h1><?php echo e($product['name']); ?></h1>
                <?php if (!empty($product['category_name'])): ?>
                    <span class="fg-product-category-chip">
                        <?php echo e($product['category_name']); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ================= MAIN PRODUCT LAYOUT ================= -->
    <main class="fg-product-main">
        <div class="fg-product-main__inner">
            <!-- Left: image -->
            <section class="fg-product-gallery">
                <div class="fg-product-gallery__image">
                    <?php if (!empty($product['is_on_sale']) && $product['is_on_sale'] == 1): ?>
                        <span class="fg-sale-badge-img">SALE</span>
                    <?php endif; ?>
                    <img src="assets/images/products/<?php echo e($product['main_image']); ?>"
                         alt="<?php echo e($product['name']); ?>">
                </div>
            </section>

            <!-- Right: info -->
            <section class="fg-product-info">
                <div class="fg-product-price-block">
                    <?php if (!empty($product['is_on_sale']) && $product['is_on_sale'] == 1): ?>
                        <div class="fg-price-wrapper fg-price-wrapper--large">
                            <span class="price-new">
                                Rs.<?php echo number_format((float) $product['price']); ?>
                            </span>
                            <?php if (!empty($product['old_price'])): ?>
                                <span class="price-old">
                                    Rs.<?php echo number_format((float) $product['old_price']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <span class="price-large">
                            Rs.<?php echo number_format((float) $product['price']); ?>
                        </span>
                    <?php endif; ?>

                    <span class="fg-product-stock">
                        <?php if ((int) $product['stock'] > 0): ?>
                            In stock: <?php echo (int) $product['stock']; ?>
                        <?php else: ?>
                            <span class="fg-out-of-stock">Out of stock</span>
                        <?php endif; ?>
                    </span>
                </div>

               <div class="fg-product-actions">
                    <?php if ($isLoggedIn): ?>
                        <!-- Logged-in: real add-to-cart form -->
                        <form action="cart.php" method="post" class="fg-product-actions__form">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">

                            <!-- NEW: tell cart.php to send user back here -->
                            <input type="hidden" name="redirect"
                                value="product.php?id=<?php echo (int)$product['id']; ?>">

                            <div class="fg-qty">
                                <label for="qty">Qty</label>
                                <input type="number" id="qty" name="qty" min="1" value="1">
                            </div>

                            <button type="submit" class="fg-btn fg-btn--gold fg-btn--product">
                                Add to Cart
                            </button>
                        </form>

                        <p class="fg-product-actions__note">
                            Cart and checkout are in early access. Quantities and pricing are for demo purposes.
                        </p>

                    <?php else: ?>
                        <!-- Not logged-in: show login CTA instead of cart form -->
                        <a href="login.php?redirect=<?php echo urlencode('product.php?id=' . (int)$product['id']); ?>"
                        class="fg-btn fg-btn--gold fg-btn--product">
                            Login to Add to Cart
                        </a>

                        <p class="fg-product-actions__note">
                            Please sign in to save items to your FrostGear cart.
                        </p>
                    <?php endif; ?>
                </div>

                <div class="fg-product-description">
                    <h2>Product Description</h2>
                    <p>
                        <?php echo nl2br(e($product['description'] ?? '')); ?>
                    </p>
                </div>
            </section>
        </div>
    </main>

    <!-- ================= RELATED PRODUCTS ================= -->
    <?php if (!empty($relatedProducts)): ?>
        <section class="fg-related">
            <div class="fg-related__inner">
                <header class="fg-related__header">
                    <h2>You may also like</h2>
                </header>

                <div class="fg-related-grid">
                    <?php foreach ($relatedProducts as $rp): ?>
                        <a href="product.php?id=<?php echo (int) $rp['id']; ?>"
                           class="fg-product-card--grid">
                            <div class="fg-product-card__img">
                                <?php if (!empty($rp['is_on_sale']) && $rp['is_on_sale'] == 1): ?>
                                    <span class="fg-sale-badge-img">SALE</span>
                                <?php endif; ?>
                                <img src="assets/images/products/<?php echo e($rp['main_image']); ?>"
                                     alt="<?php echo e($rp['name']); ?>">
                            </div>
                            <div class="fg-product-card__body">
                                <h3><?php echo e($rp['name']); ?></h3>
                                <div class="fg-product-card__meta">
                                    <?php if (!empty($rp['is_on_sale']) && $rp['is_on_sale'] == 1): ?>
                                        <div class="fg-price-wrapper">
                                            <span class="price-new">
                                                Rs.<?php echo number_format((float) $rp['price']); ?>
                                            </span>
                                            <?php if (!empty($rp['old_price'])): ?>
                                                <span class="price-old">
                                                    Rs.<?php echo number_format((float) $rp['old_price']); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="price">
                                            Rs.<?php echo number_format((float) $rp['price']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

<?php endif; ?>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
