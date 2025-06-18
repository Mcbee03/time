<?php
session_start();
require_once '../../config/db.php';

// CSRF check
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die(json_encode(['success' => false, 'message' => 'Security validation failed']));
}

// Required fields
$required = ['pb_number', 'member_id', 'name', 'username', 'password', 'confirm_password'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        die(json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)).' is required']));
    }
}

// Password validation
if ($_POST['password'] !== $_POST['confirm_password']) {
    die(json_encode(['success' => false, 'message' => 'Passwords do not match']));
}

if (strlen($_POST['password']) < 8) {
    die(json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']));
}

// Check username exists
$stmt = $conn->prepare("SELECT Id FROM tbl_adminlogin WHERE username = ?");
$stmt->bind_param('s', $_POST['username']);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    die(json_encode(['success' => false, 'message' => 'Username already exists']));
}

// Create admin
$hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO tbl_adminlogin (PBNum, MemberID, Name, username, password) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('sssss', $_POST['pb_number'], $_POST['member_id'], $_POST['name'], $_POST['username'], $hashedPassword);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Admin added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

$stmt->close();
$conn->close();
?>