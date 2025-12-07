<?php
session_start();
require_once __DIR__ . "/includes/db.php";

$isLoggedIn = isset($_SESSION['user_id']);

// Ensure cart array exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$cart =& $_SESSION['cart'];

$errors = [];

// ---------- HANDLE ACTIONS ----------
$isLoggedIn = isset($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* =================== ADD ITEM TO CART ==========================*/
    if (isset($_POST['action']) && $_POST['action'] === 'add') {

        // Require login
        if (!$isLoggedIn) {
            $_SESSION['cart_error'] = "Please log in to add items to your cart.";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }


        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $qty       = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
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
            if ($maxQty > 0 && $qty > $maxQty) {
                $qty = $maxQty;
            }

            if (isset($cart[$productId])) {
                $newQty = $cart[$productId]['qty'] + $qty;
                if ($maxQty > 0 && $newQty > $maxQty) {
                    $newQty = $maxQty;
                }
                $cart[$productId]['qty'] = $newQty;
            } else {
                $cart[$productId] = [
                    'id'    => $product['id'],
                    'name'  => $product['name'],
                    'price' => (float)$product['price'],
                    'image' => $product['main_image'],
                    'qty'   => $qty
                ];
            }

        } else {
            $errors[] = "Product not found or inactive.";
        }

        header("Location: cart.php");
        exit;
    }


    /* ================================
       UPDATE CART QUANTITIES
    =================================*/
    if (isset($_POST['action']) && $_POST['action'] === 'update') {

        // Require login
        if (!$isLoggedIn) {
            $_SESSION['flash_error'] = "Please log in to update your cart.";
            header("Location: login.php");
            exit;
        }

        if (isset($_POST['qty']) && is_array($_POST['qty'])) {

            foreach ($_POST['qty'] as $pid => $qtyRaw) {
                $pid = (int)$pid;
                $qty = (int)$qtyRaw;

                if (!isset($cart[$pid])) continue;

                if ($qty <= 0) {
                    unset($cart[$pid]);
                } else {
                    // Check stock
                    $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
                    $stmt->bind_param("i", $pid);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    $maxQty = 0;
                    if ($result && $row = $result->fetch_assoc()) {
                        $maxQty = (int)$row['stock'];
                    }

                    if ($maxQty > 0 && $qty > $maxQty) {
                        $qty = $maxQty;
                    }

                    $cart[$pid]['qty'] = $qty;
                }
            }
        }

        header("Location: cart.php");
        exit;
    }
}

// ---------- CALCULATE TOTALS ----------
$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['qty'];
}
$grandTotal = $subtotal; // later you can add shipping, tax, etc.
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
        <p>
            Review your selected FrostGear items before proceeding to checkout.
        </p>
    </div>
</section>

<main class="fg-cart-main">
    <div class="fg-cart-main__inner">

        <?php if (!empty($errors)): ?>
            <div class="fg-cart-alert">
                <?php foreach ($errors as $e): ?>
                    <p><?php echo htmlspecialchars($e); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($cart)): ?>
            <div class="fg-cart-empty">
                <h2>Your cart is empty</h2>
                <p>Browse our latest skis, boots, and gear to start building your kit.</p>
                <a href="shop.php" class="fg-btn fg-btn--gold">Continue Shopping</a>
            </div>
        <?php else: ?>

            <section class="fg-cart-items-box">
                <form action="cart.php" method="post">
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
                            <tr>
                                <td class="fg-cart-item-info">
                                    <div class="fg-cart-item-thumb">
                                        <img src="assets/images/products/<?php echo htmlspecialchars($item['image']); ?>"
                                             alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    </div>
                                    <div>
                                        <div class="fg-cart-item-name">
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="fg-cart-price">
                                    Rs.<?php echo number_format($item['price'], 0); ?>
                                </td>
                                <td class="fg-cart-qty">
                                    <input type="number"
                                           name="qty[<?php echo (int)$item['id']; ?>]"
                                           min="0"
                                           max="999"
                                           value="<?php echo (int)$item['qty']; ?>">
                                </td>
                                <td class="fg-cart-line-total">
                                    Rs.<?php echo number_format($item['price'] * $item['qty'], 0); ?>
                                </td>
                                <td class="fg-cart-remove">
                                    <a href="cart.php?action=remove&id=<?php echo (int)$item['id']; ?>"
                                       onclick="return confirm('Remove this item from your cart?');">
                                        &times;
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="fg-cart-actions-row">
                        <button type="submit" class="fg-btn fg-btn--outline">
                            Update Cart
                        </button>
                        <a href="cart.php?action=clear"
                           class="fg-link-danger"
                           onclick="return confirm('Clear all items from your cart?');">
                            Clear Cart
                        </a>
                    </div>
                </form>
            </section>

            <aside class="fg-cart-summary">
                <h2>Order Summary</h2>
                <div class="fg-cart-summary-row">
                    <span>Subtotal</span>
                    <span>Rs.<?php echo number_format($subtotal, 0); ?></span>
                </div>
                <div class="fg-cart-summary-row fg-cart-summary-row--total">
                    <span>Total</span>
                    <span>Rs.<?php echo number_format($grandTotal, 0); ?></span>
                </div>
                <p class="fg-cart-summary-note">
                    Checkout and payment will be added in the next phase of FrostGear.
                </p>
                <button class="fg-btn fg-btn--gold fg-btn--full" disabled>
                    Proceed to Checkout (Coming Soon)
                </button>
                <a href="shop.php" class="fg-cart-back-link">Continue Shopping</a>
            </aside>

        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
