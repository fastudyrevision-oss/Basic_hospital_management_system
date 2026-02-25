<?php
require '../../config.php';

$doctors = get_all('doctors', $pdo);
$patients = get_all('patients', $pdo);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = (int)($_POST['doctor_id'] ?? 0);
    $patient_id = (int)($_POST['patient_id'] ?? 0);
    $appointment_date = sanitize_input($_POST['appointment_date'] ?? '');
    $notes = sanitize_input($_POST['notes'] ?? '');

    if (!$doctor_id || !$patient_id || empty($appointment_date)) {
        $error = "Doctor, patient, and appointment date are required!";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO appointments (doctor_id, patient_id, appointment_date, notes) VALUES (?, ?, ?, ?)");
            $stmt->execute([$doctor_id, $patient_id, $appointment_date, $notes]);
            redirect('list.php?success=1');
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Appointment - Hospital Management System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #333; font-weight: 500; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #667eea; box-shadow: 0 0 5px rgba(102, 126, 234, 0.3); }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-right: 10px; }
        .btn-submit { background: #4CAF50; color: white; }
        .btn-submit:hover { background: #45a049; }
        .btn-back { background: #666; color: white; }
        .btn-back:hover { background: #555; }
        .alert { padding: 12px; margin-bottom: 20px; border-radius: 5px; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        textarea { resize: vertical; min-height: 100px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📅 Add New Appointment</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="doctor_id">Doctor *</label>
                <select id="doctor_id" name="doctor_id" required>
                    <option value="">Select a doctor</option>
                    <?php foreach ($doctors as $doc): ?>
                    <option value="<?php echo $doc['id']; ?>"><?php echo htmlspecialchars($doc['first_name'] . ' ' . $doc['last_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="patient_id">Patient *</label>
                <select id="patient_id" name="patient_id" required>
                    <option value="">Select a patient</option>
                    <?php foreach ($patients as $pat): ?>
                    <option value="<?php echo $pat['id']; ?>"><?php echo htmlspecialchars($pat['first_name'] . ' ' . $pat['last_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="appointment_date">Appointment Date & Time *</label>
                <input type="datetime-local" id="appointment_date" name="appointment_date" required>
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" placeholder="Add any notes about this appointment"></textarea>
            </div>

            <div>
                <button type="submit" class="btn btn-submit">Add Appointment</button>
                <a href="list.php" class="btn btn-back">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
