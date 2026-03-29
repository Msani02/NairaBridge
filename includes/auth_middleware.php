<?php
// /opt/lampp/htdocs/NairaBridge/includes/auth_middleware.php
session_start();

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        send_json(['success' => false, 'message' => 'Unauthorized Access. Please log in.'], 401);
    }
}

function require_role($role) {
    require_login();
    if ($_SESSION['role'] !== $role && $_SESSION['role'] !== 'admin') { 
        // Admin overrides the check.
        send_json(['success' => false, 'message' => 'Forbidden: Insufficient permissions'], 403);
    }
}

function current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function current_user_role() {
    return $_SESSION['role'] ?? null;
}
?>
