<?php
require '../config.php';
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: /dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = sanitize_input($_POST['first_name']);
    $last_name = sanitize_input($_POST['last_name']);
    $phone = sanitize_input($_POST['phone']);
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];

    // Validation
    if (empty($email) || empty($password) || empty($first_name) || empty($last_name)) {
        $error = 'Please fill in all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Email already registered';
            } else {
                // Calculate age from DOB
                $age = null;
                if (!empty($dob)) {
                    $birthDate = new DateTime($dob);
                    $today = new DateTime();
                    $age = $today->diff($birthDate)->y;
                }

                // Create patient record first
                $stmt = $pdo->prepare("INSERT INTO patients (first_name, last_name, gender, dob, phone_number, email, age) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$first_name, $last_name, $gender, $dob, $phone, $email, $age]);
                $patient_id = $pdo->lastInsertId();

                // Create user account
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (email, password, role, patient_id) VALUES (?, ?, 'patient', ?)");
                $stmt->execute([$email, $hashed_password, $patient_id]);

                $success = 'Account created successfully! You can now login.';
            }
        } catch (PDOException $e) {
            $error = 'Registration failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Hospital Management System</title>
    <meta name="description" content="Create your Hospital Management System account">

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

    <div class="container container-md">
        <div class="card shadow-2xl animate-fadeInUp" style="max-width: 600px; margin: 0 auto;">
            <div class="text-center mb-6">
                <h1 class="text-4xl mb-4">🏥 Create Account</h1>
                <p class="text-gray-600 text-lg">Join Hospital Management System</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error animate-shake">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success animate-bounceIn">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="signupForm">
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="first_name" class="form-label form-label-required">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="form-input" required
                            value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>" placeholder="John">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="last_name" class="form-label form-label-required">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="form-input" required
                            value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>" placeholder="Doe">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label form-label-required">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" required
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="john.doe@example.com"
                        autocomplete="email">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="password" class="form-label form-label-required">Password</label>
                        <input type="password" id="password" name="password" class="form-input" required
                            placeholder="Min. 6 characters" autocomplete="new-password">
                        <p class="form-help">At least 6 characters</p>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="confirm_password" class="form-label form-label-required">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-input" required
                            placeholder="Re-enter password" autocomplete="new-password">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-input"
                            value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" placeholder="+92 300 1234567"
                            autocomplete="tel">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="gender" class="form-label">Gender</label>
                        <select id="gender" name="gender" class="form-select">
                            <option value="Male" <?= ($_POST['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= ($_POST['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female
                            </option>
                            <option value="Other" <?= ($_POST['gender'] ?? '') === 'Other' ? 'selected' : '' ?>>Other
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" id="dob" name="dob" class="form-input"
                        value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>">
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg hover-lift" id="signupBtn">
                    Create Account
                </button>
            </form>

            <div class="divider"></div>

            <div class="text-center">
                <p class="text-gray-600">
                    Already have an account?
                    <a href="login.php" class="font-semibold" style="color: var(--color-primary);">
                        Login here →
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
        document.getElementById('signupForm').addEventListener('submit', function (e) {
            const btn = document.getElementById('signupBtn');
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                if (window.HMS) {
                    window.HMS.Toast.error('Passwords do not match!');
                }
                return false;
            }

            if (window.HMS) {
                window.HMS.setLoading(btn, true);
            }
        });
    </script>
</body>

</html>