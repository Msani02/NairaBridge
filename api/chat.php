<?php
// api/chat.php
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth_middleware.php';

$action = $_GET['action'] ?? '';
require_login();
$user_id = current_user_id();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'get_history') {
        $other_id = (int)($_GET['other_id'] ?? 0);
        $order_id = (int)($_GET['order_id'] ?? 0);
        
        if (!$other_id) send_json(['success' => false, 'message' => 'Contact ID required'], 400);

        $sql = "SELECT m.*, u.name as sender_name FROM messages m 
                JOIN users u ON m.sender_id = u.id 
                WHERE ((sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?))";
        
        if ($order_id) {
            $sql .= " AND order_id = " . (int)$order_id;
        }
        $sql .= " ORDER BY created_at ASC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $user_id, $other_id, $other_id, $user_id);
        $stmt->execute();
        $messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Mark as read
        $upd = $conn->prepare("UPDATE messages SET is_read = 1 WHERE receiver_id = ? AND sender_id = ?");
        $upd->bind_param("ii", $user_id, $other_id);
        $upd->execute();
        
        send_json(['success' => true, 'messages' => $messages]);
    } elseif ($action === 'get_threads') {
        // Fetch users the current user has exchanged messages with
        $sql = "SELECT DISTINCT 
                CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END as contact_id,
                u.name as contact_name,
                u.role as contact_role,
                (SELECT content FROM messages 
                 WHERE (sender_id = ? AND receiver_id = contact_id) OR (sender_id = contact_id AND receiver_id = ?)
                 ORDER BY created_at DESC LIMIT 1) as last_message,
                (SELECT created_at FROM messages 
                 WHERE (sender_id = ? AND receiver_id = contact_id) OR (sender_id = contact_id AND receiver_id = ?)
                 ORDER BY created_at DESC LIMIT 1) as last_time
                FROM messages m
                JOIN users u ON u.id = (CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END)
                WHERE sender_id = ? OR receiver_id = ?
                ORDER BY last_time DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiiiiiii", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id);
        $stmt->execute();
        send_json(['success' => true, 'threads' => $stmt->get_result()->fetch_all(MYSQLI_ASSOC)]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'send') {
        $input = get_json_input();
        $receiver_id = (int)($input['receiver_id'] ?? 0);
        $order_id = (int)($input['order_id'] ?? 0);
        $content = sanitize_input($conn, $input['content'] ?? '');
        
        if (!$receiver_id || !$content) send_json(['success' => false, 'message' => 'Invalid data'], 400);
        
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, order_id, content) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $user_id, $receiver_id, $order_id, $content);
        
        if ($stmt->execute()) {
            send_json(['success' => true, 'message' => 'Message sent successfully']);
        }
        send_json(['success' => false, 'message' => 'Failed to send message'], 500);
    }
}

send_json(['success' => false, 'message' => 'Invalid request'], 400);
?>
