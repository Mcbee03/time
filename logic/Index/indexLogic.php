<?php
session_start();
include 'config/db.php'; // Make sure this path is correct
date_default_timezone_set('Asia/Manila');
$today = date('Y-m-d');

// Initialize variables
$searchedMemberID = null;
$timedInStatus = false;
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$totalHours = isset($_SESSION['total_hours']) ? $_SESSION['total_hours'] : '';
$userData = null;

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['member_id'])) {
    $memberID = trim($_POST['member_id']);
    
    if ($memberID !== '') {
        // Check if member exists in database
        $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE MemberID = ? OR PBNum = ?");
        $stmt->bind_param("ss", $memberID, $memberID);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $stmt->close();
        
        if ($userData) {
            $searchedMemberID = $memberID;
            
            if (isset($_POST['action'])) {
                if ($_POST['action'] === 'time_in') {
                    // Check if already timed in today
                    $stmt = $conn->prepare("SELECT * FROM tbl_dtr WHERE Users_Id = ? AND Date = ? AND TimeOUT IS NULL");
                    $stmt->bind_param("is", $userData['Id'], $today);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $existingEntry = $result->fetch_assoc();
                    $stmt->close();
                    
                    if ($existingEntry) {
                        $_SESSION['message'] = "Member $memberID is already timed in today!";
                    } else {
                        // Insert time in record
                        $now = date('Y-m-d H:i:s'); // PHP time in Asia/Manila
                        $stmt = $conn->prepare("INSERT INTO tbl_dtr (Users_Id, Date, TimeIN, created_at) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("isss", $userData['Id'], $today, $now, $now);
                        $stmt->execute();
                        $stmt->close();
                        
                        $_SESSION['message'] = "Member $memberID timed in at " . date("M j, Y, g:i:s A");
                    }
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit;
                    
                } elseif ($_POST['action'] === 'time_out') {
                    // Find existing time in record
                    $stmt = $conn->prepare("SELECT * FROM tbl_dtr WHERE Users_Id = ? AND Date = ? AND TimeOUT IS NULL ORDER BY TimeIN DESC LIMIT 1");
                    $stmt->bind_param("is", $userData['Id'], $today);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $existingEntry = $result->fetch_assoc();
                    $stmt->close();
                    
                    if ($existingEntry) {
                        // Update with time out
                        $now = date('Y-m-d H:i:s');
                        $stmt = $conn->prepare("UPDATE tbl_dtr SET TimeOUT = ?, updated_at = ?, HoursWorked = TIMESTAMPDIFF(SECOND, TimeIN, ?)/3600 WHERE Id = ?");
                        $stmt->bind_param("sssi", $now, $now, $now, $existingEntry['Id']);
                        $stmt->execute();
                        $stmt->close();
                        
                        // Calculate time worked
                        $stmt = $conn->prepare("SELECT HoursWorked FROM tbl_dtr WHERE Id = ?");
                        $stmt->bind_param("i", $existingEntry['Id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $hoursData = $result->fetch_assoc();
                        $stmt->close();
                        
                        $totalHoursWorked = $hoursData['HoursWorked'];
                        $hours = floor($totalHoursWorked);
                        $minutes = floor(($totalHoursWorked - $hours) * 60);
                        
                        $_SESSION['message'] = "Member $memberID timed out at " . date("M j, Y, g:i:s A");
                        $_SESSION['total_hours'] = "Total time worked: $hours hours and $minutes minutes";
                    } else {
                        $_SESSION['message'] = "No active time in found for member $memberID";
                    }
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit;
                }
            } else {
                // Check timed in status
                $stmt = $conn->prepare("SELECT * FROM tbl_dtr WHERE Users_Id = ? AND Date = ? AND TimeOUT IS NULL");
                $stmt->bind_param("is", $userData['Id'], $today);
                $stmt->execute();
                $result = $stmt->get_result();
                $timedInStatus = $result->fetch_assoc() ? true : false;
                $stmt->close();
            }
        } else {
            $_SESSION['message'] = "Member ID/PB# $memberID not found!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// Clear session messages after they're displayed
unset($_SESSION['message']);
unset($_SESSION['total_hours']);
?>