<?php
$pageTitle = "Allowance Management";
$activePage = "allowances";

include 'header.php';

// Sample allowances data
$allowances = [
    ['id' => 1, 'name' => 'John Doe', 'type' => 'Transportation', 'date' => '2025-06-01', 'amount' => '₱200.00', 'remarks' => 'Late shift'],
    ['id' => 2, 'name' => 'Jane Smith', 'type' => 'Meal', 'date' => '2025-06-01', 'amount' => '₱150.00', 'remarks' => 'Double shift'],
    ['id' => 3, 'name' => 'Robert Johnson', 'type' => 'Lodging', 'date' => '2025-06-01', 'amount' => '₱500.00', 'remarks' => 'Out of town'],
    ['id' => 4, 'name' => 'Anna Cruz', 'type' => 'Transportation', 'date' => '2025-06-02', 'amount' => '₱200.00', 'remarks' => 'Night duty'],
    ['id' => 5, 'name' => 'Leo Torres', 'type' => 'Meal', 'date' => '2025-06-02', 'amount' => '₱150.00', 'remarks' => 'Regular'],
    ['id' => 6, 'name' => 'Sara Lim', 'type' => 'Transportation', 'date' => '2025-06-03', 'amount' => '₱200.00', 'remarks' => 'Late entry'],
    ['id' => 7, 'name' => 'Marco Reyes', 'type' => 'Lodging', 'date' => '2025-06-03', 'amount' => '₱500.00', 'remarks' => 'Seminar'],
    ['id' => 8, 'name' => 'Kim David', 'type' => 'Transportation', 'date' => '2025-06-04', 'amount' => '₱200.00', 'remarks' => 'Holiday duty'],
    ['id' => 9, 'name' => 'Ella Santos', 'type' => 'Meal', 'date' => '2025-06-04', 'amount' => '₱150.00', 'remarks' => ''],
    ['id' => 10, 'name' => 'Joey Bautista', 'type' => 'Transportation', 'date' => '2025-06-05', 'amount' => '₱200.00', 'remarks' => 'Sick cover'],
];

// Pagination logic
$perPage = 5;
$totalItems = count($allowances);
$totalPages = ceil($totalItems / $perPage);
$currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$startIndex = ($currentPage - 1) * $perPage;
$pagedAllowances = array_slice($allowances, $startIndex, $perPage);

// For pagination links
$searchQuery = $_GET['search'] ?? '';
?>

