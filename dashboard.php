<?php
require 'config.php';
require 'auth_helper.php';

// Require login
require_login();

$user = get_logged_in_user();

// Get user-specific data based on role
$userData = null;
$appointments = [];
$bills = [];

if ($user['role'] === 'patient' && $user['patient_id']) {
    // Get patient details
    $stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
    $stmt->execute([$user['patient_id']]);
    $userData = $stmt->fetch();

    // Get upcoming appointments
    $stmt = $pdo->prepare("
        SELECT a.*, d.first_name as doctor_first, d.last_name as doctor_last, d.speciality 
        FROM appointments a 
        JOIN doctors d ON a.doctor_id = d.id 
        WHERE a.patient_id = ? 
        ORDER BY a.appointment_date DESC 
        LIMIT 5
    ");
    $stmt->execute([$user['patient_id']]);
    $appointments = $stmt->fetchAll();

    // Get bills
    $stmt = $pdo->prepare("
        SELECT * FROM bills 
        WHERE patient_id = ? 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $stmt->execute([$user['patient_id']]);
    $bills = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Hospital Management System</title>
    <meta name="description" content="Your personal healthcare dashboard">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/animations.css">
</head>

<body style="padding: var(--space-5);">
    <div class="navbar animate-fadeInDown">
        <h2 class="navbar-brand">🏥 Hospital Management System</h2>
        <div class="navbar-menu">
            <div class="user-info">
                <strong><?= htmlspecialchars($user['email']) ?></strong>
                <br>
                <small><?= ucfirst($user['role']) ?></small>
            </div>
            <a href="/auth/logout.php" class="btn btn-sm" style="background: var(--color-danger);">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="card mb-6 animate-fadeInUp">
            <h1 style="margin-bottom: var(--space-2);">Welcome,
                <?= $userData ? htmlspecialchars($userData['first_name']) : 'User' ?>! 👋</h1>
            <p class="text-gray-600" style="margin: 0;">Here's your dashboard overview</p>
        </div>

        <?php if ($user['role'] === 'patient'): ?>
            <div class="grid grid-auto-fit gap-5 mb-6">
                <div class="card card-gradient stat-card hover-lift animate-fadeInUp stagger-1">
                    <div class="stat-label">Total Appointments</div>
                    <div class="stat-number" data-count="<?= count($appointments) ?>"><?= count($appointments) ?></div>
                </div>
                <div class="card card-gradient stat-card hover-lift animate-fadeInUp stagger-2">
                    <div class="stat-label">Total Bills</div>
                    <div class="stat-number" data-count="<?= count($bills) ?>"><?= count($bills) ?></div>
                </div>
                <div class="card card-gradient stat-card hover-lift animate-fadeInUp stagger-3">
                    <div class="stat-label">Unpaid Bills</div>
                    <div class="stat-number"
                        data-count="<?= count(array_filter($bills, fn($b) => $b['status'] === 'unpaid')) ?>">
                        <?= count(array_filter($bills, fn($b) => $b['status'] === 'unpaid')) ?>
                    </div>
                </div>
            </div>

            <div class="grid grid-auto-fit gap-5 mb-6">
                <div class="card hover-lift animate-fadeInUp stagger-4">
                    <div class="card-header">
                        <h3 class="card-title">📅 Recent Appointments</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($appointments)): ?>
                            <div class="empty-state">
                                <div class="empty-state-icon">📅</div>
                                <div class="empty-state-title">No appointments found</div>
                                <p class="empty-state-text">Book your first appointment to get started</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($appointments as $apt): ?>
                                <div class="list-item">
                                    <h4 class="list-item-title">Dr.
                                        <?= htmlspecialchars($apt['doctor_first'] . ' ' . $apt['doctor_last']) ?></h4>
                                    <p class="list-item-text"><strong><?= htmlspecialchars($apt['speciality']) ?></strong></p>
                                    <p class="list-item-text">📅 <?= date('M d, Y h:i A', strtotime($apt['appointment_date'])) ?>
                                    </p>
                                    <?php if ($apt['notes']): ?>
                                        <p class="list-item-text">📝 <?= htmlspecialchars($apt['notes']) ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <a href="/user/book_appointment.php" class="btn btn-success">📅 Book New</a>
                        <a href="/user/appointments.php" class="btn btn-primary">View All</a>
                    </div>
                </div>

                <div class="card hover-lift animate-fadeInUp stagger-5">
                    <div class="card-header">
                        <h3 class="card-title">💰 Recent Bills</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($bills)): ?>
                            <div class="empty-state">
                                <div class="empty-state-icon">💰</div>
                                <div class="empty-state-title">No bills found</div>
                                <p class="empty-state-text">Your billing history will appear here</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($bills as $bill): ?>
                                <div class="list-item">
                                    <h4 class="list-item-title">Bill #<?= $bill['id'] ?></h4>
                                    <p class="list-item-text"><strong>Rs. <?= number_format($bill['total_amount'], 2) ?></strong>
                                    </p>
                                    <p class="list-item-text">Payment: <?= htmlspecialchars($bill['payment_method']) ?></p>
                                    <p class="list-item-text">
                                        <span class="badge badge-<?= $bill['status'] ?>">
                                            <?= ucfirst($bill['status']) ?>
                                        </span>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <a href="/user/bills.php" class="btn btn-primary">View All Bills</a>
                    </div>
                </div>
            </div>

            <div class="card hover-lift animate-fadeInUp stagger-6">
                <div class="card-header">
                    <h3 class="card-title">👤 My Profile</h3>
                </div>
                <div class="list-item">
                    <p class="list-item-text"><strong>Name:</strong>
                        <?= htmlspecialchars($userData['first_name'] . ' ' . $userData['last_name']) ?></p>
                    <p class="list-item-text"><strong>Email:</strong> <?= htmlspecialchars($userData['email']) ?></p>
                    <p class="list-item-text"><strong>Phone:</strong>
                        <?= htmlspecialchars($userData['phone_number'] ?? 'N/A') ?></p>
                    <p class="list-item-text"><strong>Gender:</strong> <?= htmlspecialchars($userData['gender'] ?? 'N/A') ?>
                    </p>
                    <p class="list-item-text"><strong>Age:</strong> <?= htmlspecialchars($userData['age'] ?? 'N/A') ?></p>
                </div>
            </div>
        <?php else: ?>
            <div class="card hover-lift">
                <div class="card-header">
                    <h3 class="card-title">🎯 Quick Actions</h3>
                </div>
                <p>Your role-specific dashboard is coming soon!</p>
                <div class="card-footer">
                    <a href="/index.php" class="btn btn-primary">Go to Admin Panel</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Scripts -->
    <script src="/assets/js/app.js"></script>
</body>

</html>