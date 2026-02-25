<?php
// Database configuration
$host = 'localhost';
$db   = 'hospital_db';
$user = 'root';
$pass = 'root'; // set your password if any
$charset = 'utf8mb4';

// DSN (Data Source Name) for PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Helper functions for CRUD operations
function get_all($table, $pdo) {
    $stmt = $pdo->query("SELECT * FROM $table");
    return $stmt->fetchAll();
}

function get_by_id($table, $id, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function delete_record($table, $id, $pdo) {
    $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
    return $stmt->execute([$id]);
}

function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Redirect helper
function redirect($page) {
    header("Location: $page");
    exit;
}
?>