<div class="main-content-container">
    <!-- FILTER -->
    <div class="card card-body mb-4 shadow-sm">
        <form method="GET" class="form-row align-items-end">
            <div class="form-group col-md-3">
                <label for="date_from">Date From</label>
                <input type="date" id="date_from" name="date_from" class="form-control" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="date_to">Date To</label>
                <input type="date" id="date_to" name="date_to" class="form-control" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>">
            </div>
            <div class="form-group col-auto">
                <button type="submit" class="btn btn-custom-green btn-block"><i class="fas fa-filter"></i> Filter</button>
            </div>
            <div class="form-group col-auto ml-auto">
                <button type="button" class="btn btn-custom-green btn-block" data-toggle="modal" data-target="#addAllowanceModal"><i class="fas fa-plus"></i> Add Allowance</button>
            </div>
        </form>
    </div>

    <!-- LIST -->
    <div class="card card-primary card-outline elevation-2 p-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Employee Name</th>
                            <th>Allowance Type</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pagedAllowances as $a): ?>
                            <tr>
                                <td><?= $a['id'] ?></td>
                                <td><?= htmlspecialchars($a['name']) ?></td>
                                <td><?= htmlspecialchars($a['type']) ?></td>
                                <td><?= htmlspecialchars($a['date']) ?></td>
                                <td><?= htmlspecialchars($a['amount']) ?></td>
                                <td><?= htmlspecialchars($a['remarks']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                    <button 
                                        class="btn btn-sm btn-outline-danger btn-delete" 
                                        data-toggle="modal" 
                                        data-target="#deleteConfirmModal" 
                                        data-id="<?= $a['id'] ?>"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="mt-3 d-flex justify-content-center">
                    <a 
                        href="?search=<?= urlencode($searchQuery) ?>&page=<?= max(1, $currentPage - 1) ?>" 
                        class="btn mr-2 <?= $currentPage == 1 ? 'disabled' : '' ?>"
                        role="button" 
                        aria-disabled="<?= $currentPage == 1 ? 'true' : 'false' ?>"
                        style="background-color: <?= $currentPage == 1 ? '#a3c2b5' : '#2b7d62' ?>; color: white; border-color: #2b7d62;"
                    >
                        &laquo; Previous
                    </a>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a 
                            href="?search=<?= urlencode($searchQuery) ?>&page=<?= $i ?>" 
                            class="btn mx-1"
                            style="
                                background-color: <?= $i == $currentPage ? '#2b7d62' : 'transparent' ?>; 
                                color: <?= $i == $currentPage ? 'white' : '#2b7d62' ?>; 
                                border: 1px solid #2b7d62;
                            "
                        >
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <a 
                        href="?search=<?= urlencode($searchQuery) ?>&page=<?= min($totalPages, $currentPage + 1) ?>" 
                        class="btn ml-2 <?= $currentPage == $totalPages ? 'disabled' : '' ?>"
                        role="button" 
                        aria-disabled="<?= $currentPage == $totalPages ? 'true' : 'false' ?>"
                        style="background-color: <?= $currentPage == $totalPages ? '#a3c2b5' : '#2b7d62' ?>; color: white; border-color: #2b7d62;"
                    >
                        Next &raquo;
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Allowance Modal -->
<div class="modal fade" id="addAllowanceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-custom-green">
                <h5 class="modal-title">Add Manual Allowance</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <form id="allowanceForm" method="POST" action="process_allowance.php">
                <div class="modal-body">
                    <div class="form-row mb-3">
                        <div class="form-group col-md-6">
                            <label>Date From</label>
                            <input type="date" name="date_from" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Date To</label>
                            <input type="date" name="date_to" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Employee</label>
                            <select name="employee_id" class="form-control" required>
                                <option value="">Select Employee</option>
                                <?php
                                $employees = [
                                    ['id' => 1, 'name' => 'John Doe'],
                                    ['id' => 2, 'name' => 'Jane Smith'],
                                    ['id' => 3, 'name' => 'Robert Johnson']
                                ];
                                foreach ($employees as $emp) {
                                    echo "<option value='{$emp['id']}'>" . htmlspecialchars($emp['name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Member ID</label>
                            <input type="text" name="member_id" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Allowance Type</label>
                            <input type="text" name="allowance_type" class="form-control" placeholder="e.g. Transportation" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>Duty Hours</label>
                            <input type="number" name="duty_hours" step="0.01" class="form-control" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Rate</label>
                            <input type="number" name="rate" step="0.01" class="form-control" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Transpo Allowance</label>
                            <input type="number" name="transpo_allowance" step="0.01" class="form-control" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Remarks</label>
                            <input type="text" name="remarks" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-custom-green">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
                   
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" action="process_allowance.php" id="deleteForm">
      <input type="hidden" name="delete_id" id="delete_id" value="">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #2b7d62; color: white;">
          <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn" style="background-color: #2b7d62; color: white;">Yes, Delete</button>
        </div>
      </div>
    </form>
  </div>
</div>


<script>
$(document).ready(function () {
    // Update member_id input when employee selected
    $('select[name="employee_id"]').change(function () {
        const empId = $(this).val(); 
        if (empId) {
            $('input[name="member_id"]').val("EMP" + empId.toString().padStart(4, '0'));
        } else {
            $('input[name="member_id"]').val("");
        }
    });

    // Pass allowance ID to delete modal hidden input
    $('.btn-delete').on('click', function () {
        var allowanceId = $(this).data('id');
        $('#delete_id').val(allowanceId);
    });
});
</script>

<?php include 'footer.php'; ?>
