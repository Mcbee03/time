<?php
session_start();
include '../../config/db.php';

header('Content-Type: application/json');
$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    $id = $_POST['id'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $csrf)) {
        $response['message'] = 'Invalid CSRF token.';
    } elseif (!empty($id)) {
        $stmt = $conn->prepare("DELETE FROM tbl_users WHERE Id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['message'] = 'Failed to delete user.';
        }

        $stmt->close();
    } else {
        $response['message'] = 'Missing user ID.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
