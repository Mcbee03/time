<?php
session_start();
require_once '../../config/db.php';
header('Content-Type: application/json');

// 🔒 CSRF Check
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Security validation failed']);
    exit;
}

// ✅ Required Fields Check
$required = ['deduction', 'date_from', 'date_to'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
        exit;
    }
}

// 🧼 Sanitize Inputs
$deduction = $conn->real_escape_string(trim($_POST['deduction']));
$dateFrom = $conn->real_escape_string($_POST['date_from']);
$dateTo = $conn->real_escape_string($_POST['date_to']);

// ❌ Date Logic
if ($dateFrom > $dateTo) {
    echo json_encode(['success' => false, 'message' => 'End date must be after start date']);
    exit;
}

// 🔍 Date Range Conflict Check
$checkStmt = $conn->prepare("
    SELECT Id FROM tbl_deduction 
    WHERE DeductionType = ? 
    AND (
        (DateFrom <= ? AND DateTo >= ?) OR 
        (DateFrom <= ? AND DateTo >= ?) OR 
        (DateFrom >= ? AND DateTo <= ?)
    )
");
$checkStmt->bind_param('sssssss', $deduction, $dateFrom, $dateFrom, $dateTo, $dateTo, $dateFrom, $dateTo);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'This deduction overlaps with an existing date range.']);
    exit;
}
$checkStmt->close();

// ✅ Insert Deduction
$stmt = $conn->prepare("INSERT INTO tbl_deduction (DeductionType, DateFrom, DateTo) VALUES (?, ?, ?)");
$stmt->bind_param('sss', $deduction, $dateFrom, $dateTo);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Deduction added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>
