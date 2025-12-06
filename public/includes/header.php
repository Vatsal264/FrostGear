<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_name = null;

if (!empty($_SESSION['user_id'])) {
    require_once __DIR__ . '/db.php';

    $user_id = (int) $_SESSION['user_id'];

    $stmt = mysqli_prepare($conn, "SELECT name FROM users WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $user_name = $row['name'];
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<header class="fg-header">
    <?php if ($user_name): ?>
        <div class="fg-welcome-banner">
            <span>Welcome, <?php echo htmlspecialchars($user_name); ?></span>
        </div>
    <?php endif; ?>

    <div class="fg-header__inner">
        <a href="index.php" class="fg-logo">
            <img src="assets/images/FrostGear.png"
                 alt="FrostGear logo"
                 class="fg-logo__img">
            <span class="fg-logo__text">FrostGear</span>
        </a>

        <nav class="fg-nav">
            <a href="index.php" class="fg-nav__link">Home</a>
            <a href="shop.php" class="fg-nav__link">Shop</a>
            <a href="about.php" class="fg-nav__link">About</a>
            <a href="contact.php" class="fg-nav__link">Contact</a>

            <?php if ($user_name): ?>
                <a href="logout.php"
                   class="fg-nav__link"
                   onclick="return confirm('Are you sure you want to log out of FrostGear?');">
                   Logout
                </a>
            <?php else: ?>
                <a href="login.php" class="fg-nav__link">Login</a>
            <?php endif; ?>

            <button id="cartToggle"
                    class="fg-cart-btn"
                    type="button"
                    aria-label="View cart">
                🛒
            </button>
        </nav>
    </div>
</header>

<div class="fg-cart-panel" id="cartPanel">
    <div class="fg-cart-header">
        <h2>Your Cart</h2>
        <button class="fg-cart-close" id="cartClose">&times;</button>
    </div>
    <div class="fg-cart-items">
        <p>Your cart is currently empty.</p>
    </div>
</div>
<div class="fg-cart-overlay" id="cartOverlay"></div>
