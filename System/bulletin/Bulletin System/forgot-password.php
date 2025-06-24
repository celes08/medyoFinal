<?php
include("connections.php");
session_start();

$email = '';
$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }

    // Password validation (same as signup)
    if (empty($password)) {
        $errors[] = "Password is required.";
    } else {
        if (strlen($password) < 8 || strlen($password) > 16) {
            $errors[] = "Password must be 8-16 characters.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter.";
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number.";
        }
        if (!preg_match("/[!@#$%^&*()_\-\=\[\]{};':\"\\|,.<>\/?]/", $password)) {
            $errors[] = "Password must contain at least one special character.";
        }
    }
    if ($password !== $confirmPassword) $errors[] = "Passwords do not match.";

    // If no errors, update password
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $con->prepare("UPDATE signuptbl SET password=? WHERE email=?");
        $stmt->bind_param("ss", $hashedPassword, $email);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $success = "Password reset successful! You may now log in.";
        } else {
            $errors[] = "No account found with that email, or database error.";
        }
        $stmt->close();
    }
} else {
    $email = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - CVSU Department Bulletin Board System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Inter', Arial, sans-serif;
            background: #e9ecef;
        }
        .background-image {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            object-fit: cover;
            z-index: -2;
            filter: brightness(0.50);
            background: linear-gradient(rgba(4, 9, 30, 0.85), rgba(4, 9, 30, 0.85)), url('img/Silang-Campus-scaled.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 1.2s cubic-bezier(.4,0,.2,1);
            gap: 0;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .left-panel, .right-panel {
            min-height: 480px;
            height: 70vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin: 0;
        }
        .left-panel {
            background: rgba(22, 53, 32, 0.82);
            color: #fff;
            border-radius: 22px 0 0 22px;
            padding: 54px 38px 54px 38px;
            max-width: 370px;
            min-width: 300px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.22);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }
        .logo-container {
            margin-bottom: 18px;
        }
        .logo {
            width: 90px;
            height: 90px;
            display: block;
            margin: 0 auto 10px auto;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.12));
        }
        .left-panel h1 {
            font-size: 1.45rem;
            font-weight: 800;
            margin: 0 0 10px 0;
            text-align: center;
            line-height: 1.3;
            letter-spacing: 0.5px;
            color: #fff !important;
            text-shadow: 0 2px 8px rgba(0,0,0,0.18), 0 1px 0 #222;
        }
        .left-panel p {
            font-size: 1.08rem;
            font-weight: 400;
            margin: 0;
            text-align: center;
            color: #e0e0e0;
            text-shadow: 0 2px 8px rgba(0,0,0,0.18);
        }
        .right-panel {
            background: rgba(255,255,255,0.82);
            border-radius: 0 22px 22px 0;
            box-shadow: 0 8px 32px rgba(0,0,0,0.13);
            padding: 22px 36px 36px 36px;
            min-width: 350px;
            max-width: 400px;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            animation: fadeIn 1.2s cubic-bezier(.4,0,.2,1);
            position: relative;
        }
        .form-container {
            margin: 0 auto;
            width: 100%;
            max-width: 340px;
            padding: 0;
        }
        .form-container h2 {
            font-size: 1.18rem;
            font-weight: 800;
            margin-bottom: 16px;
            color: #1b4332;
            text-align: center;
            letter-spacing: 0.5px;
        }
        .form-group {
            margin-bottom: 13px;
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            font-size: 1.01rem;
            font-weight: 600;
            margin-bottom: 7px;
            color: #1b4332;
            letter-spacing: 0.2px;
        }
        .form-group input {
            padding: 13px 15px;
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            font-size: 1.01rem;
            background: #f8f9fa;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-group input:focus {
            border-color: #1b4332;
            outline: none;
            box-shadow: 0 0 0 2px rgba(27,67,50,0.10);
        }
        .login-button {
            width: 100%;
            padding: 11px 0;
            font-size: 1.05rem;
            font-weight: 700;
            border-radius: 9px;
            background: #1b4332;
            color: #fff;
            border: none;
            margin-top: 6px;
            transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(27,67,50,0.08);
        }
        .login-button:hover, .login-button:focus {
            background: #14532d;
            transform: translateY(-1px) scale(1.01);
            box-shadow: 0 4px 16px rgba(27,67,50,0.13);
        }
        .notification {
            display: none;
            position: fixed;
            top: 32px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            min-width: 280px;
            max-width: 400px;
            padding: 16px 24px;
            border-radius: 10px;
            font-size: 1rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.13);
            opacity: 0;
            transition: opacity 0.5s, top 0.5s;
            word-break: break-word;
        }
        .notification.show {
            display: block;
            opacity: 1;
        }
        .notification.success {
            background: #d1fae5;
            color: #065f46;
            border: 1.5px solid #10b981;
        }
        .notification.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1.5px solid #ef4444;
        }
        .notification .close-btn {
            position: absolute;
            top: 8px;
            right: 12px;
            background: none;
            border: none;
            font-size: 1.2em;
            color: #991b1b;
            cursor: pointer;
        }
        .footer {
            text-align: center;
            color: #fff;
            font-size: 0.98rem;
            position: fixed;
            bottom: 0; left: 0; width: 100vw;
            background: rgba(27,67,50,0.85);
            padding: 10px 0;
            z-index: 10;
        }
        @media (max-width: 900px) {
            .main-container { flex-direction: column; gap: 0; }
            .left-panel, .right-panel {
                border-radius: 22px 22px 0 0;
                min-width: unset;
                max-width: 100vw;
                height: auto;
                margin: 0;
        }
            .right-panel { border-radius: 0 0 22px 22px; }
        }
        @media (max-width: 600px) {
            .main-container { flex-direction: column; gap: 0; }
            .left-panel, .right-panel {
                border-radius: 0;
                min-width: unset;
                max-width: 100vw;
                padding: 24px 8px;
                height: auto;
                margin: 0;
            }
            .right-panel { border-radius: 0 0 22px 22px; }
            .footer { font-size: 0.9rem; }
            .form-container {
                max-width: 98vw;
                padding: 0 2vw;
            }
        }
    </style>
