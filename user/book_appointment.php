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

$success = '';
$error = '';

// Get all doctors for the dropdown
$stmt = $pdo->query("SELECT * FROM doctors ORDER BY first_name, last_name");
$doctors = $stmt->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $notes = sanitize_input($_POST['notes']);

    if (empty($doctor_id) || empty($appointment_date) || empty($appointment_time)) {
        $error = 'Please fill in all required fields';
    } else {
        try {
            // Combine date and time
            $appointment_datetime = $appointment_date . ' ' . $appointment_time;

            // Insert appointment
            $stmt = $pdo->prepare("INSERT INTO appointments (doctor_id, patient_id, appointment_date, notes) VALUES (?, ?, ?, ?)");
            $stmt->execute([$doctor_id, $user['patient_id'], $appointment_datetime, $notes]);

            $success = 'Appointment booked successfully!';
        } catch (PDOException $e) {
            $error = 'Failed to book appointment: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Hospital Management System</title>
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
            max-width: 800px;
            margin: 0 auto;
        }

        .form-card {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.3s;
            font-family: inherit;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .error {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #c33;
        }

        .success {
            background: #efe;
            color: #3c3;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #3c3;
        }

        .doctor-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            display: none;
        }

        .doctor-info.active {
            display: block;
        }

        .doctor-info h4 {
            color: #333;
            margin-bottom: 8px;
        }

        .doctor-info p {
            color: #666;
            font-size: 14px;
            margin: 4px 0;
        }
    </style>
    <script>
        // Show doctor info when selected
        function showDoctorInfo() {
            const select = document.getElementById('doctor_id');
            const selectedOption = select.options[select.selectedIndex];
            const infoDiv = document.getElementById('doctor-info');

            if (selectedOption.value) {
                const speciality = selectedOption.getAttribute('data-speciality');
                const phone = selectedOption.getAttribute('data-phone');
                const email = selectedOption.getAttribute('data-email');

                document.getElementById('doctor-speciality').textContent = speciality;
                document.getElementById('doctor-phone').textContent = phone;
                document.getElementById('doctor-email').textContent = email;

                infoDiv.classList.add('active');
            } else {
                infoDiv.classList.remove('active');
            }
        }
    </script>
</head>

<body>
    <div class="navbar">
        <h2>📅 Book Appointment</h2>
        <a href="/dashboard.php" class="btn-back">← Back to Dashboard</a>
    </div>

    <div class="container">
        <div class="form-card">
            <h1>Schedule New Appointment</h1>
            <p class="subtitle">Book an appointment with one of our doctors</p>

            <?php if ($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success">
                    <?= $success ?>
                    <br><br>
                    <a href="/user/appointments.php" style="color: #3c3; font-weight: bold;">View All Appointments →</a>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="doctor_id">Select Doctor *</label>
                    <select id="doctor_id" name="doctor_id" required onchange="showDoctorInfo()">
                        <option value="">-- Choose a doctor --</option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?= $doctor['id'] ?>"
                                data-speciality="<?= htmlspecialchars($doctor['speciality']) ?>"
                                data-phone="<?= htmlspecialchars($doctor['phone_number']) ?>"
                                data-email="<?= htmlspecialchars($doctor['email']) ?>">
                                Dr. <?= htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div id="doctor-info" class="doctor-info">
                        <h4>Doctor Information</h4>
                        <p><strong>Speciality:</strong> <span id="doctor-speciality"></span></p>
                        <p><strong>Phone:</strong> <span id="doctor-phone"></span></p>
                        <p><strong>Email:</strong> <span id="doctor-email"></span></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="appointment_date">Appointment Date *</label>
                    <input type="date" id="appointment_date" name="appointment_date" required
                        min="<?= date('Y-m-d') ?>">
                </div>

                <div class="form-group">
                    <label for="appointment_time">Appointment Time *</label>
                    <input type="time" id="appointment_time" name="appointment_time" required>
                </div>

                <div class="form-group">
                    <label for="notes">Notes / Reason for Visit</label>
                    <textarea id="notes" name="notes"
                        placeholder="Describe your symptoms or reason for visit..."><?= $_POST['notes'] ?? '' ?></textarea>
                </div>

                <button type="submit" class="btn">Book Appointment</button>
            </form>
        </div>
    </div>
</body>

</html>