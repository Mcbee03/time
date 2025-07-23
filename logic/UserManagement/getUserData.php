<?php
session_start();
header('Content-Type: application/json');
include '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
error_log("Received request for user ID: " . $id);

if ($id <= 0) {
    error_log("Invalid ID received: " . $id);
    echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT Id, PBNum, MemberID, Name, Committee_Id, Profile FROM tbl_users WHERE Id = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }

    $row = $result->fetch_assoc();
    
    // Convert BLOB to base64 for image display
    $profileData = null;
    if (!empty($row['Profile'])) {
        $profileData = base64_encode($row['Profile']);
    }
    
    $response = [
        'success' => true,
        'data' => [
            'Id' => $row['Id'],
            'PBNum' => $row['PBNum'] ?? '',
            'MemberID' => $row['MemberID'] ?? '',
            'Name' => $row['Name'] ?? '',
            'Committee_Id' => $row['Committee_Id'] ?? '',
            'Profile' => $profileData
        ]
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    error_log("Error in getUserData: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
} finally {
    if (isset($stmt)) $stmt->close();
    $conn->close();
}
?>