<?php
// api/notifications.php
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth_middleware.php';

$action = $_GET['action'] ?? '';
require_login();
$user_id = current_user_id();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'list') {
        $stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        send_json(['success' => true, 'notifications' => $stmt->get_result()->fetch_all(MYSQLI_ASSOC)]);
    } elseif ($action === 'unread_count') {
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        send_json(['success' => true, 'count' => (int)$res['count']]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'mark_read') {
        $input = get_json_input();
        $id = (int)($input['id'] ?? 0);
        if ($id) {
            $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $id, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
        }
        $stmt->execute();
        send_json(['success' => true]);
    }
}

send_json(['success' => false, 'message' => 'Invalid action'], 400);
?>
