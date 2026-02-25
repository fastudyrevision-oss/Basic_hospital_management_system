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

// Get all bills for this patient with items
$stmt = $pdo->prepare("
    SELECT * FROM bills 
    WHERE patient_id = ? 
    ORDER BY created_at DESC
");
$stmt->execute([$user['patient_id']]);
$bills = $stmt->fetchAll();

// Get bill items for each bill
foreach ($bills as &$bill) {
    $stmt = $pdo->prepare("
        SELECT bi.*, m.product_name, m.dosage, m.duration
        FROM bill_items bi
        JOIN medicine m ON bi.medicine_id = m.id
        WHERE bi.bill_id = ?
    ");
    $stmt->execute([$bill['id']]);
    $bill['items'] = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bills - Hospital Management System</title>
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

        .bill-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .bill-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .bill-id {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .badge-paid {
            background: #d4edda;
            color: #155724;
        }

        .badge-unpaid {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }

        .bill-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-item {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .info-item strong {
            display: block;
            color: #666;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .info-item span {
            color: #333;
            font-size: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-size: 13px;
            color: #666;
            border-bottom: 2px solid #e0e0e0;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }

        .total-row {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        .total-row td {
            border: none;
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
        <h2>💰 My Bills</h2>
        <a href="/dashboard.php" class="btn-back">← Back to Dashboard</a>
    </div>

    <div class="container">
        <div class="header-card">
            <h1>All Bills</h1>
            <p>View all your medical bills and payment status</p>
        </div>

        <?php if (empty($bills)): ?>
            <div class="empty-state">
                <h3>No bills found</h3>
                <p>You don't have any bills yet.</p>
            </div>
        <?php else: ?>
            <?php foreach ($bills as $bill): ?>
                <div class="bill-card">
                    <div class="bill-header">
                        <div class="bill-id">Bill #<?= $bill['id'] ?></div>
                        <span class="badge badge-<?= $bill['status'] ?>">
                            <?= ucfirst($bill['status']) ?>
                        </span>
                    </div>

                    <div class="bill-info">
                        <div class="info-item">
                            <strong>DATE</strong>
                            <span><?= date('M d, Y', strtotime($bill['created_at'])) ?></span>
                        </div>
                        <div class="info-item">
                            <strong>PAYMENT METHOD</strong>
                            <span><?= htmlspecialchars($bill['payment_method']) ?></span>
                        </div>
                        <div class="info-item">
                            <strong>TOTAL AMOUNT</strong>
                            <span style="font-size: 20px; font-weight: bold; color: #667eea;">
                                Rs. <?= number_format($bill['total_amount'], 2) ?>
                            </span>
                        </div>
                    </div>

                    <?php if (!empty($bill['items'])): ?>
                        <h4 style="margin-bottom: 10px; color: #333;">Bill Items</h4>
                        <table>
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Dosage</th>
                                    <th>Duration</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bill['items'] as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                                        <td><?= htmlspecialchars($item['dosage']) ?></td>
                                        <td><?= htmlspecialchars($item['duration']) ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td>Rs. <?= number_format($item['price'], 2) ?></td>
                                        <td>Rs. <?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="total-row">
                                    <td colspan="5" style="text-align: right;">TOTAL:</td>
                                    <td>Rs. <?= number_format($bill['total_amount'], 2) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>