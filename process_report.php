<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dateFrom = $_POST['dateFrom'];
    $dateTo = $_POST['dateTo'];
    $memberId = $_POST['memberId'];
    $reportType = $_POST['reportType'];

    // Sample logic: fetch member data from another table
    $stmt = $conn->prepare("SELECT * FROM members WHERE member_id = ?");
    $stmt->bind_param("s", $memberId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Simulate calculation logic, e.g., assume default values
        $insert = $conn->prepare("INSERT INTO tbl_report (
            committee_name, member_name, member_id, duty_hours, rate, transpo_allowance,
            rcbc_deduction, norf_deduction, rice_deduction, savings_deposit
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $insert->bind_param("sssiiiiiii",
            $row['committee'], $row['name'], $row['member_id'],
            $dutyHours = 8, $rate = 100, $transpo = 500,
            $rcbc = 0, $norf = 0, $rice = 0, $deposit = 0
        );

        if ($insert->execute()) {
            header("Location: committee_management.php?success=1");
            exit;
        } else {
            echo "Insert failed: " . $conn->error;
        }
    } else {
        echo "Member not found.";
    }
}
?>
