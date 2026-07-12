<?php
require_once "config/db.php";

if (isLoggedIn()) {
    if (isset($_SESSION["role"]) && $_SESSION["role"] === "employee") {
        redirect("employee_dashboard.php");
    } else {
        redirect("dashboard.php");
    }
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = clean($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["admin_id"] = $user["id"]; // legacy fallback
        $_SESSION["admin_name"] = $user["full_name"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];
        $_SESSION["employee_id"] = $user["employee_id"];

        if ($user["role"] === "employee") {
            redirect("employee_dashboard.php");
        } else {
            redirect("dashboard.php");
        }

    } else {

        $error = "Invalid username or password.";

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AttendEase - Login</title>
    <link rel="icon" type="image/png" href="images/favicon.png">
    
    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/index.css">
</head>

<body>

<div class="login-wrapper">
    <div class="login-card">
        <!-- Left Side: Peeking Graphic -->
        <div class="login-left">
            <img src="images/peeking-login.png" alt="Peeking Graphic">
        </div>
        
        <!-- Right Side: Login Form -->
        <div class="login-right">
            <div class="login-form-container">
                <div class="brand-logo">
                    <a href="index.php">
                        <img src="images/logo.png" alt="AttendEase Logo">
                    </a>
                </div>
                <h2>Welcome Back!</h2>
                <p class="subtitle">Please enter your credentials to log in.</p>

                <?php if (!empty($error)): ?>
                    <div class="error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form action="login.php" method="post">
                    <div class="input-group">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Enter Username" required>
                    </div>

                    <div class="input-group">
                        <label>Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" placeholder="Enter Password" required>
                            <button type="button" class="toggle-password" id="togglePassword" aria-label="Toggle password visibility">
                                <!-- Eye Open SVG -->
                                <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <!-- Eye Closed SVG -->
                                <svg class="eye-off-icon hidden" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="js/script.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const togglePasswordBtn = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");
    
    if (togglePasswordBtn && passwordInput) {
        const eyeIcon = togglePasswordBtn.querySelector(".eye-icon");
        const eyeOffIcon = togglePasswordBtn.querySelector(".eye-off-icon");

        togglePasswordBtn.addEventListener("click", function() {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.add("hidden");
                eyeOffIcon.classList.remove("hidden");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("hidden");
                eyeOffIcon.classList.add("hidden");
            }
        });
    }
});
</script>

</body>
</html>
