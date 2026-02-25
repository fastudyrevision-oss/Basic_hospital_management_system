<?php
require '../config.php';
require '../auth_helper.php';

// Require patient login
require_login();
$user = get_logged_in_user();

if ($user['role'] !== 'patient' || !$user['patient_id']) {
    header('Location: /dashboard.php');
    exit;
}

// Get all appointments for this patient
$stmt = $pdo->prepare("
    SELECT a.*, 
           d.first_name as doctor_first, 
           d.last_name as doctor_last, 
           d.speciality,
           d.phone_number as doctor_phone,
           d.email as doctor_email
    FROM appointments a 
    JOIN doctors d ON a.doctor_id = d.id 
    WHERE a.patient_id = ? 
    ORDER BY a.appointment_date DESC
");
$stmt->execute([$user['patient_id']]);
$appointments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments - Hospital Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .navbar {
            background: white;
            padding: 15px 30px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar h2 {
            color: #333;
        }

        .btn-back {
            background: #667eea;
            color: white;
            padding: 8px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .header-card h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .appointment-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .appointment-card:hover {
            transform: translateY(-3px);
        }

        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .appointment-header h3 {
            color: #333;
            font-size: 20px;
        }

        .appointment-date {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
        }

        .appointment-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .detail-item {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .detail-item strong {
            display: block;
            color: #666;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .detail-item span {
            color: #333;
            font-size: 15px;
        }

        .notes {
            margin-top: 15px;
            padding: 15px;
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            border-radius: 6px;
        }

        .notes strong {
            color: #856404;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 10px;
        }

        .empty-state h3 {
            color: #999;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <h2>📅 My Appointments</h2>
        <a href="/dashboard.php" class="btn-back">← Back to Dashboard</a>
    </div>

    <div class="container">
        <div class="header-card">
            <h1>All Appointments</h1>
            <p>View all your scheduled appointments with doctors</p>
        </div>

        <?php if (empty($appointments)): ?>
            <div class="empty-state">
                <h3>No appointments found</h3>
                <p>You don't have any appointments scheduled yet.</p>
            </div>
        <?php else: ?>
            <?php foreach ($appointments as $apt): ?>
                <div class="appointment-card">
                    <div class="appointment-header">
                        <h3>Dr. <?= htmlspecialchars($apt['doctor_first'] . ' ' . $apt['doctor_last']) ?></h3>
                        <div class="appointment-date">
                            <?= date('M d, Y', strtotime($apt['appointment_date'])) ?>
                            <br>
                            <?= date('h:i A', strtotime($apt['appointment_date'])) ?>
                        </div>
                    </div>

                    <div class="appointment-details">
                        <div class="detail-item">
                            <strong>SPECIALITY</strong>
                            <span><?= htmlspecialchars($apt['speciality']) ?></span>
                        </div>
                        <div class="detail-item">
                            <strong>DOCTOR PHONE</strong>
                            <span><?= htmlspecialchars($apt['doctor_phone']) ?></span>
                        </div>
                        <div class="detail-item">
                            <strong>DOCTOR EMAIL</strong>
                            <span><?= htmlspecialchars($apt['doctor_email']) ?></span>
                        </div>
                        <div class="detail-item">
                            <strong>APPOINTMENT ID</strong>
                            <span>#<?= $apt['id'] ?></span>
                        </div>
                    </div>

                    <?php if ($apt['notes']): ?>
                        <div class="notes">
                            <strong>📝 Notes:</strong> <?= htmlspecialchars($apt['notes']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>