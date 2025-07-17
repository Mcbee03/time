<?php
session_start();
include '../../config/db.php';

date_default_timezone_set('Asia/Manila');
mysqli_query($conn, "SET time_zone = '+08:00'");

// JSON response helper (optional but neat)
function send_json($arr) {
    header('Content-Type: application/json');
    echo json_encode($arr);
    exit;
}

// Validate CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    send_json(['success' => false, 'message' => 'Invalid CSRF token']);
}

// Sanitize inputs (use null coalesce para iwas undefined index warnings)
$pb_number    = trim($_POST['pb_number']    ?? '');
$member_id    = trim($_POST['member_id']    ?? '');
$name         = trim($_POST['name']         ?? '');
$committee_id = intval($_POST['committee_id'] ?? 0);

// Manila timestamp
$created_at = date('Y-m-d H:i:s');

// Validate: need at least PBNum or MemberID + required fields
if ((!$pb_number && !$member_id) || !$name || !$committee_id) {
    send_json([
        'success' => false,
        'message' => 'Either PB Number or Member ID is required, and all other fields are mandatory'
    ]);
}

$sql = "INSERT INTO tbl_users (PBNum, MemberID, Name, Committee_ID, created_at) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    send_json(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
}

// 5 placeholders => 5 type chars
$stmt->bind_param("sssis", $pb_number, $member_id, $name, $committee_id, $created_at);

if ($stmt->execute()) {
    send_json(['success' => true, 'message' => 'User added successfully']);
} else {
    send_json(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
}
?>
