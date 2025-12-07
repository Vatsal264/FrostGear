<?php
session_start();
require_once __DIR__ . "/includes/db.php";

$error      = "";
$success    = "";
$emailValue = "";

// Read flash error from session (e.g., from cart redirect)
$flashError = "";
if (!empty($_SESSION['flash_error'])) {
    $flashError = $_SESSION['flash_error'];
    unset($_SESSION['flash_error']); // show it only once
}

// If user is already logged in, keep them away from login page
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    $emailValue = $email; // keep in field if there is an error

    if ($email === "" || $password === "") {
        $error = "Please enter both email and password.";
    } else {
        // Find user by email
        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password hash
            if (password_verify($password, $user["password"])) {
                // Login success – set session data
                $_SESSION["user_id"]    = $user["id"];
                $_SESSION["user_name"]  = $user["name"];
                $_SESSION["user_email"] = $user["email"];

                // Optional small success text (won't really be seen due to redirect)
                $success = "Login successful. Redirecting to homepage...";

                header("Location: index.php");
                exit;
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "No account found with that email.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login – FrostGear</title>
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

        <h2>Welcome Back</h2>

        <?php if (!empty($flashError)): ?>
            <p class="fg-error"><?php echo htmlspecialchars($flashError); ?></p>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <p class="fg-error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <p class="fg-success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <p class="fg-auth__subtitle">
            Log in to continue exploring FrostGear.
        </p>

        <form action="login.php" method="POST" class="fg-auth__form">

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
                    placeholder="Your password"
                    required
                >
            </div>

            <button type="submit" class="fg-btn fg-btn--gold fg-auth-btn">
                Log In
            </button>
        </form>

        <p class="fg-auth__switch">
            Don't have an account?
            <a href="register.php">Create one</a>
        </p>
    </div>
</section>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
