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
$id           = isset($_POST['id']) ? intval($_POST['id']) : 0;
$pb_number    = trim($_POST['pb_number']    ?? '');
$member_id    = trim($_POST['member_id']    ?? '');
$name         = trim($_POST['name']         ?? '');
$committee_id = intval($_POST['committee_id'] ?? 0);
$updated_at   = date('Y-m-d H:i:s');

// Validate
if ($id <= 0 || (!$pb_number && !$member_id) || !$name || !$committee_id) {
    send_json([
        'success' => false,
        'message' => 'Either PB Number or Member ID is required, and all other fields are mandatory'
    ]);
}

$sql = "
    UPDATE tbl_users
    SET PBNum = ?, MemberID = ?, Name = ?, Committee_ID = ?, updated_at = ?
    WHERE Id = ?
";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    send_json(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
}

// Correct order: s, s, s, i, s, i
$stmt->bind_param("sssisi", $pb_number, $member_id, $name, $committee_id, $updated_at, $id);

if ($stmt->execute()) {
    send_json(['success' => true, 'message' => 'User updated successfully']);
} else {
    send_json(['success' => false, 'message' => 'Database error during update: ' . $stmt->error]);
}
?>
