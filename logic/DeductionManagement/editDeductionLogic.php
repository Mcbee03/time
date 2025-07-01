<?php
session_start();
header('Content-Type: application/json');
include '../../config/db.php';

// 🔒 CSRF Check
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'CSRF token validation failed']);
    exit;
}

// ✅ Required Fields
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid deduction ID']);
    exit;
}

$id = (int)$_POST['id'];
$deduction = $conn->real_escape_string(trim($_POST['deduction']));
$dateFrom = $conn->real_escape_string($_POST['date_from']);
$dateTo = $conn->real_escape_string($_POST['date_to']);

// ❌ Date Logic
if ($dateFrom > $dateTo) {
    echo json_encode(['success' => false, 'message' => 'End date must be after start date']);
    exit;
}

// 🔍 Check if deduction exists
$checkExist = $conn->prepare("SELECT Id FROM tbl_deduction WHERE Id = ?");
$checkExist->bind_param('i', $id);
$checkExist->execute();
$checkExist->store_result();

if ($checkExist->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Deduction not found']);
    exit;
}
$checkExist->close();

// 🔍 Conflict Check for overlapping dates (excluding current)
$conflictCheck = $conn->prepare("
    SELECT Id FROM tbl_deduction 
    WHERE DeductionType = ? 
    AND Id != ? 
    AND (
        (DateFrom <= ? AND DateTo >= ?) OR 
        (DateFrom <= ? AND DateTo >= ?) OR 
        (DateFrom >= ? AND DateTo <= ?)
    )
");
$conflictCheck->bind_param('sissssss', $deduction, $id, $dateFrom, $dateFrom, $dateTo, $dateTo, $dateFrom, $dateTo);
$conflictCheck->execute();
$conflictCheck->store_result();

if ($conflictCheck->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Another deduction with the same name overlaps with this date range.']);
    exit;
}
$conflictCheck->close();

// ✅ Update Deduction
$updateStmt = $conn->prepare("UPDATE tbl_deduction SET DeductionType = ?, DateFrom = ?, DateTo = ? WHERE Id = ?");
$updateStmt->bind_param('sssi', $deduction, $dateFrom, $dateTo, $id);

if ($updateStmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Deduction updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$updateStmt->close();
$conn->close();
?>
