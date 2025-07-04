<?php
session_start();
include '../../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die(json_encode([
        'success' => false, 
        'message' => 'Unauthorized',
        'toast' => [
            'title' => 'Error',
            'message' => 'You are not authorized',
            'type' => 'error'
        ]
    ]));
}

// Validate CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die(json_encode([
        'success' => false,
        'message' => 'Invalid CSRF token',
        'toast' => [
            'title' => 'Security Error',
            'message' => 'Invalid request',
            'type' => 'error'
        ]
    ]));
}

// Get and validate date range
$date_from = isset($_POST['date_from']) ? mysqli_real_escape_string($conn, $_POST['date_from']) : '';
$date_to = isset($_POST['date_to']) ? mysqli_real_escape_string($conn, $_POST['date_to']) : '';

if (empty($date_from) || empty($date_to)) {
    die(json_encode([
        'success' => false,
        'message' => 'Date range is required',
        'toast' => [
            'title' => 'Validation Error',
            'message' => 'Please select date range',
            'type' => 'warning'
        ]
    ]));
}

// Validate same month
$from_month = date('m-Y', strtotime($date_from));
$to_month = date('m-Y', strtotime($date_to));
if ($from_month !== $to_month) {
    die(json_encode([
        'success' => false,
        'message' => 'Invalid month range',
        'toast' => [
            'title' => 'Validation Error',
            'message' => 'Dates must be in same month',
            'type' => 'warning'
        ]
    ]));
}

// Check for existing allowance for this month
$month_start = date('Y-m-01', strtotime($date_from));
$month_end = date('Y-m-t', strtotime($date_from));
$checkQuery = "SELECT id FROM tbl_monthly_allowance 
              WHERE (
                (DateFrom BETWEEN '$month_start' AND '$month_end')
                OR (DateTo BETWEEN '$month_start' AND '$month_end')
                OR ('$month_start' BETWEEN DateFrom AND DateTo)
                OR ('$month_end' BETWEEN DateFrom AND DateTo)
              ) LIMIT 1";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
    die(json_encode([
        'success' => false,
        'message' => 'Allowance exists for this month',
        'toast' => [
            'title' => 'Validation Error',
            'message' => 'Monthly allowance exists',
            'type' => 'warning'
        ]
    ]));
}

// Start transaction
mysqli_begin_transaction($conn);

try {
    $user_ids = $_POST['user_id'] ?? [];
    $rates = $_POST['rate'] ?? [];
    $transpo_allowances = $_POST['transpo_allowance'] ?? [];
    $deduction_amounts = $_POST['deduction_amount'] ?? [];

    foreach ($user_ids as $index => $user_id) {
        $user_id = mysqli_real_escape_string($conn, $user_id);
        $rate = floatval($rates[$index] ?? 0);
        $transpo = floatval($transpo_allowances[$index] ?? 0);

        // Check if user already has allowance for this period
        $checkUserQuery = "SELECT id FROM tbl_monthly_allowance 
                          WHERE Users_Id = '$user_id' 
                          AND (
                            (DateFrom BETWEEN '$date_from' AND '$date_to')
                            OR (DateTo BETWEEN '$date_from' AND '$date_to')
                            OR ('$date_from' BETWEEN DateFrom AND DateTo)
                            OR ('$date_to' BETWEEN DateFrom AND DateTo)
                          ) LIMIT 1";
        $checkUserResult = mysqli_query($conn, $checkUserQuery);
        
        if (mysqli_num_rows($checkUserResult)) {
            throw new Exception("User already has allowance for this period");
        }

        // Insert deductions if any
        $userDeductionId = null;
        if (isset($deduction_amounts[$user_id])) {
            $deductionValues = [];
            foreach ($deduction_amounts[$user_id] as $deduction_id => $amount) {
                $deduction_id = mysqli_real_escape_string($conn, $deduction_id);
                $amount = floatval($amount) ?: 0;
                if ($amount > 0) {
                    $deductionValues[] = "('$user_id', '$deduction_id', '$amount', NOW())";
                }
            }
            
            if (!empty($deductionValues)) {
                $deductionQuery = "INSERT INTO tbl_user_deduction 
                                  (Users_Id, Deduction_Id, Amount, created_at)
                                  VALUES " . implode(',', $deductionValues);
                if (!mysqli_query($conn, $deductionQuery)) {
                    throw new Exception("Failed to insert deductions");
                }
                $userDeductionId = mysqli_insert_id($conn);
            }
        }

        // Insert default deduction if none
        if ($userDeductionId === null) {
            $defaultDeductionQuery = "INSERT INTO tbl_user_deduction 
                                     (Users_Id, Deduction_Id, Amount, created_at)
                                     VALUES ('$user_id', 0, 0, NOW())";
            if (!mysqli_query($conn, $defaultDeductionQuery)) {
                throw new Exception("Failed to create default deduction");
            }
            $userDeductionId = mysqli_insert_id($conn);
        }

        // Insert allowance record
        $query = "INSERT INTO tbl_monthly_allowance 
                  (Users_Id, Rate, TranspoAllowance, UserDeduction_Id, DateFrom, DateTo, created_at)
                  VALUES ('$user_id', '$rate', '$transpo', '$userDeductionId', '$date_from', '$date_to', NOW())";
        
        if (!mysqli_query($conn, $query)) {
            throw new Exception("Failed to save allowance: " . mysqli_error($conn));
        }
    }

    mysqli_commit($conn);
    
    echo json_encode([
        'success' => true,
        'message' => 'Monthly allowance saved successfully',
        'toast' => [
            'title' => 'Success',
            'message' => 'Allowance saved successfully',
            'type' => 'success'
        ],
        'date_from' => $date_from,
        'date_to' => $date_to
    ]);

} catch (Exception $e) {
    mysqli_rollback($conn);
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