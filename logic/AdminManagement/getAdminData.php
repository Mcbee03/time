<?php
session_start();
include '../../config/db.php';


if (!isset($_POST['id'])) {
    die(json_encode(['error' => 'Admin ID not provided']));
}

$id = (int)$_POST['id'];

$stmt = $conn->prepare("SELECT Id, PBNum, MemberID, Name, username FROM tbl_adminlogin WHERE Id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die(json_encode(['error' => 'Admin not found']));
}

echo json_encode($result->fetch_assoc());

$stmt->close();
$conn->close();
?>