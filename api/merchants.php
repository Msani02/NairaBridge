<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth_middleware.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'list') {
        require_role('agent'); // agents to see their merchants
        $agent_id = current_user_id();
        $is_admin = current_user_role() === 'admin';
        
        if ($is_admin) {
            $query = "SELECT * FROM merchants ORDER BY created_at DESC";
        } else {
            $query = "SELECT * FROM merchants WHERE created_by_agent_id = " . (int)$agent_id . " ORDER BY created_at DESC";
        }
        $result = $conn->query($query);
        $merchants = [];
        while ($row = $result->fetch_assoc()) {
            $merchants[] = $row;
        }
        send_json(['success' => true, 'merchants' => $merchants]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create') {
        require_role('agent');
        $input = get_json_input();
        $name = sanitize_input($conn, $input['name'] ?? '');
        $contact_info = sanitize_input($conn, $input['contact_info'] ?? '');
        $agent_id = current_user_id();
        
        if (!$name) {
            send_json(['success' => false, 'message' => 'Merchant name required'], 400);
        }
        
        $stmt = $conn->prepare("INSERT INTO merchants (name, contact_info, created_by_agent_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $contact_info, $agent_id);
        
        if ($stmt->execute()) {
            send_json(['success' => true, 'message' => 'Merchant created']);
        } else {
            send_json(['success' => false, 'message' => 'Failed to create merchant'], 500);
        }
    }
}

send_json(['success' => false, 'message' => 'Invalid action'], 400);
?>
