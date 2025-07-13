<?php
session_start();
include '../../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// FETCH DATA
if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['date_from']) || isset($_GET['id']))) {
    $date_from = $_GET['date_from'] ?? '';
    $date_to = $_GET['date_to'] ?? '';
    
    if (isset($_GET['id'])) {
        $stmt = $conn->prepare("SELECT DateFrom, DateTo FROM tbl_monthly_allowance WHERE Id = ? LIMIT 1");
        $stmt->bind_param('i', $_GET['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
            exit;
        }
        
        $row = $result->fetch_assoc();
        $date_from = $row['DateFrom'];
        $date_to = $row['DateTo'];
    }

    // First get all users with their allowance data
    $stmt = $conn->prepare("
        SELECT 
            u.Id as user_id, u.PBNum, u.MemberID, u.Name, c.Committee,
            ma.Id as allowance_id, ma.Rate, ma.TranspoAllowance, ma.HoursWorked
        FROM tbl_monthly_allowance ma
        JOIN tbl_users u ON ma.Users_Id = u.Id
        LEFT JOIN tbl_committee c ON u.Committee_Id = c.Id
        WHERE ma.DateFrom = ? AND ma.DateTo = ?
    ");
    
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Query failed: ' . $conn->error]);
        exit;
    }
    
    $stmt->bind_param('ss', $date_from, $date_to);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[$row['user_id']] = [
            'id' => $row['user_id'],
            'PBNum' => $row['PBNum'],
            'Name' => $row['Name'],
            'MemberID' => $row['MemberID'],
            'Committee' => $row['Committee'],
            'allowance_id' => $row['allowance_id'],
            'Rate' => $row['Rate'],
            'TranspoAllowance' => $row['TranspoAllowance'],
            'HoursWorked' => $row['HoursWorked'],
            'Deductions' => []
        ];
    }

    // Now get deductions that are active during the allowance period
    $stmt = $conn->prepare("
        SELECT d.Id, d.DeductionType 
        FROM tbl_deduction d
        WHERE (d.DateFrom IS NULL OR d.DateFrom <= ?) 
        AND (d.DateTo IS NULL OR d.DateTo >= ?)
    ");
    $stmt->bind_param('ss', $date_to, $date_from);
    $stmt->execute();
    $deductionTypes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Get user deductions that are active during this period
    if (!empty($users)) {
        $user_ids = array_keys($users);
        $placeholders = implode(',', array_fill(0, count($user_ids), '?'));
        
        $stmt = $conn->prepare("
            SELECT ud.Users_Id, ud.Deduction_Id, ud.Amount, d.DeductionType
            FROM tbl_user_deduction ud
            JOIN tbl_deduction d ON ud.Deduction_Id = d.Id
            WHERE ud.Users_Id IN ($placeholders)
            AND (d.DateFrom IS NULL OR d.DateFrom <= ?)
            AND (d.DateTo IS NULL OR d.DateTo >= ?)
        ");
        
        $types = str_repeat('i', count($user_ids)) . 'ss';
        $params = array_merge($user_ids, [$date_to, $date_from]);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $user_id = $row['Users_Id'];
            if (isset($users[$user_id])) {
                $users[$user_id]['Deductions'][$row['Deduction_Id']] = [
                    'DeductionType' => $row['DeductionType'],
                    'Amount' => $row['Amount']
                ];
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'date_from' => $date_from,
        'date_to' => $date_to,
        'users' => array_values($users),
        'deductionTypes' => $deductionTypes
    ]);
    exit;
}

// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die(json_encode(['success' => false, 'message' => 'Invalid CSRF token']));
    }

    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    
    mysqli_begin_transaction($conn);
    try {
        if (!isset($_POST['user_id']) || !is_array($_POST['user_id'])) {
            throw new Exception('Invalid user data');
        }

        foreach ($_POST['user_id'] as $i => $user_id) {
            $rate = isset($_POST['rate'][$i]) ? floatval($_POST['rate'][$i]) : 0;
            $transpo = isset($_POST['transpo_allowance'][$i]) ? floatval($_POST['transpo_allowance'][$i]) : 0;
            $hoursWorked = isset($_POST['hours_worked'][$i]) ? floatval($_POST['hours_worked'][$i]) : 0;
            $allowance_id = isset($_POST['allowance_id'][$i]) ? intval($_POST['allowance_id'][$i]) : 0;

            if ($allowance_id <= 0) continue;

            // Update allowance including hours worked
            $stmt = $conn->prepare("UPDATE tbl_monthly_allowance SET Rate = ?, TranspoAllowance = ?, HoursWorked = ? WHERE Id = ?");
            $stmt->bind_param('dddi', $rate, $transpo, $hoursWorked, $allowance_id);
            $stmt->execute();

            if (isset($_POST['deduction_amount']) && is_array($_POST['deduction_amount'])) {
                foreach ($_POST['deduction_amount'] as $uid => $deductions) {
                    if ($uid != $user_id) continue;
                    
                    foreach ($deductions as $deduction_id => $amount) {
                        $amount = floatval($amount);
                        $deduction_id = intval($deduction_id);
                        $current_time = date('Y-m-d H:i:s');

                        $stmt = $conn->prepare("SELECT Id FROM tbl_user_deduction WHERE Users_Id = ? AND Deduction_Id = ?");
                        $stmt->bind_param('ii', $user_id, $deduction_id);
                        $stmt->execute();
                        $exists = $stmt->get_result()->num_rows > 0;

                        if ($amount > 0) {
                            if ($exists) {
                                $stmt = $conn->prepare("UPDATE tbl_user_deduction SET Amount = ?, updated_at = ? WHERE Users_Id = ? AND Deduction_Id = ?");
                                $stmt->bind_param('dsii', $amount, $current_time, $user_id, $deduction_id);
                            } else {
                                $stmt = $conn->prepare("INSERT INTO tbl_user_deduction (Users_Id, Deduction_Id, Amount, created_at, updated_at) VALUES (?, ?, ?, ?, ?)");
                                $stmt->bind_param('iidss', $user_id, $deduction_id, $amount, $current_time, $current_time);
                            }
                            $stmt->execute();
                        } else if ($exists) {
                            $stmt = $conn->prepare("DELETE FROM tbl_user_deduction WHERE Users_Id = ? AND Deduction_Id = ?");
                            $stmt->bind_param('ii', $user_id, $deduction_id);
                            $stmt->execute();
                        }
                    }
                }
            }
        }

        mysqli_commit($conn);
        echo json_encode(['success' => true, 'message' => 'Allowance updated successfully']);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>