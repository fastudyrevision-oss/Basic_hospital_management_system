<?php
require '../../config.php';

$id = $_GET['id'] ?? null;
$bill_item = get_by_id('bill_items', $id, $pdo);
$error = '';

if (!$bill_item) {
    redirect('list.php');
}

$bills = $pdo->query("
    SELECT b.id, CONCAT(p.first_name, ' ', p.last_name) as patient_name, b.total_amount, b.status
    FROM bills b
    JOIN patients p ON b.patient_id = p.id
")->fetchAll();

$medicines = get_all('medicine', $pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bill_id = (int)($_POST['bill_id'] ?? 0);
    $medicine_id = (int)($_POST['medicine_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);

    if (!$bill_id || !$medicine_id || $quantity <= 0 || $price <= 0) {
        $error = "All fields are required and must be valid!";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE bill_items SET bill_id = ?, medicine_id = ?, quantity = ?, price = ? WHERE id = ?");
            $stmt->execute([$bill_id, $medicine_id, $quantity, $price, $id]);
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
    <title>Edit Bill Item - Hospital Management System</title>
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
        <h1>📋 Edit Bill Item</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="bill_id">Bill *</label>
                <select id="bill_id" name="bill_id" required>
                    <option value="">Select a bill</option>
                    <?php foreach ($bills as $bill): ?>
                    <option value="<?php echo $bill['id']; ?>" <?php echo $bill['id'] === $bill_item['bill_id'] ? 'selected' : ''; ?>>#<?php echo $bill['id']; ?> - <?php echo htmlspecialchars($bill['patient_name']); ?> (Rs. <?php echo number_format($bill['total_amount'], 2); ?>) - <?php echo ucfirst($bill['status']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="medicine_id">Medicine *</label>
                <select id="medicine_id" name="medicine_id" required>
                    <option value="">Select a medicine</option>
                    <?php foreach ($medicines as $med): ?>
                    <option value="<?php echo $med['id']; ?>" <?php echo $med['id'] === $bill_item['medicine_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($med['product_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity *</label>
                <input type="number" id="quantity" name="quantity" min="1" value="<?php echo htmlspecialchars($bill_item['quantity']); ?>" required>
            </div>

            <div class="form-group">
                <label for="price">Price per Unit (Rs.) *</label>
                <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($bill_item['price']); ?>" required>
            </div>

            <div>
                <button type="submit" class="btn btn-submit">Update Item</button>
                <a href="list.php" class="btn btn-back">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
