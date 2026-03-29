<?php
// api/payment.php
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth_middleware.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_login();
    $user_id = current_user_id();
    $input = get_json_input();
    
    if ($action === 'init_interswitch') {
        $amount = (float)($input['amount'] ?? 0);
        if ($amount <= 100) {
            send_json(['success' => false, 'message' => 'Minimum deposit is ₦100'], 400);
        }
        
        $txn_ref = 'NB-' . strtoupper(uniqid());
        $amount_kobo = (int)($amount * 100);
        
        $merchant_code = 'MX21696';
        $pay_item_id = '4177785';
        $secret_key = 'ajkdpGiF6PHVrwK';
        
        $redirect_url = "http://localhost/NairaBridge/"; 
        
        // Final standard order from docs: txn_ref + merchant_code + pay_item_id + amount + redirect_url + secret_key
        $hash_string = $txn_ref . $merchant_code . $pay_item_id . $amount_kobo . $redirect_url . $secret_key;
        $hash = strtoupper(hash('sha512', $hash_string));
        
        send_json([
            'success' => true,
            'params' => [
                'merchant_code' => $merchant_code,
                'pay_item_id' => $pay_item_id,
                'site_redirect_url' => $redirect_url,
                'txn_ref' => $txn_ref,
                'amount' => $amount_kobo,
                'currency' => '566',
                'hash' => $hash,
                'test_url' => 'https://sandbox.interswitchng.com/collections/w/pay'
            ]
        ]);
    } elseif ($action === 'init_interswitch_order') {
        $order_id = (int)($input['order_id'] ?? 0);
        
        $stmt = $conn->prepare("SELECT total_amount FROM orders WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $order_id, $user_id);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();
        
        if (!$order) send_json(['success' => false, 'message' => 'Order not found'], 404);
        
        $amount_kobo = (int)($order['total_amount'] * 100);
        $txn_ref = 'NB-ORD-' . strtoupper(uniqid());
        
        $merchant_code = 'MX21696';
        $pay_item_id = '4177785';
        $secret_key = 'ajkdpGiF6PHVrwK';
        
        $redirect_url = "http://localhost/NairaBridge/"; 
        
        $hash_string = $txn_ref . $merchant_code . $pay_item_id . $amount_kobo . $redirect_url . $secret_key;
        $hash = strtoupper(hash('sha512', $hash_string));
        
        send_json([
            'success' => true,
            'params' => [
                'merchant_code' => $merchant_code,
                'pay_item_id' => $pay_item_id,
                'site_redirect_url' => $redirect_url,
                'txn_ref' => $txn_ref,
                'amount' => $amount_kobo,
                'currency' => '566',
                'hash' => $hash,
                'test_url' => 'https://sandbox.interswitchng.com/collections/w/pay'
            ]
        ]);
    } elseif ($action === 'verify_interswitch') {
        $txn_ref = $input['txn_ref'] ?? '';
        $amount = (float)($input['amount'] ?? 0);
        $order_id = (int)($input['order_id'] ?? 0);
        $amount_kobo = (int)($amount * 100);
        
        if (!$txn_ref) send_json(['success' => false, 'message' => 'Missing reference'], 400);
        
        $merchant_code = 'MX21696';
        $secret_key = 'ajkdpGiF6PHVrwK';
        
        $hash_string = $merchant_code . $txn_ref . $secret_key;
        $hash = hash('sha512', $hash_string);
        
        $url = "https://sandbox.interswitchng.com/collections/api/v1/gettransaction.json?merchantcode=$merchant_code&transactionreference=$txn_ref&amount=$amount_kobo";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Hash: $hash", "User-Agent: Mozilla/5.0"]);
        $response = curl_exec($ch);
        $res_data = json_decode($response, true);
        curl_close($ch);
        
        if ($res_data && isset($res_data['ResponseCode']) && $res_data['ResponseCode'] === '00') {
            $conn->begin_transaction();
            try {
                $check = $conn->prepare("SELECT id FROM transactions WHERE reference = ?");
                $check->bind_param("s", $txn_ref);
                $check->execute();
                if ($check->get_result()->num_rows > 0) {
                    send_json(['success' => true, 'message' => 'Already processed']);
                }

                $amt_final = (float)($res_data['Amount'] / 100);
                
                if ($order_id > 0) {
                    $stmt = $conn->prepare("UPDATE orders SET status = 'Paid' WHERE id = ? AND user_id = ?");
                    $stmt->bind_param("ii", $order_id, $user_id);
                    $stmt->execute();
                    
                    $stmt = $conn->prepare("INSERT INTO transactions (user_id, amount, type, reference, status) VALUES (?, ?, 'debit', ?, 'success')");
                    $stmt->bind_param("ids", $user_id, $amt_final, $txn_ref);
                    $stmt->execute();
                } else {
                    $stmt = $conn->prepare("UPDATE wallets SET balance = balance + ? WHERE user_id = ?");
                    $stmt->bind_param("di", $amt_final, $user_id);
                    $stmt->execute();
                    
                    $stmt = $conn->prepare("INSERT INTO transactions (user_id, amount, type, reference, status) VALUES (?, ?, 'credit', ?, 'success')");
                    $stmt->bind_param("ids", $user_id, $amt_final, $txn_ref);
                    $stmt->execute();
                }
                
                $conn->commit();
                send_json(['success' => true, 'message' => 'Payment verified successfully']);
            } catch (Exception $e) {
                $conn->rollback();
                send_json(['success' => false, 'message' => 'Database error'], 500);
            }
        } else {
            send_json(['success' => false, 'message' => 'Payment verification failed', 'raw' => $res_data], 400);
        }
    } elseif ($action === 'pay_order') {
        $order_id = (int)($input['order_id'] ?? 0);
        $stmt = $conn->prepare("SELECT total_amount FROM orders WHERE id = ? AND user_id = ? AND status = 'Pending Payment'");
        $stmt->bind_param("ii", $order_id, $user_id);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();
        
        if (!$order) send_json(['success' => false, 'message' => 'Order not found or already paid'], 404);
        
        $w_stmt = $conn->prepare("SELECT balance FROM wallets WHERE user_id = ?");
        $w_stmt->bind_param("i", $user_id);
        $w_stmt->execute();
        $wallet = $w_stmt->get_result()->fetch_assoc();
        
        if ($wallet['balance'] < $order['total_amount']) {
            send_json(['success' => false, 'message' => 'Insufficient wallet balance'], 400);
        }
        
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("UPDATE wallets SET balance = balance - ? WHERE user_id = ?");
            $stmt->bind_param("di", $order['total_amount'], $user_id);
            $stmt->execute();
            
            $ref = 'PAY-' . strtoupper(uniqid());
            $stmt = $conn->prepare("INSERT INTO transactions (user_id, amount, type, reference, status) VALUES (?, ?, 'debit', ?, 'success')");
            $stmt->bind_param("ids", $user_id, $order['total_amount'], $ref);
            $stmt->execute();
            
            $stmt = $conn->prepare("UPDATE orders SET status = 'Paid' WHERE id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            
            $conn->commit();
            send_json(['success' => true, 'message' => 'Wallet payment successful']);
        } catch (Exception $e) {
            $conn->rollback();
            send_json(['success' => false, 'message' => 'Payment failed'], 500);
        }
    } else {
        send_json(['success' => false, 'message' => 'Invalid action'], 400);
    }
} else {
    send_json(['success' => false, 'message' => 'Only POST allowed'], 405);
}
?>
