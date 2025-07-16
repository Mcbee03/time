<?php
session_start();
include '../../config/db.php';
date_default_timezone_set('Asia/Manila');
mysqli_query($conn, "SET time_zone = '+08:00'");
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
$updated_at = date('Y-m-d H:i:s'); // ✅ Use PHP timezone-safe timestamp
// Validate that either PB Number or Member ID exists
if ((!$pb_number && !$member_id) || !$name || !$committee_id) {
    echo json_encode(['success' => false, 'message' => 'Either PB Number or Member ID is required, and all other fields are mandatory']);
    exit;
}

if ($name && $committee_id) {
    $stmt = $conn->prepare("INSERT INTO tbl_users (PBNum, MemberID, Name, Committee_ID, created_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $pb_number, $member_id, $name, $committee_id, $created_at);

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