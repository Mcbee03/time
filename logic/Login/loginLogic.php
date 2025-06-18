<?php
session_start();
include '../../config/db.php';

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $errors = [];

    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    }
    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    }

    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input', 'errors' => $errors]);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM tbl_adminlogin WHERE username = ?");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: prepare failed.']);
        exit;
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['admin_id'] = $user['Id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_name'] = $user['Name'];
            $_SESSION['admin_pbnum'] = $user['PBNum'];
            $_SESSION['admin_memberid'] = $user['MemberID'];

            // THIS IS WHERE WE ADDED THE SMART REDIRECT LOGIC
            $response = [
                'status' => 'success',
                'message' => 'Login successful',
                'redirect' => isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : '/pages/adminManagement.php'
            ];
            unset($_SESSION['redirect_url']); // Clear the stored URL after use
            echo json_encode($response);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Incorrect Password',
                'errors' => ['password' => true]
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Incorrect Username',
            'errors' => ['username' => true]
        ]);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>