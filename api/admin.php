<?php
// api/admin.php
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth_middleware.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require_role('admin');
    
    if ($action === 'users') {
        $result = $conn->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
        $users = [];
        while ($row = $result->fetch_assoc()) { $users[] = $row; }
        send_json(['success' => true, 'users' => $users]);
    } elseif ($action === 'transactions') {
        $query = "SELECT t.*, u.name as user_name FROM transactions t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC";
        $result = $conn->query($query);
        $txns = [];
        while ($row = $result->fetch_assoc()) { $txns[] = $row; }
        send_json(['success' => true, 'transactions' => $txns]);
    } elseif ($action === 'stats') {
        $users_count = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
        $orders_count = $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'];
        $rev = $conn->query("SELECT SUM(amount) as c FROM transactions WHERE type='credit'")->fetch_assoc()['c'] ?? 0;
        send_json(['success' => true, 'stats' => [
            'users' => $users_count,
            'orders' => $orders_count,
            'revenue' => $rev
        ]]);
    }
}
send_json(['success' => false, 'message' => 'Invalid action'], 400);
?>
