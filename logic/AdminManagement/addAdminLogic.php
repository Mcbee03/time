<?php
session_start();
include '../../config/db.php';


// Verify CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'CSRF token validation failed']);
    exit;
}

// Get and validate form data
$pbNumber = trim($_POST['pb_number']);
$memberId = trim($_POST['member_id']);
$name = trim($_POST['name']);
$username = trim($_POST['username']);
$password = $_POST['password'];
$confirmPassword = $_POST['confirm_password'];

// Validation
$errors = [];
if (empty($pbNumber)) $errors[] = "PB Number is required";
if (empty($memberId)) $errors[] = "Member ID is required";
if (empty($name)) $errors[] = "Name is required";
if (empty($username)) $errors[] = "Username is required";
if (empty($password)) $errors[] = "Password is required";
if ($password !== $confirmPassword) $errors[] = "Passwords do not match";
if (strlen($password) < 8) $errors[] = "Password must be at least 8 characters";

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode("<br>", $errors)]);
    exit;
}

// Check if username exists
$stmt = $conn->prepare("SELECT Id FROM tbl_adminlogin WHERE username = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username already exists']);
    exit;
}

// Insert new admin
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO tbl_adminlogin (PBNum, MemberID, Name, username, password) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('sssss', $pbNumber, $memberId, $name, $username, $hashedPassword);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Admin added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>