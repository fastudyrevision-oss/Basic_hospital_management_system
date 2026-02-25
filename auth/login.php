<?php
require '../config.php';
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: /dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['patient_id'] = $user['patient_id'];
                $_SESSION['doctor_id'] = $user['doctor_id'];
                $_SESSION['staff_id'] = $user['staff_id'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: /index.php');
                } else {
                    header('Location: /dashboard.php');
                }
                exit;
            } else {
                $error = 'Invalid email or password';
            }
        } catch (PDOException $e) {
            $error = 'Login failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hospital Management System</title>
    <meta name="description" content="Login to your Hospital Management System account">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/animations.css">
</head>

<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: var(--space-5);">
    <!-- Animated Background -->
    <canvas id="particle-canvas"></canvas>

    <div class="container container-sm">
        <div class="card shadow-2xl animate-fadeInUp" style="max-width: 450px; margin: 0 auto;">
            <div class="text-center mb-6">
                <h1 class="text-4xl mb-4">🏥 Welcome Back</h1>
                <p class="text-gray-600 text-lg">Login to Hospital Management System</p>
            </div>

        

            <?php if ($error): ?>
                <div class="alert alert-error animate-shake">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="loginForm">
                <div class="form-group">
                    <label for="email" class="form-label form-label-required">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" required
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="Enter your email"
                        autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label form-label-required">Password</label>
                    <input type="password" id="password" name="password" class="form-input" required
                        placeholder="Enter your password" autocomplete="current-password">
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg hover-lift" id="loginBtn">
                    Login to Account
                </button>
            </form>

            <div class="divider"></div>

            <div class="text-center">
                <p class="text-gray-600">
                    Don't have an account?
                    <a href="signup.php" class="font-semibold" style="color: var(--color-primary);">
                        Sign up here →
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/assets/js/background.js"></script>
    <script src="/assets/js/app.js"></script>
    <script>
        // Form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            const btn = document.getElementById('loginBtn');
            if (window.HMS) {
                window.HMS.setLoading(btn, true);
            }
        });
    </script>
</body>

</html>