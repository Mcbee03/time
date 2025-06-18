<?php
session_start();
include '../../config/db.php';


// Verify CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'CSRF token validation failed']);
    exit;
}

// Get form data
$id = (int)$_POST['id'];
$pbNumber = trim($_POST['pb_number']);
$memberId = trim($_POST['member_id']);
$name = trim($_POST['name']);
$username = trim($_POST['username']);
$password = !empty($_POST['password']) ? $_POST['password'] : null;

// Validate inputs
$errors = [];
if (empty($pbNumber)) $errors[] = "PB Number is required";
if (empty($memberId)) $errors[] = "Member ID is required";
if (empty($name)) $errors[] = "Name is required";
if (empty($username)) $errors[] = "Username is required";

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode("<br>", $errors)]);
    exit;
}

// Check if username exists (excluding current admin)
$stmt = $conn->prepare("SELECT Id FROM tbl_adminlogin WHERE username = ? AND Id != ?");
$stmt->bind_param('si', $username, $id);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username already exists']);
    exit;
}

// Update with or without password
if ($password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE tbl_adminlogin SET PBNum = ?, MemberID = ?, Name = ?, username = ?, password = ? WHERE Id = ?");
    $stmt->bind_param('sssssi', $pbNumber, $memberId, $name, $username, $hashedPassword, $id);
} else {
    $stmt = $conn->prepare("UPDATE tbl_adminlogin SET PBNum = ?, MemberID = ?, Name = ?, username = ? WHERE Id = ?");
    $stmt->bind_param('ssssi', $pbNumber, $memberId, $name, $username, $id);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Admin updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>