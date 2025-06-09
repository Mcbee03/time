<?php
$pageTitle = "Committee Management";
$activePage = "committee";

// Default empty committees array
$committees = [
    [
        'name' => 'ETHICS AND GRIEVANCE COMMITTEE',
        'users' => []
    ],
    [
        'name' => 'MEDIATION AND CONCILIATION COMMITTEE',
        'users' => []
    ],
    [
        'name' => 'GENDER AND DEVELOPMENT COMMITTEE',
        'users' => []
    ]
];

$hasDatabase = false;
try {
    if (file_exists('db_connectio.php')) {
        include 'db_connection.php';
        $hasDatabase = true;

        // Reset committees array so it can be rebuilt from DB
        $committees = [];

        // Initialize base query
        $query = "SELECT * FROM tbl_report WHERE 1=1";
        $params = [];
        $types = "";

        // Check and append filters from POST
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!empty($_POST['dateFrom']) && !empty($_POST['dateTo'])) {
                $query .= " AND report_date BETWEEN ? AND ?";
                $params[] = $_POST['dateFrom'];
                $params[] = $_POST['dateTo'];
                $types .= "ss";
            }

            if (!empty($_POST['memberId'])) {
                $query .= " AND member_id = ?";
                $params[] = $_POST['memberId'];
                $types .= "s";
            }

            // Optional future filter for reportType
        }

        $query .= " ORDER BY committee_name, member_name";
        $stmt = $conn->prepare($query);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Grouping by committee
        while ($row = $result->fetch_assoc()) {
            $committeeName = $row['committee_name'];
            $committeeIndex = array_search($committeeName, array_column($committees, 'name'));

            $member = [
                'name' => $row['member_name'],
                'member_id' => $row['member_id'],
                'duty_hours' => $row['duty_hours'],
                'rate' => $row['rate'],
                'transpo_allowance' => $row['transpo_allowance'],
                'less' => [
                    'RCBC' => $row['rcbc_deduction'],
                    'NORF' => $row['norf_deduction'],
                    'Rice' => $row['rice_deduction']
                ],
                'regular_savings_deposit' => $row['savings_deposit']
            ];

            if ($committeeIndex !== false) {
                $committees[$committeeIndex]['users'][] = $member;
            } else {
                $committees[] = [
                    'name' => $committeeName,
                    'users' => [$member]
                ];
            }
        }
    }
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    $hasDatabase = false;
}

include 'header.php';
?>


<div class="main-content-container">
    <!-- Button to trigger modal -->
    <button type="button" class="btn mb-3 text-white" data-toggle="modal" data-target="#createDeductionModal" style="background-color: #2c7a7b;">
    Create Deduction
