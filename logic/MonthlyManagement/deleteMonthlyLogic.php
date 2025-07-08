<?php
session_start();
include '../../config/db.php';

header('Content-Type: application/json');

// === Validate CSRF token ===
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    die(json_encode([
        'success' => false, 
        'message' => 'Invalid CSRF token',
        'toast' => [
            'title' => 'Error',
            'message' => 'Security validation failed',
            'type' => 'error'
        ]
    ]));
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    http_response_code(400);
    die(json_encode([
        'success' => false, 
        'message' => 'Invalid ID',
        'toast' => [
            'title' => 'Error',
            'message' => 'Invalid record ID',
            'type' => 'error'
        ]
    ]));
}

try {
    // First get the DateFrom and DateTo for this allowance record
    $getDatesStmt = $conn->prepare("SELECT DateFrom, DateTo FROM tbl_monthly_allowance WHERE Id = ?");
    $getDatesStmt->bind_param('i', $id);
    $getDatesStmt->execute();
    $datesResult = $getDatesStmt->get_result();
    
    if ($datesResult->num_rows === 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Record already deleted',
            'toast' => [
                'title' => 'Already Deleted',
                'message' => 'This record was already removed',
                'type' => 'info'
            ]
        ]);
        exit;
    }
    
    $dates = $datesResult->fetch_assoc();
    $dateFrom = $dates['DateFrom'];
    $dateTo = $dates['DateTo'];
    $getDatesStmt->close();

    $conn->begin_transaction();

    // Get all Users_Id for this monthly period
    $getUsersStmt = $conn->prepare("SELECT Users_Id FROM tbl_monthly_allowance WHERE DateFrom = ? AND DateTo = ?");
    $getUsersStmt->bind_param('ss', $dateFrom, $dateTo);
    $getUsersStmt->execute();
    $usersResult = $getUsersStmt->get_result();
    
    $userIds = [];
    while ($row = $usersResult->fetch_assoc()) {
        $userIds[] = $row['Users_Id'];
    }
    $getUsersStmt->close();

    // Delete all user deductions for these users (if any exist)
    if (!empty($userIds)) {
        $placeholders = implode(',', array_fill(0, count($userIds), '?'));
        
        // First delete all user deductions that were created for this monthly period
        $deleteDeductions = $conn->prepare("
            DELETE ud FROM tbl_user_deduction ud
            JOIN tbl_monthly_allowance ma ON ud.Id = ma.UserDeduction_Id
            WHERE ma.DateFrom = ? AND ma.DateTo = ?
        ");
        $deleteDeductions->bind_param('ss', $dateFrom, $dateTo);
        $deleteDeductions->execute();
        
        // Then delete any remaining deductions for these users that might be linked
        $deleteUserDeductions = $conn->prepare("
            DELETE FROM tbl_user_deduction 
            WHERE Users_Id IN ($placeholders)
        ");
        $deleteUserDeductions->bind_param(str_repeat('i', count($userIds)), ...$userIds);
        $deleteUserDeductions->execute();
    }

    // Delete all monthly allowance records for this period
    $deleteAllowances = $conn->prepare("DELETE FROM tbl_monthly_allowance WHERE DateFrom = ? AND DateTo = ?");
    $deleteAllowances->bind_param('ss', $dateFrom, $dateTo);
    $deleteAllowances->execute();

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Monthly allowance and all related deductions deleted successfully',
        'toast' => [
            'title' => 'Deleted',
            'message' => 'All records and deductions for this monthly period were removed.',
            'type' => 'success'
        ]
    ]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage(),
        'toast' => [
            'title' => 'Error',
            'message' => 'Failed to delete monthly records. Try again.',
            'type' => 'error'
        ]
    ]);
}