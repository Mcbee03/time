<?php
session_start();
header('Content-Type: application/json');
include '../../config/db.php';

// Verify CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'CSRF token validation failed']);
    exit;
}

// Validate deduction ID
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid deduction ID']);
    exit;
}

$id = (int)$_POST['id'];

try {
    // Check if deduction exists
    $checkStmt = $conn->prepare("SELECT Id FROM tbl_deduction WHERE Id = ?");
    $checkStmt->bind_param('i', $id);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows === 0) {
        echo json_encode(['success' => true, 'message' => 'Deduction already deleted']);
        exit;
    }
    $checkStmt->close();

    // Delete deduction
    $deleteStmt = $conn->prepare("DELETE FROM tbl_deduction WHERE Id = ?");
    $deleteStmt->bind_param('i', $id);

    if ($deleteStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Deduction deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }

    $deleteStmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
} finally {
    $conn->close();
}
?>
