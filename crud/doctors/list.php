<?php
require '../../config.php';

$doctors = get_all('doctors', $pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors - Hospital Management System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
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
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
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
            <h1>👨‍⚕️ Doctors</h1>
            <div>
                <a href="create.php" class="btn btn-add">+ Add New Doctor</a>
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
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Speciality</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($doctors as $doctor): ?>
                <tr>
                    <td><?php echo htmlspecialchars($doctor['id']); ?></td>
                    <td><?php echo htmlspecialchars($doctor['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($doctor['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($doctor['speciality'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($doctor['phone_number'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($doctor['email'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($doctor['created_at']); ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?php echo $doctor['id']; ?>" class="btn btn-edit">Edit</a>
                        <a href="delete.php?id=<?php echo $doctor['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (empty($doctors)): ?>
            <p style="text-align: center; margin-top: 20px; color: #666;">No doctors found. <a href="create.php">Add one now</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
