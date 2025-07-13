<?php
header('Content-Type: application/json');
session_start();
include '../../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized',
        'toast' => [
            'title' => 'Error',
            'message' => 'You need to login first',
            'type' => 'error'
        ]
    ]);
    exit;
}

try {
    $date_from = mysqli_real_escape_string($conn, $_POST['date_from']);
    $date_to = mysqli_real_escape_string($conn, $_POST['date_to']);
    $response = [
        'success' => true,
        'users' => [],
        'deductionTypes' => [],
        'toast' => null
    ];

    // ✅ Check overlapping allowance entries
    $checkQuery = "
        SELECT id FROM tbl_monthly_allowance 
        WHERE (
            (DateFrom BETWEEN '$date_from' AND '$date_to') OR
            (DateTo BETWEEN '$date_from' AND '$date_to') OR
            ('$date_from' BETWEEN DateFrom AND DateTo) OR
            ('$date_to' BETWEEN DateFrom AND DateTo)
        ) LIMIT 1
    ";
    $checkResult = mysqli_query($conn, $checkQuery);
    if (!$checkResult) {
        throw new Exception('Date validation failed: ' . mysqli_error($conn));
    }
    if (mysqli_num_rows($checkResult) > 0) {
        throw new Exception('An allowance already exists for this date range or overlaps with existing records');
    }

    // ✅ Get users with committee and DTR
    $userQuery = "
        SELECT u.id, u.Name, u.MemberID, c.Committee, 
        COALESCE(SUM(d.HoursWorked), 0) as HoursWorked
        FROM tbl_users u
        LEFT JOIN tbl_committee c ON u.Committee_Id = c.Id
        LEFT JOIN tbl_dtr d ON u.id = d.Users_Id AND d.Date BETWEEN '$date_from' AND '$date_to'
        GROUP BY u.id
    ";
    $userResult = mysqli_query($conn, $userQuery);
    if (!$userResult) {
        throw new Exception('User query failed: ' . mysqli_error($conn));
    }

    while ($user = mysqli_fetch_assoc($userResult)) {
        $response['users'][] = $user;
    }

    // ✅ Get deduction types (removed Status column condition)
    $deductionQuery = "
        SELECT Id, DeductionType 
        FROM tbl_deduction 
        WHERE 
            (DateFrom IS NULL OR DateFrom <= '$date_to') AND 
            (DateTo IS NULL OR DateTo >= '$date_from')
    ";
    $deductionResult = mysqli_query($conn, $deductionQuery);
    if (!$deductionResult) {
        throw new Exception('Deduction query failed: ' . mysqli_error($conn));
    }

    while ($deduction = mysqli_fetch_assoc($deductionResult)) {
        $response['deductionTypes'][] = $deduction;
    }

    echo json_encode($response);    

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'toast' => [
            'title' => 'Validation Error',
            'message' => $e->getMessage(),
            'type' => 'warning'
        ]
    ]);
}
?>
