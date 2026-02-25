<?php
require '../../config.php';

$id = $_GET['id'] ?? null;
$patient = get_by_id('patients', $id, $pdo);
$error = '';

if (!$patient) {
    redirect('list.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = sanitize_input($_POST['first_name'] ?? '');
    $last_name = sanitize_input($_POST['last_name'] ?? '');
    $gender = sanitize_input($_POST['gender'] ?? '');
    $dob = sanitize_input($_POST['dob'] ?? '');
    $phone = sanitize_input($_POST['phone_number'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $age = (int)($_POST['age'] ?? 0);

    if (empty($first_name) || empty($last_name)) {
        $error = "First name and last name are required!";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE patients SET first_name = ?, last_name = ?, gender = ?, dob = ?, phone_number = ?, email = ?, age = ? WHERE id = ?");
            $stmt->execute([$first_name, $last_name, $gender ?: null, $dob ?: null, $phone, $email, $age ?: null, $id]);
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
    <title>Edit Patient - Hospital Management System</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h1>🧑‍🤝‍🧑 Edit Patient</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="first_name">First Name *</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($patient['first_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name *</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($patient['last_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender">
                    <option value="">Select Gender</option>
                    <option value="Male" <?php echo $patient['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo $patient['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo $patient['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($patient['dob'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" min="0" value="<?php echo htmlspecialchars($patient['age'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="tel" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($patient['phone_number'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($patient['email'] ?? ''); ?>">
            </div>

            <div>
                <button type="submit" class="btn btn-submit">Update Patient</button>
                <a href="list.php" class="btn btn-back">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
