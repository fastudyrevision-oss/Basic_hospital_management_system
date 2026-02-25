<?php
require '../../config.php';

$id = $_GET['id'] ?? null;
$bill = get_by_id('bills', $id, $pdo);
$error = '';

if (!$bill) {
    redirect('list.php');
}

$patients = get_all('patients', $pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = (int)($_POST['patient_id'] ?? 0);
    $total_amount = (float)($_POST['total_amount'] ?? 0);
    $payment_method = sanitize_input($_POST['payment_method'] ?? '');
    $status = sanitize_input($_POST['status'] ?? 'unpaid');

    if (!$patient_id || $total_amount <= 0) {
        $error = "Patient and valid amount are required!";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE bills SET patient_id = ?, total_amount = ?, payment_method = ?, status = ? WHERE id = ?");
            $stmt->execute([$patient_id, $total_amount, $payment_method, $status, $id]);
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
    <title>Edit Bill - Hospital Management System</title>
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
        <h1>💰 Edit Bill</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="patient_id">Patient *</label>
                <select id="patient_id" name="patient_id" required>
                    <option value="">Select a patient</option>
                    <?php foreach ($patients as $pat): ?>
                    <option value="<?php echo $pat['id']; ?>" <?php echo $pat['id'] === $bill['patient_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($pat['first_name'] . ' ' . $pat['last_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="total_amount">Total Amount (Rs.) *</label>
                <input type="number" id="total_amount" name="total_amount" step="0.01" min="0" value="<?php echo htmlspecialchars($bill['total_amount']); ?>" required>
            </div>

            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <select id="payment_method" name="payment_method">
                    <option value="">Select payment method</option>
                    <option value="Cash" <?php echo $bill['payment_method'] === 'Cash' ? 'selected' : ''; ?>>Cash</option>
                    <option value="Card" <?php echo $bill['payment_method'] === 'Card' ? 'selected' : ''; ?>>Card</option>
                    <option value="Bank Transfer" <?php echo $bill['payment_method'] === 'Bank Transfer' ? 'selected' : ''; ?>>Bank Transfer</option>
                    <option value="Cheque" <?php echo $bill['payment_method'] === 'Cheque' ? 'selected' : ''; ?>>Cheque</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" required>
                    <option value="unpaid" <?php echo $bill['status'] === 'unpaid' ? 'selected' : ''; ?>>Unpaid</option>
                    <option value="paid" <?php echo $bill['status'] === 'paid' ? 'selected' : ''; ?>>Paid</option>
                    <option value="pending" <?php echo $bill['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                </select>
            </div>

            <div>
                <button type="submit" class="btn btn-submit">Update Bill</button>
                <a href="list.php" class="btn btn-back">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
