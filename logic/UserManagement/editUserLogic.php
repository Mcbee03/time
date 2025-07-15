<?php
session_start();
include '../../config/db.php';

// Validate CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

// Sanitize inputs
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$pb_number = trim($_POST['pb_number']);
$member_id = trim($_POST['member_id']);
$name = trim($_POST['name']);
$committee_id = intval($_POST['committee_id']);

// Validate that either PB Number or Member ID exists
if ($id <= 0 || (!$pb_number && !$member_id) || !$name || !$committee_id) {
    echo json_encode(['success' => false, 'message' => 'Either PB Number or Member ID is required, and all other fields are mandatory']);
    exit;
}

// Update user in database
$stmt = $conn->prepare("
    UPDATE tbl_users
    SET PBNum = ?, MemberID = ?, Name = ?, Committee_ID = ?, updated_at = NOW()
    WHERE Id = ?
");
$stmt->bind_param("sssii", $pb_number, $member_id, $name, $committee_id, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error during update: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>