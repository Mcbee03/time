<?php
session_start();
include '../../config/db.php';
date_default_timezone_set('Asia/Manila');
mysqli_query($conn, "SET time_zone = '+08:00'");

function send_json($arr) {
    header('Content-Type: application/json');
    echo json_encode($arr);
    exit;
}

// Validate CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    send_json(['success' => false, 'message' => 'Invalid CSRF token']);
}

// Sanitize inputs
$pb_number    = trim($_POST['pb_number']    ?? '');
$member_id    = trim($_POST['member_id']    ?? '');
$name         = trim($_POST['name']         ?? '');
$committee_id = intval($_POST['committee_id'] ?? 0);
$created_at   = date('Y-m-d H:i:s');

// Validate
if ((!$pb_number && !$member_id) || !$name || !$committee_id) {
    send_json(['success' => false, 'message' => 'Either PB Number or Member ID is required, and all other fields are mandatory']);
}

// Handle profile picture
$profileData = null;
if (!empty($_FILES['profile']['tmp_name'])) {
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    if ($_FILES['profile']['size'] > $maxFileSize) {
        send_json(['success' => false, 'message' => 'Profile picture is too large (max 5MB)']);
    }
    $profileData = file_get_contents($_FILES['profile']['tmp_name']);
}

$sql = "INSERT INTO tbl_users (Profile, PBNum, MemberID, Name, Committee_ID, created_at) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    send_json(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
}

// BLOB binding
$null = NULL;
$stmt->bind_param("bsssis", $null, $pb_number, $member_id, $name, $committee_id, $created_at);
if ($profileData) {
    $stmt->send_long_data(0, $profileData);
}

if ($stmt->execute()) {
    send_json(['success' => true, 'message' => 'User added successfully']);
} else {
    send_json(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
}
?>