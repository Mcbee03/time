<?php
session_start();
header('Content-Type: application/json');
include '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    exit('Forbidden');
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM tbl_users WHERE Id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'data' => $row]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}

$stmt->close();
$conn->close();
