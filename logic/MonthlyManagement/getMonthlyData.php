<?php
header('Content-Type: application/json');
session_start();

include '../../config/db.php';

if (!$conn) {
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed',
        'toast' => [
            'title' => 'Error',
            'message' => 'Cannot connect to database',
            'type' => 'error'
        ]
    ]));
}

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    die(json_encode([
        'success' => false,
        'message' => 'Unauthorized',
        'toast' => [
            'title' => 'Error',
            'message' => 'You need to login first',
            'type' => 'error'
        ]
    ]));
}

try {
    $date_from = $_POST['date_from'] ?? '';
    $date_to = $_POST['date_to'] ?? '';
    
    if (empty($date_from) || empty($date_to)) {
        throw new Exception('Date range is required');
    }

    $response = [
        'success' => true,
        'users' => [],
        'deductionTypes' => []
    ];

    // Get users with their existing allowances for this period
    // REMOVED the isActive check since the column doesn't exist
    $userQuery = "SELECT 
        u.id, u.Name, u.MemberID, c.Committee,
        COALESCE(SUM(d.HoursWorked), 0) as HoursWorked,
        ma.id as allowance_id, ma.DateFrom, ma.DateTo, ma.Rate, ma.TranspoAllowance
        FROM tbl_users u
        LEFT JOIN tbl_committee c ON u.Committee_Id = c.Id
        LEFT JOIN tbl_dtr d ON u.id = d.Users_Id AND d.Date BETWEEN ? AND ?
        LEFT JOIN tbl_monthly_allowance ma ON ma.Users_Id = u.id 
            AND (
                (ma.DateFrom BETWEEN ? AND ?)
                OR (ma.DateTo BETWEEN ? AND ?)
                OR (? BETWEEN ma.DateFrom AND ma.DateTo)
                OR (? BETWEEN ma.DateFrom AND ma.DateTo)
            )
        GROUP BY u.id, ma.id";

    $stmt = $conn->prepare($userQuery);
    if (!$stmt) throw new Exception('Failed to prepare user query: ' . $conn->error);
    
    $stmt->bind_param('ssssssss', $date_from, $date_to, $date_from, $date_to, $date_from, $date_to, $date_from, $date_to);
    if (!$stmt->execute()) throw new Exception('User query failed: ' . $stmt->error);
    
    $result = $stmt->get_result();
    while ($user = $result->fetch_assoc()) {
        $response['users'][] = $user;
    }

    // Get active deductions for the period
    // Also removed isActive check here unless you're sure it exists in tbl_deduction
    $deductionQuery = "SELECT Id, DeductionType 
                      FROM tbl_deduction 
                      WHERE DateFrom <= ? AND DateTo >= ?";
    
    $stmt = $conn->prepare($deductionQuery);
    if (!$stmt) throw new Exception('Failed to prepare deduction query: ' . $conn->error);
    
    $stmt->bind_param('ss', $date_to, $date_from);
    if (!$stmt->execute()) throw new Exception('Deduction query failed: ' . $stmt->error);
    
    $result = $stmt->get_result();
    while ($deduction = $result->fetch_assoc()) {
        $response['deductionTypes'][] = $deduction;
    }

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'toast' => [
            'title' => 'Error',
            'message' => $e->getMessage(),
            'type' => 'error'
        ]
    ]);
}
?>