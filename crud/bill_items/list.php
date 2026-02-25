<?php
require '../../config.php';

$stmt = $pdo->query("
    SELECT bi.*, b.id as bill_id, m.product_name, p.first_name as pat_first, p.last_name as pat_last
    FROM bill_items bi
    JOIN bills b ON bi.bill_id = b.id
    JOIN medicine m ON bi.medicine_id = m.id
    JOIN patients p ON b.patient_id = p.id
");
$bill_items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Items - Hospital Management System</title>
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
            <h1>📋 Bill Items</h1>
            <div>
                <a href="create.php" class="btn btn-add">+ Add Item to Bill</a>
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
                    <th>Bill ID</th>
                    <th>Medicine</th>
                    <th>Quantity</th>
                    <th>Price (Rs.)</th>
                    <th>Total (Rs.)</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bill_items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['id']); ?></td>
                    <td><?php echo htmlspecialchars($item['bill_id']); ?></td>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td><?php echo 'Rs. ' . number_format($item['price'], 2); ?></td>
                    <td><?php echo 'Rs. ' . number_format($item['quantity'] * $item['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($item['created_at']); ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?php echo $item['id']; ?>" class="btn btn-edit">Edit</a>
                        <a href="delete.php?id=<?php echo $item['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (empty($bill_items)): ?>
            <p style="text-align: center; margin-top: 20px; color: #666;">No bill items found. <a href="create.php">Add one now</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
