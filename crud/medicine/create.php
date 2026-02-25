<?php
require '../../config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = sanitize_input($_POST['product_name'] ?? '');
    $duration = sanitize_input($_POST['duration'] ?? '');
    $dosage = sanitize_input($_POST['dosage'] ?? '');

    if (empty($product_name)) {
        $error = "Product name is required!";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO medicine (product_name, duration, dosage) VALUES (?, ?, ?)");
            $stmt->execute([$product_name, $duration, $dosage]);
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
    <title>Add Medicine - Hospital Management System</title>
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
        <h1>💊 Add New Medicine</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="product_name">Product Name *</label>
                <input type="text" id="product_name" name="product_name" placeholder="e.g., Panadol" required>
            </div>

            <div class="form-group">
                <label for="duration">Duration</label>
                <input type="text" id="duration" name="duration" placeholder="e.g., 5 days">
            </div>

            <div class="form-group">
                <label for="dosage">Dosage</label>
                <input type="text" id="dosage" name="dosage" placeholder="e.g., 2 tablets daily">
            </div>

            <div>
                <button type="submit" class="btn btn-submit">Add Medicine</button>
                <a href="list.php" class="btn btn-back">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
