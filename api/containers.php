<?php
// api/containers.php
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth_middleware.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'list') {
        require_role('agent');
        $query = "SELECT c.*, COUNT(o.id) as order_count FROM containers c LEFT JOIN orders o ON c.id = o.container_id GROUP BY c.id ORDER BY created_at DESC";
        $result = $conn->query($query);
        $containers = [];
        while ($row = $result->fetch_assoc()) {
            $containers[] = $row;
        }
        send_json(['success' => true, 'containers' => $containers]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_role('agent');
    $input = get_json_input();
    
    if ($action === 'create') {
        $name = sanitize_input($conn, $input['name'] ?? '');
        if (!$name) { send_json(['success' => false, 'message' => 'Name required'], 400); }
        
        $stmt = $conn->prepare("INSERT INTO containers (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            send_json(['success' => true, 'message' => 'Container created']);
        }
        send_json(['success' => false, 'message' => 'Failed to create container'], 500);
        
    } elseif ($action === 'update_status') {
        $id = (int)($input['id'] ?? 0);
        $status = sanitize_input($conn, $input['status'] ?? '');
        $valid_statuses = ['Filling', 'Ready', 'Shipped', 'Arrived'];
        
        if (!in_array($status, $valid_statuses)) {
            send_json(['success' => false, 'message' => 'Invalid status'], 400);
        }
        
        $stmt = $conn->prepare("UPDATE containers SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        
        if ($stmt->execute()) {
            // Update orders inside container if status is Shipped/Arrived
            if ($status === 'Shipped') {
                $ostmt = $conn->prepare("UPDATE orders SET status = 'In Transit' WHERE container_id = ?");
                $ostmt->bind_param("i", $id);
                $ostmt->execute();
            } elseif ($status === 'Arrived') {
                $ostmt = $conn->prepare("UPDATE orders SET status = 'Delivered' WHERE container_id = ?");
                $ostmt->bind_param("i", $id);
                $ostmt->execute();
            }
            send_json(['success' => true, 'message' => 'Container status updated']);
        }
        send_json(['success' => false, 'message' => 'Failed to update status'], 500);
    }
}
send_json(['success' => false, 'message' => 'Invalid action'], 400);
?>