</button>


    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0" id="usersTable">
                    <thead class="green-header">
                        <tr>
                            <th rowspan="2">Name</th>
                            <th rowspan="2">Member ID</th>
                            <th rowspan="2">Duty Hours</th>
                            <th rowspan="2">Rate</th>
                            <th rowspan="2">Transpo Allowance</th>
                            <th colspan="3" class="text-center">Less</th>
                            <th rowspan="2">Regular Savings Deposit</th>
                        </tr>
                        <tr>
                            <th>RCBC</th>
                            <th>NORF</th>
                            <th>Rice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($committees as $committee): ?>
                            <tr class="committee-header">
                                <td colspan="9" class="font-weight-bold bg-light">
                                    <?= htmlspecialchars($committee['name']) ?>
                                </td>
                            </tr>
                            <?php foreach ($committee['users'] as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['member_id']) ?></td>
                                <td><?= htmlspecialchars($user['duty_hours']) ?></td>
                                <td><?= htmlspecialchars($user['rate']) ?></td>
                                <td><?= htmlspecialchars($user['transpo_allowance']) ?></td>
                                <td><?= htmlspecialchars($user['less']['RCBC']) ?></td>
                                <td><?= htmlspecialchars($user['less']['NORF']) ?></td>
                                <td><?= htmlspecialchars($user['less']['Rice']) ?></td>
                                <td><?= htmlspecialchars($user['regular_savings_deposit']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Deduction Modal -->
<div class="modal fade" id="createDeductionModal" tabindex="-1" role="dialog" aria-labelledby="createDeductionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #2c7a7b; color: white;">
    <h5 class="modal-title" id="createDeductionModalLabel">Create Deduction</h5>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

            <div class="modal-body">
                <div class="form-row mb-4">
                    <div class="form-group col-md-3">
                        <label for="dateFrom" class="font-weight-bold">Date From</label>
                        <input type="date" class="form-control form-control-lg" id="dateFrom">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="dateTo" class="font-weight-bold">Date To</label>
                        <input type="date" class="form-control form-control-lg" id="dateTo">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="rate" class="font-weight-bold"> Rate</label>
                        <input type="number" class="form-control form-control-lg number-input" id="rate" value="100">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="transpoAllowance" class="font-weight-bold">Transport Allowance</label>
                        <input type="number" class="form-control form-control-lg number-input" id="transpoAllowance" value="500">
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 font-weight-bold">Committee Members</h5>
                    <button type="button" class="btn btn-success btn-lg" id="addRowBtn">
                        <i class="fas fa-plus"></i> Add Member
                    </button>
                </div>
                
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light sticky-top" style="background-color: #f8f9fa;">
                            <tr class="text-center">
                                <th width="20%" class="align-middle">Committee</th>
                                <th width="15%" class="align-middle">Name</th>
                                <th width="10%" class="align-middle">Member ID</th>
                                <th width="8%" class="align-middle">Duty Hours</th>
                                <th width="8%" class="align-middle">Rate</th>
                                <th width="10%" class="align-middle">Transpo Allowance</th>
                                <th colspan="3" class="text-center bg-light">Less</th>
                                <th width="10%" class="align-middle">Regular Savings</th>
                                <th width="5%" class="align-middle">Action</th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th colspan="6"></th>
                                <th width="8%">RCBC</th>
                                <th width="8%">NORF</th>
                                <th width="8%">Rice</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="deductionTableBody">
                            <!-- Initial row -->
                            <tr>
    <td>
        <select class="form-control">
            <option selected>ETHICS AND GRIEVANCE COMMITTEE</option>
            <option style="display: none;">MEDIATION AND CONCILIATION COMMITTEE</option>
            <option style="display: none;">GENDER AND DEVELOPMENT COMMITTEE</option>
        </select>
    </td>
    <td><input type="text" class="form-control" placeholder="Name" readonly></td>
<td><input type="text" class="form-control" placeholder="ID" readonly></td>
<td><input type="number" class="form-control" placeholder="Hours" value="8" style="width: 65px;" readonly></td>
<td><input type="number" class="form-control" value="100" style="width: 70px;"></td> <!-- Rate is editable -->
<td><input type="number" class="form-control" value="500" style="width: 80px;" readonly></td>
<td><input type="number" class="form-control" value="0" style="width: 65px;"></td> <!-- RCBC -->
<td><input type="number" class="form-control" value="0" style="width: 65px;"></td> <!-- NORF (Bangus) -->
<td><input type="number" class="form-control" value="0" style="width: 65px;"></td> <!-- Rice -->
<td><input type="number" class="form-control" value="0" style="width: 80px;" readonly></td>

    <td class="text-center">
        <button class="btn btn-danger btn-sm remove-row" disabled>
            <i class="fas fa-trash"></i>
        </button>
    </td>
</tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-lg text-white" id="saveDeductionBtn" style="background-color: #2c7a7b;">

                    <i class="fas fa-save"></i> Save Deduction
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    /* Ensure number inputs are wide enough to show 4-5 digits clearly */
    .number-input {
        min-width: 80px !important;
        text-align: right !important;
        padding-right: 10px !important;
    }
    /* Make sure table cells have enough width */
    .table td, .table th {
        padding: 8px 12px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add new row
    document.getElementById('addRowBtn').addEventListener('click', function() {
        const tableBody = document.getElementById('deductionTableBody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <select class="form-control">
                    <option>ETHICS AND GRIEVANCE COMMITTEE</option>
                    <option>MEDIATION AND CONCILIATION COMMITTEE</option>
                    <option>GENDER AND DEVELOPMENT COMMITTEE</option>
                </select>
            </td>
            <td><input type="text" class="form-control" placeholder="Name" readonly></td>
<td><input type="text" class="form-control" placeholder="ID" readonly></td>
<td><input type="number" class="form-control" placeholder="Hours" value="8" style="width: 65px;" readonly></td>
<td><input type="number" class="form-control" value="${document.getElementById('rate').value}" style="width: 70px;"></td> <!-- Rate is editable -->
<td><input type="number" class="form-control" value="${document.getElementById('transpoAllowance').value}" style="width: 80px;" readonly></td>
<td><input type="number" class="form-control" value="0" style="width: 65px;"></td> <!-- RCBC -->
<td><input type="number" class="form-control" value="0" style="width: 65px;"></td> <!-- NORF (Bangus) -->
<td><input type="number" class="form-control" value="0" style="width: 65px;"></td> <!-- Rice -->
<td><input type="number" class="form-control" value="0" style="width: 80px;" readonly></td>
            <td class="text-center">
                <button class="btn btn-danger btn-sm remove-row">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(newRow);
    });

    // Remove row functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row') || e.target.closest('.remove-row')) {
            const row = e.target.closest('tr');
            if (document.querySelectorAll('#deductionTableBody tr').length > 1) {
                row.remove();
            } else {
                alert('You need to keep at least one member!');
            }
        }
    });

    // Save button functionality
    document.getElementById('saveDeductionBtn').addEventListener('click', function() {
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;
        
        if (!dateFrom || !dateTo) {
            alert('Please select both date ranges!');
            return;
        }
        
        const rows = document.querySelectorAll('#deductionTableBody tr');
        const data = {
            period: { dateFrom, dateTo },
            members: []
        };
        
        rows.forEach(row => {
            const inputs = row.querySelectorAll('input, select');
            data.members.push({
                committee: inputs[0].value,
                name: inputs[1].value,
                memberId: inputs[2].value,
                dutyHours: inputs[3].value,
                rate: inputs[4].value,
                transpoAllowance: inputs[5].value,
                rcbc: inputs[6].value,
                norf: inputs[7].value,
                rice: inputs[8].value,
                deposit: inputs[9].value
            });
        });
        
        console.log('Data to save:', data);
        alert('Deduction data saved successfully!');
        $('#createDeductionModal').modal('hide');
    });
});
</script>

<?php include 'footer.php'; ?>