</head>
<body>
    <img src="img/Silang-Campus-scaled.jpg" alt="Campus aerial view" class="background-image">
    <div class="main-container">
        <div class="left-panel">
            <div class="logo-container">
                <img src="img/logo.png" alt="CvSU Logo" class="logo">
            </div>
            <h1>Welcome to CVSU's Department Bulletin Board System</h1>
            <p>Stay updated with the latest announcements from all departments</p>
        </div>
        <div class="right-panel">
            <div class="form-container">
                <h2>REQUEST CHANGE PASSWORD</h2>
                <?php if ($success): ?>
                    <div class="notification success"><?php echo $success; ?></div>
                    <a href="index.php" class="back-to-login">BACK TO LOG IN</a>
                <?php else: ?>
                    <?php if ($errors): ?>
                        <div class="notification error" id="floatingError">
                            <?php foreach ($errors as $e) echo htmlspecialchars($e) . "<br>"; ?>
                            <button class="close-btn" onclick="this.parentElement.style.display='none'">&times;</button>
                        </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm New Password</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" required>
                        </div>
                        <button type="submit" class="login-button">
                            <span class="button-text">Submit</span>
                        </button>
                        <a href="index.php" class="back-to-login">BACK TO LOG IN</a>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="footer">
        <p>© 2025 School Bulletin Board System. All rights reserved.</p>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var emailInput = document.getElementById('email');
        var storedEmail = sessionStorage.getItem('resetEmail');
        if (storedEmail && emailInput) {
            emailInput.value = storedEmail;
        }
    });

    // Floating error message auto-hide
    window.addEventListener('DOMContentLoaded', function() {
        var errorBox = document.getElementById('floatingError');
        if (errorBox) {
            errorBox.classList.add('show');
            setTimeout(function() {
                errorBox.classList.remove('show');
                errorBox.style.opacity = 0;
            }, 5000); // Hide after 5 seconds
        }
    });
    </script>
</body>
</html>