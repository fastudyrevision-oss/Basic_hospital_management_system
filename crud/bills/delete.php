<?php
require '../../config.php';

$id = $_GET['id'] ?? null;

if ($id) {
    delete_record('bills', $id, $pdo);
    redirect('list.php?success=1');
} else {
    redirect('list.php');
}
?>
