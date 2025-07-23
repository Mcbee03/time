<?php
session_start();
include '../../config/db.php';
date_default_timezone_set('Asia/Manila');

function send_json($arr) {
    header('Content-Type: application/json');
    echo json_encode($arr);
    exit;
}

// CSRF Check
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    send_json(['success' => false, 'message' => 'Invalid CSRF token']);
}

// Validate required fields
$id = intval($_POST['id'] ?? 0);
error_log("Edit request for user ID: " . $id);

if ($id <= 0) {
    send_json(['success' => false, 'message' => 'Invalid user ID']);
}

// First check if user exists
try {
    $checkStmt = $conn->prepare("SELECT Id FROM tbl_users WHERE Id = ?");
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        send_json(['success' => false, 'message' => 'User not found']);
    }
    $checkStmt->close();
} catch (Exception $e) {
    send_json(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

// Get form data
$pb_number = trim($_POST['pb_number'] ?? '');
$member_id = trim($_POST['member_id'] ?? '');
$name = trim($_POST['name'] ?? '');
$committee_id = intval($_POST['committee_id'] ?? 0);

// Validate required fields
if (empty($name)) {
    send_json(['success' => false, 'message' => 'Name is required']);
}

if ($committee_id <= 0) {
    send_json(['success' => false, 'message' => 'Committee is required']);
}

if (empty($pb_number) && empty($member_id)) {
    send_json(['success' => false, 'message' => 'Either PB Number or Member ID is required']);
}

try {
    $updated_at = date('Y-m-d H:i:s');
    
    // Check if profile image was uploaded
    if (!empty($_FILES['profile']['tmp_name'])) {
        // Validate image
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        if ($_FILES['profile']['size'] > $maxFileSize) {
            send_json(['success' => false, 'message' => 'Profile picture is too large (max 5MB)']);
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($_FILES['profile']['type'], $allowedTypes)) {
            send_json(['success' => false, 'message' => 'Invalid image type. Only JPEG, PNG, JPG allowed']);
        }
        
        $profileData = file_get_contents($_FILES['profile']['tmp_name']);
        $sql = "UPDATE tbl_users 
                SET Profile = ?, PBNum = ?, MemberID = ?, Name = ?, Committee_Id = ?, updated_at = ? 
                WHERE Id = ?";
        $stmt = $conn->prepare($sql);
        $null = NULL;
        $stmt->bind_param("bsssisi", $null, $pb_number, $member_id, $name, $committee_id, $updated_at, $id);
        $stmt->send_long_data(0, $profileData);
    } else {
        $sql = "UPDATE tbl_users 
                SET PBNum = ?, MemberID = ?, Name = ?, Committee_Id = ?, updated_at = ? 
                WHERE Id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssisi", $pb_number, $member_id, $name, $committee_id, $updated_at, $id);
    }
    
    if ($stmt->execute()) {
        send_json(['success' => true, 'message' => 'User updated successfully']);
    } else {
        throw new Exception('Database error: ' . $stmt->error);
    }
} catch (Exception $e) {
    error_log("Error in editUserLogic: " . $e->getMessage());
    send_json(['success' => false, 'message' => 'Error updating user: ' . $e->getMessage()]);
} finally {
    if (isset($stmt)) $stmt->close();
    $conn->close();
}
?>