<?php
// api/wallet.php
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth_middleware.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require_login();
    $user_id = current_user_id();
    
    if ($action === 'balance') {
        $stmt = $conn->prepare("SELECT balance FROM wallets WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($w = $res->fetch_assoc()) {
            send_json(['success' => true, 'balance' => $w['balance']]);
        }
        send_json(['success' => false, 'message' => 'Wallet not found'], 404);
    } elseif ($action === 'history') {
        $stmt = $conn->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $history = [];
        while($row = $res->fetch_assoc()) { $history[] = $row; }
        send_json(['success' => true, 'history' => $history]);
    }
}
send_json(['success' => false, 'message' => 'Invalid action'], 400);
?>
