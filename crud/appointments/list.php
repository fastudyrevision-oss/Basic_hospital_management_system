<?php
require '../../config.php';

$stmt = $pdo->query("
    SELECT a.*, d.first_name as doc_first, d.last_name as doc_last, p.first_name as pat_first, p.last_name as pat_last
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.id
    JOIN patients p ON a.patient_id = p.id
");
$appointments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - Hospital Management System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 20px; }
        .btn { padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin: 5px; }
        .btn-add { background: #2196F3; color: white; }
        .btn-add:hover { background: #0b7dda; }
        .btn-back { background: #666; color: white; }
        .btn-back:hover { background: #555; }
        .btn-edit { background: #FF9800; color: white; }
        .btn-edit:hover { background: #e68900; }
        .btn-delete { background: #f44336; color: white; }
        .btn-delete:hover { background: #da190b; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 13px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #667eea; color: white; }
        tr:hover { background: #f5f5f5; }
        .actions { text-align: center; }
        .alert { padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📅 Appointments</h1>
            <div>
                <a href="create.php" class="btn btn-add">+ New Appointment</a>
                <a href="../../index.php" class="btn btn-back">Back to Home</a>
            </div>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Operation successful!</div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Doctor</th>
                    <th>Patient</th>
                    <th>Date & Time</th>
                    <th>Notes</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $apt): ?>
                <tr>
                    <td><?php echo htmlspecialchars($apt['id']); ?></td>
                    <td><?php echo htmlspecialchars($apt['doc_first'] . ' ' . $apt['doc_last']); ?></td>
                    <td><?php echo htmlspecialchars($apt['pat_first'] . ' ' . $apt['pat_last']); ?></td>
                    <td><?php echo htmlspecialchars($apt['appointment_date']); ?></td>
                    <td><?php echo htmlspecialchars(substr($apt['notes'] ?? '', 0, 30)); ?></td>
                    <td><?php echo htmlspecialchars($apt['created_at']); ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?php echo $apt['id']; ?>" class="btn btn-edit">Edit</a>
                        <a href="delete.php?id=<?php echo $apt['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (empty($appointments)): ?>
            <p style="text-align: center; margin-top: 20px; color: #666;">No appointments found. <a href="create.php">Add one now</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
