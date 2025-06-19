<?php
session_start();
header('Content-Type: application/json');
include '../../config/db.php';

// Verify CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'CSRF token validation failed']);
    exit;
}

// Validate admin ID
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid admin ID']);
    exit;
}

$id = (int)$_POST['id'];

// Prevent deleting current admin
if (isset($_SESSION['admin_id']) && $id === $_SESSION['admin_id']) {
    echo json_encode(['success' => false, 'message' => 'You cannot delete your own account']);
    exit;
}

try {
    // Check if admin exists
    $checkStmt = $conn->prepare("SELECT Id FROM tbl_adminlogin WHERE Id = ?");
    $checkStmt->bind_param('i', $id);
    $checkStmt->execute();
    $checkStmt->store_result();
    
    if ($checkStmt->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Admin not found']);
        exit;
    }
    $checkStmt->close();

    // Delete admin
    $deleteStmt = $conn->prepare("DELETE FROM tbl_adminlogin WHERE Id = ?");
    $deleteStmt->bind_param('i', $id);

    if ($deleteStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Admin deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }

    $deleteStmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
} finally {
    $conn->close();
}
