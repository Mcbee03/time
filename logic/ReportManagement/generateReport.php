<?php
ob_start();
session_start();
require_once '../../config/db.php';
require_once '../../vendor/autoload.php';

if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized access");
}

$reportType = $_POST['reportType'] ?? '';
$dateFrom = $_POST['dateFrom'] ?? '';
$dateTo = $_POST['dateTo'] ?? '';
$memberId = $_POST['memberId'] ?? '';
$exportFormat = $_POST['exportFormat'] ?? 'pdf';

if (empty($reportType) || empty($dateFrom) || empty($dateTo)) {
    die("Invalid request parameters");
}

if ($reportType === 'monthly') {
    generateMonthlyReport($dateFrom, $dateTo, $exportFormat);
} elseif ($reportType === 'dtr') {
    generateDTRReport($dateFrom, $dateTo, $memberId, $exportFormat);
}

// ======================== MONTHLY REPORT ========================
function generateMonthlyReport($dateFrom, $dateTo, $exportFormat) {
    global $conn;

    $sqlDateFrom = date('Y-m-d', strtotime($dateFrom));
    $sqlDateTo = date('Y-m-d', strtotime($dateTo));

    // First, get all monthly allowances for the period
    $query = "
        SELECT 
            ma.*, 
            u.Name, 
            u.PBNum, 
            u.MemberID, 
            u.Id AS Users_Id, 
            c.Committee
        FROM tbl_monthly_allowance ma
        JOIN tbl_users u ON ma.Users_Id = u.Id
        JOIN tbl_committee c ON u.Committee_Id = c.Id
        WHERE ma.DateFrom = ? AND ma.DateTo = ?
        ORDER BY c.Committee, u.Name
    ";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error . " | Query: " . $query);
    }
    
    $stmt->bind_param("ss", $sqlDateFrom, $sqlDateTo);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    if (!$result) {
        die("Get result failed: " . $stmt->error);
    }
    
    $allowances = $result->fetch_all(MYSQLI_ASSOC);

    // Get all deduction types that are active during this period
    $deductionsQuery = "
        SELECT Id, DeductionType 
        FROM tbl_deduction 
        WHERE (DateFrom IS NULL OR DateFrom <= ?) 
        AND (DateTo IS NULL OR DateTo >= ?)
    ";
    $dedStmt = $conn->prepare($deductionsQuery);
    if (!$dedStmt) {
        die("Prepare deductions failed: " . $conn->error);
    }
    
    if (!$dedStmt->bind_param("ss", $sqlDateTo, $sqlDateFrom)) {
        die("Bind param failed: " . $dedStmt->error);
    }
    
    if (!$dedStmt->execute()) {
        die("Execute deductions failed: " . $dedStmt->error);
    }
    
    $deductionTypes = $dedStmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Get user deductions that are valid for this monthly period
    $userIds = array_column($allowances, 'Users_Id');
    if (empty($userIds)) {
        die("No data found for the selected period");
    }

    $placeholders = implode(',', array_fill(0, count($userIds), '?'));
    $types = str_repeat('i', count($userIds)) . 'ss';
    
    $userDeductionsQuery = "
        SELECT 
            ud.Users_Id,
            ud.Deduction_Id,
            ud.Amount,
            d.DeductionType
        FROM tbl_user_deduction ud
        JOIN tbl_deduction d ON ud.Deduction_Id = d.Id
        WHERE ud.Users_Id IN ($placeholders)
        AND (d.DateFrom IS NULL OR d.DateFrom <= ?)
        AND (d.DateTo IS NULL OR d.DateTo >= ?)
    ";
    
    $dedStmt = $conn->prepare($userDeductionsQuery);
    if (!$dedStmt) {
        die("Prepare user deductions failed: " . $conn->error . " | Query: " . $userDeductionsQuery);
    }
    
    $params = array_merge($userIds, [$sqlDateTo, $sqlDateFrom]);
    if (!$dedStmt->bind_param($types, ...$params)) {
        die("Bind param failed: " . $dedStmt->error);
    }
    
    if (!$dedStmt->execute()) {
        die("Execute user deductions failed: " . $dedStmt->error);
    }
    
    $userDeductions = $dedStmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Group deductions by User ID
    $deductionsByUser = [];
    foreach ($userDeductions as $deduction) {
        $deductionsByUser[$deduction['Users_Id']][] = $deduction;
    }

    // Combine data with only valid deductions for this period
    $reportData = [];
    foreach ($allowances as $allowance) {
        $userId = $allowance['Users_Id'];
        $deductions = $deductionsByUser[$userId] ?? [];
        
        // Filter deductions to only include those that match our deduction types
        $validDeductions = array_filter($deductions, function($ded) use ($deductionTypes) {
            foreach ($deductionTypes as $type) {
                if ($type['Id'] == $ded['Deduction_Id']) {
                    return true;
                }
            }
            return false;
        });
        
        $allowance['deductions'] = $validDeductions;
        $reportData[] = $allowance;
    }

    if ($exportFormat === 'pdf') {
        generateMonthlyPDF($reportData, $deductionTypes, $dateFrom, $dateTo);
    } else {
        generateMonthlyExcel($reportData, $deductionTypes, $dateFrom, $dateTo);
    }
}

