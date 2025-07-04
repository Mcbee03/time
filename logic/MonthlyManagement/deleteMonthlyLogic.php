<?php
session_start();
incl '../../config/db.php';

// Verify CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    die(json_encode(['success' => false, 'message' => 'Invalid CSRF token']));
}

// Verify admin session
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    die(json_encode(['success' => false, 'message' => 'Unauthorized access']));
}

// Get the ID to delete
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid ID']));
}

try {
    // Start transaction
    $conn->begin_transaction();

    // First delete related records in tbl_user_deduction
    $deleteDeductions = $conn->prepare("DELETE FROM tbl_user_deduction WHERE allowance_id = ?");
    $deleteDeductions->bind_param('i', $id);
    $deleteDeductions->execute();

    // Then delete the monthly allowance record
    $deleteAllowance = $conn->prepare("DELETE FROM tbl_monthly_allowance WHERE id = ?");
    $deleteAllowance->bind_param('i', $id);
    $deleteAllowance->execute();

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Allowance deleted successfully',
        'toast' => [
            'title' => 'Success',
            'message' => 'Allowance record has been deleted',
            'type' => 'success'
        ]
    ]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error deleting record: ' . $e->getMessage(),
        'toast' => [
            'title' => 'Error',
            'message' => 'Failed to delete allowance',
            'type' => 'error'
        ]
    ]);
}
?>