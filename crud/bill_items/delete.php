<?php
require '../../config.php';

$id = $_GET['id'] ?? null;

if ($id) {
    delete_record('bill_items', $id, $pdo);
    redirect('list.php?success=1');
} else {
    redirect('list.php');
}
?>