function generateMonthlyPDF($data, $deductionTypes, $dateFrom, $dateTo) {
    ob_clean();
    $mpdf = new \Mpdf\Mpdf(['format' => [215.9, 279.4]]);
    $mpdf->SetDisplayMode('fullpage');

    $style = '<style>
        body { font-family: Arial; }
        h1 { text-align: center; font-size: 16pt; margin-bottom: 5px; }
        h2 { text-align: center; font-size: 14pt; margin-top: 5px; margin-bottom: 15px; }
        h3 { font-size: 12pt; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 10pt; }
        th, td { border: 1px solid #333; padding: 5px; }
        th { background-color: #f0f0f0; text-align: center; }
        .right { text-align: right; }
        .center { text-align: center; }
    </style>';

    $mpdf->WriteHTML($style, \Mpdf\HTMLParserMode::HEADER_CSS);

    $monthYear = date('F Y', strtotime($dateFrom));
    $html = "<h1>MONTHLY ALLOWANCE REPORT</h1>";
    $html .= "<h2>For the month of $monthYear</h2>";

    $grouped = [];
    foreach ($data as $row) {
        $grouped[$row['Committee']][] = $row;
    }

    foreach ($grouped as $committee => $members) {
        $html .= "<h3>" . strtoupper($committee) . "</h3>";
        $html .= "<table><thead><tr>
            <th>Committee</th>
            <th>Name</th>
            <th>Member ID</th>
            <th>Duty Hours</th>
            <th>Rate</th>
            <th>Transpo Allowance</th>";
        foreach ($deductionTypes as $dedType) {
            $html .= "<th>{$dedType['DeductionType']}</th>";
        }
        $html .= "<th>Regular Savings</th></tr></thead><tbody>";

        foreach ($members as $member) {
            // Prepare deduction amounts per type
            $deductionMap = [];
            foreach ($deductionTypes as $dedType) {
                $deductionMap[$dedType['Id']] = 0;
            }
            foreach ($member['deductions'] as $deduction) {
                $deductionMap[$deduction['Deduction_Id']] = floatval($deduction['Amount']);
            }
            $totalDeductions = array_sum($deductionMap);
            $savings = floatval($member['TranspoAllowance']) - $totalDeductions;

            $html .= "<tr>
                <td>{$member['Committee']}</td>
                <td>{$member['Name']}</td>
                <td class='center'>{$member['MemberID']}</td>
                <td class='center'>{$member['HoursWorked']}</td>
                <td class='right'>" . number_format($member['Rate'], 2) . "</td>
                <td class='right'>" . number_format($member['TranspoAllowance'], 2) . "</td>";
            foreach ($deductionTypes as $dedType) {
                $html .= "<td class='right'>" . number_format($deductionMap[$dedType['Id']], 2) . "</td>";
            }
            $html .= "<td class='right'>" . number_format($savings, 2) . "</td>
            </tr>";
        }
        $html .= "</tbody></table>";
    }

    $mpdf->WriteHTML($html);
    $mpdf->Output("Monthly_Allowance_Report_$monthYear.pdf", "D");
    exit;
}

function generateMonthlyExcel($data, $deductionTypes, $dateFrom, $dateTo) {
    ob_clean();
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $monthYear = date('F Y', strtotime($dateFrom));
    $sheet->setCellValue('A1', 'MONTHLY ALLOWANCE REPORT');
    $sheet->setCellValue('A2', "For the month of $monthYear");
    $colCount = 7 + count($deductionTypes); // Committee, Name, Member ID, Duty Hours, Rate, Transpo, Deductions..., Savings
    $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colCount);
    $sheet->mergeCells("A1:{$lastCol}1");
    $sheet->mergeCells("A2:{$lastCol}2");

    // Style for title
    $sheet->getStyle('A1:A2')->getFont()->setBold(true);
    $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


    $rowNum = 4;
    $headers = [
        'Committee', 'Name', 'Member ID', 'Duty Hours', 'Rate', 'Transpo Allowance'
    ];
    foreach ($deductionTypes as $dedType) {
        $headers[] = $dedType['DeductionType'];
    }
    $headers[] = 'Regular Savings';
    $sheet->fromArray($headers, NULL, "A$rowNum");
    $headerRange = "A$rowNum:{$lastCol}{$rowNum}";
    $sheet->getStyle($headerRange)->getFont()->setBold(true);
    $rowNum++;

    // Group by committee
    $grouped = [];
    foreach ($data as $row) {
        $grouped[$row['Committee']][] = $row;
    }

    foreach ($grouped as $committee => $members) {
        $sheet->setCellValue("A$rowNum", strtoupper($committee));
        $sheet->mergeCells("A{$rowNum}:{$lastCol}{$rowNum}");
        $rowNum++;

        foreach ($members as $member) {
            $deductionMap = [];
            foreach ($deductionTypes as $dedType) {
                $deductionMap[$dedType['Id']] = 0;
            }
            foreach ($member['deductions'] as $deduction) {
                $deductionMap[$deduction['Deduction_Id']] = floatval($deduction['Amount']);
            }
            $totalDeductions = array_sum($deductionMap);
            $savings = floatval($member['TranspoAllowance']) - $totalDeductions;

            $rowData = [
                $member['Committee'],
                $member['Name'],
                $member['MemberID'],
                $member['HoursWorked'],
                number_format($member['Rate'], 2),
                number_format($member['TranspoAllowance'], 2)
            ];
            foreach ($deductionTypes as $dedType) {
                $rowData[] = number_format($deductionMap[$dedType['Id']], 2);
            }
            $rowData[] = number_format($savings, 2);

            $sheet->fromArray($rowData, NULL, "A$rowNum");
            $rowNum++;
        }
        $rowNum++;
    }

    foreach (range('A', $lastCol) as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // CLEAR OUTPUT BUFFER BEFORE SENDING HEADERS AND FILE
    if (ob_get_length()) ob_end_clean();

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Monthly_Allowance_Report_' . $monthYear . '.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit;
}

// ======================== DTR REPORT ========================
function generateDTRReport($dateFrom, $dateTo, $memberId, $exportFormat) {
    global $conn;

    $query = "
        SELECT d.*, u.Name, u.PBNum, u.MemberID, c.Committee
        FROM tbl_dtr d
        JOIN tbl_users u ON d.Users_Id = u.Id
        JOIN tbl_committee c ON u.Committee_Id = c.Id
        WHERE d.Date BETWEEN ? AND ?
    ";

    $params = [$dateFrom, $dateTo];
    $types = "ss";

    if (!empty($memberId)) {
        $query .= " AND (u.PBNum = ? OR u.MemberID = ?)";
        $params[] = $memberId;
        $params[] = $memberId;
        $types .= "ss";
    }

    $query .= " ORDER BY u.Name, d.Date";
    $stmt = $conn->prepare($query);
    if (!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if ($exportFormat === 'pdf') {
        generateDTRPDF($data, $dateFrom, $dateTo);
    } else {
        generateDTRExcel($data, $dateFrom, $dateTo);
    }
}

function generateDTRPDF($data, $dateFrom, $dateTo) {
    ob_clean();
    $mpdf = new \Mpdf\Mpdf(['format' => [215.9, 279.4]]);
    $html = '<style>
        body { font-family: Arial; }
        h1, h2, h3 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 10pt; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: center; }
    </style>';
    $html .= "<h1>DAILY TIME RECORD REPORT</h1>";
    $html .= "<h2>From " . date('m-d-Y', strtotime($dateFrom)) . " to " . date('m-d-Y', strtotime($dateTo)) . "</h2>";

    $grouped = [];
    foreach ($data as $row) {
        $grouped[$row['Name']][] = $row;
    }

foreach ($grouped as $name => $entries) {
    $html .= "<h3>$name - " . $entries[0]['Committee'] . "</h3>";
    $html .= "<table><thead><tr>
        <th>DATE</th><th>TIME IN</th><th>TIME OUT</th><th>HOURS WORKED</th>
    </tr></thead><tbody>";

    $totalHours = 0;
    foreach ($entries as $entry) {
        $formattedDate = date('m-d-Y', strtotime($entry['Date']));
        $formattedTimeIn = date('h:i A', strtotime($entry['TimeIN']));
        $formattedTimeOut = !empty($entry['TimeOUT']) ? date('h:i A', strtotime($entry['TimeOUT'])) : 'N/A';

        $totalHours += floatval($entry['HoursWorked']);

        $html .= "<tr>
            <td>$formattedDate</td>
            <td>$formattedTimeIn</td>
            <td>$formattedTimeOut</td>
            <td>{$entry['HoursWorked']}</td>
        </tr>";
    }

    // Append TOTAL row
    $html .= "<tr>
        <td colspan='3'><strong>TOTAL</strong></td>
        <td><strong>" . number_format($totalHours, 2) . "</strong></td>
    </tr>";

    $html .= "</tbody></table>";
}


    $mpdf->WriteHTML($html);
    $mpdf->Output("DTR_Report.pdf", "D");
    exit;
}


function generateDTRExcel($data, $dateFrom, $dateTo) {
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'DAILY TIME RECORD REPORT');
    $sheet->setCellValue('A2', 'From ' . date('m-d-Y', strtotime($dateFrom)) . ' to ' . date('m-d-Y', strtotime($dateTo)));
    $sheet->mergeCells('A1:D1');
    $sheet->mergeCells('A2:D2');

    // Style for title
    $sheet->getStyle('A1:A2')->getFont()->setBold(true);
    $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $rowNum = 4;
    $grouped = [];
    foreach ($data as $row) {
        $grouped[$row['Name']][] = $row;
    }

foreach ($grouped as $name => $entries) {
    $sheet->setCellValue("A$rowNum", "$name - " . $entries[0]['Committee']);
    $sheet->mergeCells("A$rowNum:D$rowNum");
    $rowNum++;

    $sheet->fromArray(['DATE', 'TIME IN', 'TIME OUT', 'HOURS WORKED'], NULL, "A$rowNum");
    $headerRange = "A{$rowNum}:D{$rowNum}";
    $sheet->getStyle($headerRange)->getFont()->setBold(true);
    $rowNum++;

    $totalHours = 0;
    foreach ($entries as $entry) {
        $formattedDate = date('m-d-Y', strtotime($entry['Date']));
        $formattedTimeIn = date('h:i A', strtotime($entry['TimeIN']));
        $formattedTimeOut = !empty($entry['TimeOUT']) ? date('h:i A', strtotime($entry['TimeOUT'])) : 'N/A';

        $sheet->fromArray([
            $formattedDate,
            $formattedTimeIn,
            $formattedTimeOut,
            $entry['HoursWorked']
        ], NULL, "A$rowNum");

        $totalHours += floatval($entry['HoursWorked']);
        $rowNum++;
    }

    // Append TOTAL row
    $sheet->setCellValue("A$rowNum", 'TOTAL');
    $sheet->mergeCells("A$rowNum:C$rowNum");
    $sheet->setCellValue("D$rowNum", number_format($totalHours, 2));
    $sheet->getStyle("A$rowNum:D$rowNum")->getFont()->setBold(true);

    $rowNum += 2;
}


    foreach (range('A', 'D') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="DTR_Report.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit;
}

