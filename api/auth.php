<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth_middleware.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'register') {
        $input = get_json_input();
        $name = sanitize_input($conn, $input['name'] ?? '');
        $email = sanitize_input($conn, $input['email'] ?? '');
        $password = $input['password'] ?? '';
        $role = sanitize_input($conn, $input['role'] ?? 'user'); // In MVP, allow specifying role for testing
        
        if (!$name || !$email || !$password) {
            send_json(['success' => false, 'message' => 'Missing required fields'], 400);
        }

        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            send_json(['success' => false, 'message' => 'Email already registered'], 400);
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hash, $role);
        
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            
            // Create a wallet for the user automatically
            $wallet_stmt = $conn->prepare("INSERT INTO wallets (user_id, balance) VALUES (?, 0)");
            $wallet_stmt->bind_param("i", $user_id);
            $wallet_stmt->execute();
            
            send_json(['success' => true, 'message' => 'Registration successful']);
        } else {
            send_json(['success' => false, 'message' => 'Database error'], 500);
        }

    } elseif ($action === 'login') {
        $input = get_json_input();
        $email = sanitize_input($conn, $input['email'] ?? '');
        $password = $input['password'] ?? '';

        if (!$email || !$password) {
            send_json(['success' => false, 'message' => 'Missing email or password'], 400);
        }

        $stmt = $conn->prepare("SELECT id, name, password_hash, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['name'];

                send_json([
                    'success' => true, 
                    'message' => 'Login successful',
                    'user' => [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'role' => $user['role']
                    ]
                ]);
            }
        }
        send_json(['success' => false, 'message' => 'Invalid credentials'], 401);

    } elseif ($action === 'logout') {
        session_destroy();
        send_json(['success' => true, 'message' => 'Logged out successfully']);
    } elseif ($action === 'update_profile') {
        require_login();
        $user_id = current_user_id();
        $input = get_json_input();
        $name = sanitize_input($conn, $input['name'] ?? '');
        $email = sanitize_input($conn, $input['email'] ?? '');
        
        if (!$name || !$email) {
            send_json(['success' => false, 'message' => 'Name and email are required'], 400);
        }

        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['name'] = $name;
            send_json(['success' => true, 'message' => 'Profile updated successfully']);
        } else {
            send_json(['success' => false, 'message' => 'Update failed'], 500);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'me') {
        if (is_logged_in()) {
            send_json([
                'success' => true,
                'user' => [
                    'id' => $_SESSION['user_id'],
                    'name' => $_SESSION['name'],
                    'role' => $_SESSION['role']
                ]
            ]);
        } else {
            send_json(['success' => false, 'message' => 'Not logged in'], 401);
        }
    }
}

send_json(['success' => false, 'message' => 'Invalid action'], 400);
?>
