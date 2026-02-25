<?php
session_start();

// Check if user is logged in
function is_logged_in()
{
    return isset($_SESSION['user_id']) && isset($_SESSION['user_email']);
}

// Check if user has specific role
function has_role($role)
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
}

// Require login - redirect to login page if not logged in
function require_login()
{
    if (!is_logged_in()) {
        header('Location: /auth/login.php');
        exit;
    }
}

// Require specific role
function require_role($role)
{
    require_login();
    if (!has_role($role)) {
        header('Location: /dashboard.php');
        exit;
    }
}

// Get logged in user data
function get_logged_in_user()
{
    if (!is_logged_in()) {
        return null;
    }

    return [
        'id' => $_SESSION['user_id'],
        'email' => $_SESSION['user_email'],
        'role' => $_SESSION['user_role'],
        'patient_id' => $_SESSION['patient_id'] ?? null,
        'doctor_id' => $_SESSION['doctor_id'] ?? null,
        'staff_id' => $_SESSION['staff_id'] ?? null
    ];
}

// Logout user
function logout_user()
{
    session_destroy();
    header('Location: /auth/login.php');
    exit;
}
?>