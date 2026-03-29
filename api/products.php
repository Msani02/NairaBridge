<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth_middleware.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'list') {
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $query = "SELECT p.*, m.name as merchant_name, u.name as agent_name 
                  FROM products p 
                  LEFT JOIN merchants m ON p.merchant_id = m.id 
                  LEFT JOIN users u ON p.agent_id = u.id";
        
        if ($search) {
            $query .= " WHERE p.name LIKE ?";
            $query .= " ORDER BY p.created_at DESC";
            $stmt = $conn->prepare($query);
            $searchTerm = "%$search%";
            $stmt->bind_param("s", $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $query .= " ORDER BY p.created_at DESC";
            $result = $conn->query($query);
        }
        
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        send_json(['success' => true, 'products' => $products]);
    } elseif ($action === 'get') {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $conn->prepare("SELECT p.*, m.name as merchant_name FROM products p LEFT JOIN merchants m ON p.merchant_id = m.id WHERE p.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($product = $result->fetch_assoc()) {
            send_json(['success' => true, 'product' => $product]);
        } else {
            send_json(['success' => false, 'message' => 'Product not found'], 404);
        }
    } elseif ($action === 'get_reviews') {
        $product_id = (int)($_GET['product_id'] ?? 0);
        $stmt = $conn->prepare("SELECT r.*, u.name as user_name FROM product_reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        send_json(['success' => true, 'reviews' => $stmt->get_result()->fetch_all(MYSQLI_ASSOC)]);
    } elseif ($action === 'get_wishlist') {
        require_login();
        $user_id = current_user_id();
        $stmt = $conn->prepare("SELECT p.*, m.name as merchant_name FROM products p JOIN wishlist w ON p.id = w.product_id LEFT JOIN merchants m ON p.merchant_id = m.id WHERE w.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        send_json(['success' => true, 'wishlist' => $stmt->get_result()->fetch_all(MYSQLI_ASSOC)]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create') {
        require_role('agent'); 
        $agent_id = current_user_id();
        $merchant_id = sanitize_input($conn, $_POST['merchant_id'] ?? '');
        $name = sanitize_input($conn, $_POST['name'] ?? '');
        $description = sanitize_input($conn, $_POST['description'] ?? '');
        $price_naira = sanitize_input($conn, $_POST['price_naira'] ?? '');
        if (!$merchant_id || !$name || !$price_naira) {
            send_json(['success' => false, 'message' => 'Missing required fields'], 400);
        }
        $image_url = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../assets/images/products/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $file_name = time() . '_' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $file_name)) {
                $image_url = 'assets/images/products/' . $file_name;
            }
        }
        $stmt = $conn->prepare("INSERT INTO products (merchant_id, agent_id, name, description, price_naira, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissds", $merchant_id, $agent_id, $name, $description, $price_naira, $image_url);
        if ($stmt->execute()) send_json(['success' => true, 'message' => 'Product created', 'product_id' => $stmt->insert_id]);
        else send_json(['success' => false, 'message' => 'Failed to create product'], 500);
    } elseif ($action === 'add_review') {
        require_login();
        $user_id = current_user_id();
        $input = get_json_input();
        $product_id = (int)($input['product_id'] ?? 0);
        $rating = (int)($input['rating'] ?? 0);
        $comment = sanitize_input($conn, $input['comment'] ?? '');
        if (!$product_id || $rating < 1 || $rating > 5) send_json(['success' => false, 'message' => 'Invalid data'], 400);
        $stmt = $conn->prepare("INSERT INTO product_reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE rating = ?, comment = ?");
        $stmt->bind_param("iiisis", $user_id, $product_id, $rating, $comment, $rating, $comment);
        if ($stmt->execute()) send_json(['success' => true, 'message' => 'Review submitted']);
        else send_json(['success' => false, 'message' => 'Failed to submit review'], 500);
    } elseif ($action === 'toggle_wishlist') {
        require_login();
        $user_id = current_user_id();
        $input = get_json_input();
        $product_id = (int)($input['product_id'] ?? 0);
        $check = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
        $check->bind_param("ii", $user_id, $product_id);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
            $message = "Removed from wishlist";
        } else {
            $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
            $message = "Added to wishlist";
        }
        $stmt->bind_param("ii", $user_id, $product_id);
        if ($stmt->execute()) send_json(['success' => true, 'message' => $message]);
        else send_json(['success' => false, 'message' => 'Failed to update wishlist'], 500);
    }
}
send_json(['success' => false, 'message' => 'Invalid action'], 400);
?>
