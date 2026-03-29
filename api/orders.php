<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth_middleware.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'my_orders') {
        require_login();
        $user_id = current_user_id();
        $stmt = $conn->prepare("SELECT o.*, 
                (SELECT agent_id FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = o.id LIMIT 1) as agent_id,
                (SELECT u.name FROM order_items oi JOIN products p ON oi.product_id = p.id JOIN users u ON p.agent_id = u.id WHERE oi.order_id = o.id LIMIT 1) as agent_name
                FROM orders o WHERE o.user_id = ? ORDER BY o.created_at DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $items_stmt = $conn->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
            $items_stmt->bind_param("i", $row['id']);
            $items_stmt->execute();
            $items = [];
            $res = $items_stmt->get_result();
            while($item = $res->fetch_assoc()) { $items[] = $item; }
            $row['items'] = $items;
            $orders[] = $row;
        }
        send_json(['success' => true, 'orders' => $orders]);
    } elseif ($action === 'list') {
        require_role('agent');
        $query = "SELECT o.*, u.name as user_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC";
        $result = $conn->query($query);
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        send_json(['success' => true, 'orders' => $orders]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'place') {
        require_login();
        $user_id = current_user_id();
        $input = get_json_input();
        $items = $input['items'] ?? []; // [{product_id, quantity}]
        
        if (empty($items)) {
            send_json(['success' => false, 'message' => 'Cart is empty'], 400);
        }
        
        $total_amount = 0;
        $order_items = [];
        
        foreach ($items as $item) {
            $pid = (int)$item['product_id'];
            $qty = (int)$item['quantity'];
            $stmt = $conn->prepare("SELECT price_naira FROM products WHERE id = ?");
            $stmt->bind_param("i", $pid);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($p = $res->fetch_assoc()) {
                $total_amount += ($p['price_naira'] * $qty);
                $order_items[] = ['product_id' => $pid, 'quantity' => $qty, 'price' => $p['price_naira']];
            }
        }
        
        $reference = 'ORD-' . strtoupper(uniqid());
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, reference) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $user_id, $total_amount, $reference);
        
        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;
            foreach ($order_items as $oi) {
                $istmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $istmt->bind_param("iiid", $order_id, $oi['product_id'], $oi['quantity'], $oi['price']);
                $istmt->execute();
            }
            send_json(['success' => true, 'message' => 'Order placed successfully', 'order_id' => $order_id, 'reference' => $reference]);
        }
        send_json(['success' => false, 'message' => 'Failed to place order'], 500);
    } elseif ($action === 'update_status') {
        require_role('agent');
        $input = get_json_input();
        $order_id = (int)($input['order_id'] ?? 0);
        $status = sanitize_input($conn, $input['status'] ?? '');
        $container_id = $input['container_id'] ?? null;
        
        $valid_statuses = ['Pending Payment', 'Paid', 'Escrow', 'Processing', 'At Warehouse', 'In Container', 'Shipped', 'Delivered'];
        if (!in_array($status, $valid_statuses)) {
            send_json(['success' => false, 'message' => 'Invalid status'], 400);
        }
        
        if ($container_id) {
            $stmt = $conn->prepare("UPDATE orders SET status = ?, container_id = ? WHERE id = ?");
            $stmt->bind_param("sii", $status, $container_id, $order_id);
        } else {
            $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $order_id);
        }
        
        if ($stmt->execute()) {
            send_json(['success' => true, 'message' => 'Order updated']);
        } else {
            send_json(['success' => false, 'message' => 'Failed to update order'], 500);
        }
    }
}

send_json(['success' => false, 'message' => 'Invalid action'], 400);
?>
