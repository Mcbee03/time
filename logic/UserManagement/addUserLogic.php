<?php
session_start();
include '../../config/db.php';

// Validate CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

// Sanitize inputs
$pb_number = trim($_POST['pb_number']);
$member_id = trim($_POST['member_id']);
$name = trim($_POST['name']);
$committee_id = intval($_POST['committee_id']);

if ($pb_number && $member_id && $name && $committee_id) {
    $stmt = $conn->prepare("INSERT INTO tbl_users (PBNum, MemberID, Name, Committee_ID, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssi", $pb_number, $member_id, $name, $committee_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
}
?>
