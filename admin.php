<?php
require 'config.php';
require 'auth_helper.php';

// Require admin login
require_role('admin');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Hospital Management System</title>
    <meta name="description" content="Hospital Management System Administration Panel">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/animations.css">
    <link rel="stylesheet" href="/assets/css/3d-cards.css">
</head>

<body style="padding: var(--space-5);">
    <div class="container">
        <div class="card mb-6 animate-fadeInDown">
            <div class="flex justify-between items-center">
                <div>
                    <h1 style="margin-bottom: var(--space-2);">🏥 Hospital Management System</h1>
                    <p class="text-gray-600" style="margin: 0;">Manage doctors, patients, appointments, bills, and
                        more</p>
                </div>
                <a href="/auth/logout.php" class="btn btn-sm" style="background: var(--color-danger);">Logout</a>
            </div>
        </div>



        <div class="grid grid-auto-fit gap-5 admin-cards-container">
            <!-- Doctors -->
            <div class="card card-3d animate-fadeInUp card-3d-stagger-1">
                <div class="card-3d-content">
                    <div class="card-3d-icon">👨‍⚕️</div>
                    <h2 class="card-3d-title" style="margin-bottom: var(--space-3); font-size: var(--font-size-2xl);">
                        Doctors</h2>
                    <p class="card-3d-description" style="margin-bottom: var(--space-4); opacity: 0.9;">Manage doctors
                        and their specialties</p>
                    <div class="card-3d-buttons">
                        <a href="crud/doctors/list.php" class="card-3d-button">View All</a>
                        <a href="crud/doctors/create.php" class="card-3d-button">Add New</a>
                    </div>
                </div>
            </div>

            <!-- Patients -->
            <div class="card card-3d animate-fadeInUp card-3d-stagger-2">
                <div class="card-3d-content">
                    <div class="card-3d-icon">🧑‍🤝‍🧑</div>
                    <h2 class="card-3d-title" style="margin-bottom: var(--space-3); font-size: var(--font-size-2xl);">
                        Patients</h2>
                    <p class="card-3d-description" style="margin-bottom: var(--space-4); opacity: 0.9;">Manage patient
                        information</p>
                    <div class="card-3d-buttons">
                        <a href="crud/patients/list.php" class="card-3d-button">View All</a>
                        <a href="crud/patients/create.php" class="card-3d-button">Add New</a>
                    </div>
                </div>
            </div>

            <!-- Staff -->
            <div class="card card-3d animate-fadeInUp card-3d-stagger-3">
                <div class="card-3d-content">
                    <div class="card-3d-icon">👥</div>
                    <h2 class="card-3d-title" style="margin-bottom: var(--space-3); font-size: var(--font-size-2xl);">
                        Staff</h2>
                    <p class="card-3d-description" style="margin-bottom: var(--space-4); opacity: 0.9;">Manage hospital
                        staff</p>
                    <div class="card-3d-buttons">
                        <a href="crud/staff/list.php" class="card-3d-button">View All</a>
                        <a href="crud/staff/create.php" class="card-3d-button">Add New</a>
                    </div>
                </div>
            </div>

            <!-- Medicine -->
            <div class="card card-3d animate-fadeInUp card-3d-stagger-4">
                <div class="card-3d-content">
                    <div class="card-3d-icon">💊</div>
                    <h2 class="card-3d-title" style="margin-bottom: var(--space-3); font-size: var(--font-size-2xl);">
                        Medicine</h2>
                    <p class="card-3d-description" style="margin-bottom: var(--space-4); opacity: 0.9;">Manage medicine
                        inventory</p>
                    <div class="card-3d-buttons">
                        <a href="crud/medicine/list.php" class="card-3d-button">View All</a>
                        <a href="crud/medicine/create.php" class="card-3d-button">Add New</a>
                    </div>
                </div>
            </div>

            <!-- Appointments -->
            <div class="card card-3d animate-fadeInUp card-3d-stagger-5">
                <div class="card-3d-content">
                    <div class="card-3d-icon">📅</div>
                    <h2 class="card-3d-title" style="margin-bottom: var(--space-3); font-size: var(--font-size-2xl);">
                        Appointments</h2>
                    <p class="card-3d-description" style="margin-bottom: var(--space-4); opacity: 0.9;">Manage patient
                        appointments</p>
                    <div class="card-3d-buttons">
                        <a href="crud/appointments/list.php" class="card-3d-button">View All</a>
                        <a href="crud/appointments/create.php" class="card-3d-button">Add New</a>
                    </div>
                </div>
            </div>

            <!-- Bills -->
            <div class="card card-3d animate-fadeInUp card-3d-stagger-6">
                <div class="card-3d-content">
                    <div class="card-3d-icon">💰</div>
                    <h2 class="card-3d-title" style="margin-bottom: var(--space-3); font-size: var(--font-size-2xl);">
                        Bills</h2>
                    <p class="card-3d-description" style="margin-bottom: var(--space-4); opacity: 0.9;">Manage patient
                        bills</p>
                    <div class="card-3d-buttons">
                        <a href="crud/bills/list.php" class="card-3d-button">View All</a>
                        <a href="crud/bills/create.php" class="card-3d-button">Add New</a>
                    </div>
                </div>
            </div>

            <!-- Bill Items -->
            <div class="card card-3d animate-fadeInUp card-3d-stagger-7">
                <div class="card-3d-content">
                    <div class="card-3d-icon">📋</div>
                    <h2 class="card-3d-title" style="margin-bottom: var(--space-3); font-size: var(--font-size-2xl);">
                        Bill Items</h2>
                    <p class="card-3d-description" style="margin-bottom: var(--space-4); opacity: 0.9;">Manage bill line
                        items</p>
                    <div class="card-3d-buttons">
                        <a href="crud/bill_items/list.php" class="card-3d-button">View All</a>
                        <a href="crud/bill_items/create.php" class="card-3d-button">Add New</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Animated Background -->
    <canvas id="particle-canvas"></canvas>

    <!-- Scripts -->
    <script src="/assets/js/background.js"></script>
    <script src="/assets/js/app.js"></script>
</body>

</html>
