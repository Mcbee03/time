<?php
session_start();
include '../../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access',
        'toast' => [
            'title' => 'Error',
            'message' => 'You must be logged in as admin',
            'type' => 'error'
        ]
    ]);
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing ID',
        'toast' => [
            'title' => 'Error',
            'message' => 'Missing or invalid ID',
            'type' => 'error'
        ]
    ]);
    exit;
}

// Get DateFrom and DateTo of this allowance group
$headerQuery = "SELECT DateFrom, DateTo FROM tbl_monthly_allowance WHERE id = $id LIMIT 1";
$headerResult = mysqli_query($conn, $headerQuery);
if (!$headerResult || mysqli_num_rows($headerResult) == 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Allowance not found',
        'toast' => [
            'title' => 'Error',
            'message' => 'Allowance record not found',
            'type' => 'error'
        ]
    ]);
    exit;
}
$header = mysqli_fetch_assoc($headerResult);
$date_from = $header['DateFrom'];
$date_to = $header['DateTo'];

// Get all deduction types active within the allowance period
$deductionTypes = [];
$deductionQuery = "SELECT Id, DeductionType FROM tbl_deduction 
                   WHERE DateFrom <= '$date_to' AND DateTo >= '$date_from'";
$deductionResult = mysqli_query($conn, $deductionQuery);
while ($d = mysqli_fetch_assoc($deductionResult)) {
    $deductionTypes[] = $d;
}

// Fetch all users included in this allowance period (joined by DateFrom and DateTo)
$userQuery = "
    SELECT 
        u.id,
        u.Name,
        u.MemberID,
        c.Committee,
        ma.id AS allowance_id,
        ma.HoursWorked,
        ma.Rate,
        ma.TranspoAllowance,
        ma.UserDeduction_Id
    FROM tbl_monthly_allowance ma
    JOIN tbl_users u ON ma.Users_Id = u.id
    LEFT JOIN tbl_committee c ON u.Committee_Id = c.Id
    WHERE ma.DateFrom = '$date_from' AND ma.DateTo = '$date_to'
";
$userResult = mysqli_query($conn, $userQuery);

$users = [];
$userIds = [];

while ($row = mysqli_fetch_assoc($userResult)) {
    $row['Deductions'] = []; // Placeholder
    $users[$row['id']] = $row;
    $userIds[] = $row['id'];
}

// Fetch all deductions for those users within this period
if (!empty($userIds)) {
    $userIdsStr = implode(",", array_map('intval', $userIds));
    $deductionQuery = "
        SELECT ud.Users_Id, ud.Deduction_Id, ud.Amount
        FROM tbl_user_deduction ud
        JOIN tbl_monthly_allowance ma ON ma.UserDeduction_Id = ud.id
        WHERE ma.Users_Id IN ($userIdsStr) 
        AND ma.DateFrom = '$date_from' AND ma.DateTo = '$date_to'
    ";
    $deductionResult = mysqli_query($conn, $deductionQuery);
    while ($row = mysqli_fetch_assoc($deductionResult)) {
        $uid = $row['Users_Id'];
        $did = $row['Deduction_Id'];
        $users[$uid]['Deductions'][$did] = ['Amount' => $row['Amount']];
    }
}

// Re-index for front-end consumption
$responseUsers = array_values($users);

echo json_encode([
    'success' => true,
    'message' => 'Edit allowance data loaded',
    'toast' => [
        'title' => 'Success',
        'message' => 'Data fetched successfully',
        'type' => 'success'
    ],
    'date_from' => $date_from,
    'date_to' => $date_to,
    'deductionTypes' => $deductionTypes,
    'users' => $responseUsers
]);
?>