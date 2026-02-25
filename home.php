<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Hospital Management System</title>
    <meta name="description"
        content="Modern Hospital Management System - Book appointments, access medical records, and manage your healthcare efficiently">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/animations.css">
</head>

<body>
    <!-- Animated Background -->
    <canvas id="particle-canvas"></canvas>

    <div class="container" style="padding: var(--space-5);">
        <div class="hero animate-fadeInUp">
            <h1 class="hero-title">🏥 Hospital Management System</h1>
            <p class="hero-subtitle">Your Health, Our Priority</p>

            <div class="grid grid-auto-fit gap-5 mb-8">
                <div class="feature-card animate-fadeInUp stagger-1 scroll-reveal">
                    <div class="feature-icon">📅</div>
                    <h3 class="feature-title">Book Appointments</h3>
                    <p class="feature-text">Schedule appointments with our expert doctors at your convenience</p>
                </div>
                <div class="feature-card animate-fadeInUp stagger-2 scroll-reveal">
                    <div class="feature-icon">💊</div>
                    <h3 class="feature-title">Medical Records</h3>
                    <p class="feature-text">Access your complete medical history anytime, anywhere</p>
                </div>
                <div class="feature-card animate-fadeInUp stagger-3 scroll-reveal">
                    <div class="feature-icon">💰</div>
                    <h3 class="feature-title">View Bills</h3>
                    <p class="feature-text">Track your medical bills and payment history easily</p>
                </div>
            </div>

            <div class="flex gap-4 justify-center flex-wrap animate-fadeInUp stagger-4">
                <a href="/auth/login.php" class="btn btn-primary btn-lg hover-lift">
                    Login to Your Account
                </a>
                <a href="/auth/signup.php" class="btn btn-secondary btn-lg hover-lift">
                    Create New Account
                </a>
            </div>

            <div class="divider"></div>

            <div class="text-center animate-fadeInUp stagger-5">
                <p class="text-gray-600">
                    Are you an administrator?
                    <a href="/admin.php" class="font-semibold" style="color: var(--color-primary);">
                        Admin Login →
                    </a>
                </p>
            </div>
        </div>

        <!-- Features Section -->
        <div class="mt-8 grid grid-auto-fit gap-5">
            <div class="card card-glass hover-lift scroll-reveal">
                <div class="card-title" style="color: var(--color-white);">
                    <span style="font-size: var(--font-size-3xl);">⚡</span>
                    Fast & Efficient
                </div>
                <p style="color: rgba(255, 255, 255, 0.9); margin: 0;">
                    Quick appointment booking and instant access to your medical information
                </p>
            </div>

            <div class="card card-glass hover-lift scroll-reveal">
                <div class="card-title" style="color: var(--color-white);">
                    <span style="font-size: var(--font-size-3xl);">🔒</span>
                    Secure & Private
                </div>
                <p style="color: rgba(255, 255, 255, 0.9); margin: 0;">
                    Your health data is protected with industry-standard security measures
                </p>
            </div>

            <div class="card card-glass hover-lift scroll-reveal">
                <div class="card-title" style="color: var(--color-white);">
                    <span style="font-size: var(--font-size-3xl);">📱</span>
                    Mobile Friendly
                </div>
                <p style="color: rgba(255, 255, 255, 0.9); margin: 0;">
                    Access your healthcare information from any device, anywhere
                </p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/assets/js/background.js"></script>
    <script src="/assets/js/app.js"></script>
</body>

</html>