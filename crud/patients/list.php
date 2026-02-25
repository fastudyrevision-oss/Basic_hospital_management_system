<?php
require '../../config.php';

$patients = get_all('patients', $pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients - Hospital Management System</title>
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
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
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
            <h1>🧑‍🤝‍🧑 Patients</h1>
            <div>
                <a href="create.php" class="btn btn-add">+ Add New Patient</a>
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
                    <th>Gender</th>
                    <th>DOB</th>
                    <th>Age</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($patients as $patient): ?>
                <tr>
                    <td><?php echo htmlspecialchars($patient['id']); ?></td>
                    <td><?php echo htmlspecialchars($patient['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($patient['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($patient['gender'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($patient['dob'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($patient['age'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($patient['phone_number'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($patient['email'] ?? 'N/A'); ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?php echo $patient['id']; ?>" class="btn btn-edit">Edit</a>
                        <a href="delete.php?id=<?php echo $patient['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (empty($patients)): ?>
            <p style="text-align: center; margin-top: 20px; color: #666;">No patients found. <a href="create.php">Add one now</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
