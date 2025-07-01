<?php
session_start();
header('Content-Type: application/json');
include '../../config/db.php';

if (!isset($_POST['id'])) {
    http_response_code(400);
    exit(json_encode(['error' => 'Deduction ID not provided']));
}

$id = (int)$_POST['id'];

$stmt = $conn->prepare("SELECT Id, DeductionType AS deduction_name, 
                       DATE_FORMAT(DateFrom, '%Y-%m-%d') AS start_date, 
                       DATE_FORMAT(DateTo, '%Y-%m-%d') AS end_date 
                       FROM tbl_deduction WHERE Id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    exit(json_encode(['error' => 'Deduction not found']));
}

echo json_encode($result->fetch_assoc());

$stmt->close();
$conn->close();
?>