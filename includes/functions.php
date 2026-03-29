<?php
// /opt/lampp/htdocs/NairaBridge/includes/functions.php

function sanitize_input($conn, $data) {
    if (is_array($data)) {
        foreach ($data as $key => $val) {
            $data[$key] = sanitize_input($conn, $val);
        }
    } else {
        $data = htmlspecialchars(strip_tags(trim($data)));
        $data = $conn->real_escape_string($data);
    }
    return $data;
}

function send_json($data, $status_code = 200) {
    http_response_code($status_code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function get_json_input() {
    $input = file_get_contents('php://input');
    return json_decode($input, true) ?? [];
}
?>
