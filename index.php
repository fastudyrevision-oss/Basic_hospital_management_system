<?php
require 'config.php';
require 'auth_helper.php';

// If user is logged in, redirect based on role
if (is_logged_in()) {
    $user = get_logged_in_user();
    if ($user['role'] === 'admin') {
        header('Location: /admin.php');
    } else {
        header('Location: /dashboard.php');
    }
    exit;
}

// If not logged in, show landing page
header('Location: /home.php');
exit;
?>