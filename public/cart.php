<?php
session_start();
require_once __DIR__ . "/includes/db.php";

/* ==========================================
   INITIALIZE CART
========================================== */
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$cart =& $_SESSION['cart'];

$errors = [];

/* ==========================================
   HANDLE POST REQUESTS
========================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ---------- ADD ITEM (from product page) ---------- */
    if (isset($_POST['action']) && $_POST['action'] === 'add') {

        // Block if user not logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['cart_error'] = "Please log in to add items to your cart.";
            header("Location: " . ($_POST['redirect'] ?? "product.php"));
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $qty       = (int)($_POST['qty'] ?? 1);
        if ($qty < 1) $qty = 1;

        // Fetch product from DB
        $stmt = $conn->prepare("
            SELECT id, name, price, main_image, stock 
            FROM products 
            WHERE id = ? AND is_active = 1
        ");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $product = $result->fetch_assoc()) {
            $maxQty = (int)$product['stock'];

            // Cap qty by stock
            if ($maxQty > 0 && $qty > $maxQty) {
                $qty = $maxQty;
            }

            // If item exists, add qty
            if (isset($cart[$productId])) {
                $newQty = $cart[$productId]['qty'] + $qty;
                if ($maxQty > 0 && $newQty > $maxQty) {
                    $newQty = $maxQty;
                }
                $cart[$productId]['qty'] = $newQty;

            } else {
                // Add new item
                $cart[$productId] = [
                    'id'    => $product['id'],
                    'name'  => $product['name'],
                    'price' => (float)$product['price'],
                    'image' => $product['main_image'],
                    'qty'   => $qty
                ];
            }

        } else {
            $errors[] = "Product not found.";
        }

        // DO NOT REDIRECT FORCEFULLY TO CART — user stays on product page
        if (!empty($_POST['redirect'])) {
            header("Location: " . $_POST['redirect']);
            exit;
        }

        header("Location: product.php?id=" . $productId);
        exit;
    }

    /* ---------- UPDATE QTY (Auto-submit from JS) ---------- */
    if (isset($_POST['action']) && $_POST['action'] === 'update') {

        if (isset($_POST['qty']) && is_array($_POST['qty'])) {
            foreach ($_POST['qty'] as $pid => $qtyRaw) {
                $pid = (int)$pid;
                $qty = (int)$qtyRaw;

                if (!isset($cart[$pid])) continue;

                if ($qty <= 0) {
                    unset($cart[$pid]);
                } else {
                    // Validate stock from DB
                    $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
                    $stmt->bind_param("i", $pid);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $max = $res && $r = $res->fetch_assoc() ? (int)$r['stock'] : 0;

                    if ($max > 0 && $qty > $max) {
                        $qty = $max;
                    }

                    $cart[$pid]['qty'] = $qty;
                }
            }
        }

        header("Location: cart.php");
        exit;
    }
}

/* ==========================================
   HANDLE GET REQUESTS
========================================== */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    /* ---------- REMOVE SINGLE ITEM ---------- */
    if (isset($_GET['action']) && $_GET['action'] === 'remove') {
        $pid = (int)($_GET['id'] ?? 0);
        if (isset($cart[$pid])) unset($cart[$pid]);
        header("Location: cart.php");
        exit;
    }

    /* ---------- CLEAR CART ---------- */
    if (isset($_GET['action']) && $_GET['action'] === 'clear') {
        $_SESSION['cart'] = [];
        header("Location: cart.php");
        exit;
    }
}

/* ==========================================
   CALCULATE TOTALS
========================================== */
$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['qty'];
}
$grandTotal = $subtotal;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart – FrostGear</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<section class="fg-cart-hero">
    <div class="fg-cart-hero__inner">
        <nav class="fg-breadcrumb">
            <a href="index.php">Home</a>
            <span>&raquo;</span>
            <span>Cart</span>
        </nav>
        <h1>Your Cart</h1>
        <p>Review your FrostGear items before checkout.</p>
    </div>
</section>

<main class="fg-cart-main">
    <div class="fg-cart-main__inner">

        <?php if (!empty($errors)): ?>
            <div class="fg-cart-alert">
                <?php foreach ($errors as $e): ?>
                    <p><?= htmlspecialchars($e) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($cart)): ?>

            <!-- EMPTY CART UI -->
            <div class="fg-cart-empty">
                <h2>Your cart is empty</h2>
                <p>Browse our premium ski gear to get started.</p>
                <a href="shop.php" class="fg-btn fg-btn--gold">Continue Shopping</a>
            </div>

        <?php else: ?>

            <!-- ======================= CART ITEMS TABLE ======================= -->
            <section class="fg-cart-items-box">
                <form action="cart.php" method="post" id="fgCartForm">
                    <input type="hidden" name="action" value="update">

                    <table class="fg-cart-table">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($cart as $item): ?>
                            <tr class="fg-cart-row"
                                data-id="<?= (int)$item['id'] ?>"
                                data-price="<?= htmlspecialchars($item['price']) ?>">

                                <td class="fg-cart-item-info">
                                    <div class="fg-cart-item-thumb">
                                        <img src="assets/images/products/<?= htmlspecialchars($item['image']) ?>"
                                             alt="<?= htmlspecialchars($item['name']) ?>">
                                    </div>
                                    <div class="fg-cart-item-name">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </div>
                                </td>

                                <td class="fg-cart-price">
                                    Rs.<?= number_format($item['price'], 0) ?>
                                </td>

                                <td class="fg-cart-qty">
                                    <input type="number"
                                           class="fg-cart-qty-input"
                                           name="qty[<?= (int)$item['id'] ?>]"
                                           min="0"
                                           value="<?= (int)$item['qty'] ?>">
                                </td>

                                <td class="fg-cart-line-total">
                                    <span class="fg-cart-line-total-value">
                                        Rs.<?= number_format($item['price'] * $item['qty'], 0) ?>
                                    </span>
                                </td>

                                <td class="fg-cart-remove">
                                    <a href="cart.php?action=remove&id=<?= (int)$item['id'] ?>"
                                       class="fg-cart-remove-link"
                                       aria-label="Remove">
                                        &times;
                                    </a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="fg-cart-actions-row">
                        <a href="cart.php?action=clear"
                           class="fg-link-danger">
                            Clear Cart
                        </a>
                    </div>
                </form>
            </section>

            <!-- ======================= ORDER SUMMARY ======================= -->
            <aside class="fg-cart-summary">
                <h2>Order Summary</h2>

                <div class="fg-cart-summary-row">
                    <span>Subtotal</span>
                    <span id="fgCartSubtotal">Rs.<?= number_format($subtotal, 0) ?></span>
                </div>

                <div class="fg-cart-summary-row fg-cart-summary-row--total">
                    <span>Total</span>
                    <span id="fgCartTotal">Rs.<?= number_format($grandTotal, 0) ?></span>
                </div>

                <p class="fg-cart-summary-note">
                    Checkout system coming soon.
                </p>

                <button class="fg-btn fg-btn--gold fg-btn--full" disabled>
                    Proceed to Checkout
                </button>

                <a href="shop.php" class="fg-cart-back-link">Continue Shopping</a>
            </aside>

        <?php endif; ?>

    </div>
</main>

<?php include __DIR__ . "/includes/footer.php"; ?>

<script src="assets/js/cart.js"></script>
</body>
</html>
