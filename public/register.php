<?php
session_start();
require_once __DIR__ . "/includes/db.php";

$success   = "";
$error     = "";
$nameValue  = "";
$emailValue = "";

// Show PHP errors while developing (optional, can remove later)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// When form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name     = trim($_POST["name"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    // Keep values if there is an error
    $nameValue  = $name;
    $emailValue = $email;

    if ($name === "" || $email === "" || $password === "") {
        $error = "All fields are required.";
    } else {

        /* ---------- CHECK IF EMAIL EXISTS ---------- */
        $checkSql  = "SELECT id FROM users WHERE email = ?";
        $checkStmt = mysqli_prepare($conn, $checkSql);

        if ($checkStmt === false) {
            $error = "Database error (prepare check).";
        } else {
            mysqli_stmt_bind_param($checkStmt, "s", $email);
            mysqli_stmt_execute($checkStmt);
            mysqli_stmt_store_result($checkStmt);

            if (mysqli_stmt_num_rows($checkStmt) > 0) {
                $error = "Email is already registered.";
            } else {
                /* ---------- INSERT NEW USER ---------- */
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $insertSql  = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
                $insertStmt = mysqli_prepare($conn, $insertSql);

                if ($insertStmt === false) {
                    $error = "Database error (prepare insert).";
                } else {
                    mysqli_stmt_bind_param($insertStmt, "sss", $name, $email, $hashedPassword);

                    if (mysqli_stmt_execute($insertStmt)) {
                        $success          = "Account created successfully! Redirecting...";
                        $_SESSION["user"] = $name;

                        // Redirect after 2 seconds
                        header("Refresh: 2; url=login.php");
                    } else {
                        $error = "Something went wrong. Please try again.";
                    }
                }
            }

            mysqli_stmt_close($checkStmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account – FrostGear</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<section class="fg-auth">
    <div class="fg-auth__card">

        <div class="fg-auth__logo">
            <img src="assets/images/FrostGear.png" alt="FrostGear Logo">
        </div>

        <h2>Create Account</h2>

        <?php if (!empty($error)): ?>
            <p class="fg-error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <p class="fg-success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <p class="fg-auth__subtitle">
            Join FrostGear for a smoother shopping experience.
        </p>

        <form action="register.php" method="POST" class="fg-auth__form">
            <div class="fg-field">
                <label for="name">Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Your full name"
                    value="<?php echo htmlspecialchars($nameValue); ?>"
                    required
                >
            </div>

            <div class="fg-field">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="you@example.com"
                    value="<?php echo htmlspecialchars($emailValue); ?>"
                    required
                >
            </div>

            <div class="fg-field">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Create a password"
                    required
                >
            </div>

            <button type="submit" class="fg-btn fg-btn--gold fg-auth-btn">
                Create Account
            </button>
        </form>

        <p class="fg-auth__switch">
            Already have an account?
            <a href="login.php">Sign in</a>
        </p>
    </div>
</section>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
