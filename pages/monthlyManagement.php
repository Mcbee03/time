<?php
session_start();
include '../config/db.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['admin_id'])) {
    // Store current URL for redirect back after login
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: /pages/login.php');
    exit;
}

$pageTitle = "Allowance Management";
$activePage = "monthlyallowance";

include '../includes/header.php';

// Sample allowances data
$allowances = [
    ['id'=>1,'name'=>'John Doe','type'=>'Transportation','date'=>'2025-06-01','amount'=>'₱200.00','remarks'=>'Late shift'],
    ['id'=>2,'name'=>'Jane Smith','type'=>'Meal','date'=>'2025-06-01','amount'=>'₱150.00','remarks'=>'Double shift'],
    ['id'=>3,'name'=>'Robert Johnson','type'=>'Lodging','date'=>'2025-06-01','amount'=>'₱500.00','remarks'=>'Out of town'],
    ['id'=>4,'name'=>'Anna Cruz','type'=>'Transportation','date'=>'2025-06-02','amount'=>'₱200.00','remarks'=>'Night duty'],
    ['id'=>5,'name'=>'Leo Torres','type'=>'Meal','date'=>'2025-06-02','amount'=>'₱150.00','remarks'=>'Regular'],
    ['id'=>6,'name'=>'Sara Lim','type'=>'Transportation','date'=>'2025-06-03','amount'=>'₱200.00','remarks'=>'Late entry'],
    ['id'=>7,'name'=>'Marco Reyes','type'=>'Lodging','date'=>'2025-06-03','amount'=>'₱500.00','remarks'=>'Seminar'],
    ['id'=>8,'name'=>'Kim David','type'=>'Transportation','date'=>'2025-06-04','amount'=>'₱200.00','remarks'=>'Holiday duty'],
    ['id'=>9,'name'=>'Ella Santos','type'=>'Meal','date'=>'2025-06-04','amount'=>'₱150.00','remarks'=>''],
    ['id'=>10,'name'=>'Joey Bautista','type'=>'Transportation','date'=>'2025-06-05','amount'=>'₱200.00','remarks'=>'Sick cover'],
];

// Filter logic
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';
$filtered = $allowances;

if ($date_from || $date_to) {
    $filtered = array_filter($allowances, function($row) use ($date_from, $date_to) {
        $row_date = $row['date'];
        if ($date_from && $date_to) {
            return $row_date >= $date_from && $row_date <= $date_to;
        } elseif ($date_from) {
            return $row_date >= $date_from;
        } elseif ($date_to) {
            return $row_date <= $date_to;
        }
        return true;
    });
}

$perPage     = 5;
$totalItems  = count($filtered);
$totalPages  = max(1, ceil($totalItems/$perPage));
$page        = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page        = max(1, min($page,$totalPages));
$offset      = ($page-1)*$perPage;
$paginated   = array_slice($filtered,$offset,$perPage);


?>

<div class="main-content-container">
    <!-- Filter Form -->
    <div class="card card-primary card-outline elevation-2 p-3 mb-4">
        <form method="GET" class="form-row align-items-end">
            <div class="form-group col-md-3">
                <label for="date_from" class="font-weight-bold">Date From</label>
                <input type="date" id="date_from" name="date_from" class="form-control filter-input" value="<?= htmlspecialchars($date_from) ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="date_to" class="font-weight-bold">Date To</label>
                <input type="date" id="date_to" name="date_to" class="form-control filter-input" value="<?= htmlspecialchars($date_to) ?>">
            </div>
            <div class="form-group col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-success mr-2" style="background:#2b7d62; border-radius:6px;">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <button type="button" class="btn btn-light" id="clearFilterBtn" style="border:1px solid #2b7d62; color:#2b7d62; border-radius:6px;">
                    <i class="fas fa-times-circle mr-2"></i> Clear
                </button>
            </div>
            <div class="form-group col-md-3 d-flex justify-content-end align-items-end">
                <button type="button" class="btn btn-success" style="background:#2b7d62; border-radius:6px;" data-toggle="modal" data-target="#addAllowanceModal">
                    <i class="fas fa-plus-circle mr-2"></i> Add Allowance
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card card-primary card-outline elevation-2 p-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped bg-white" style="border:4px solid #2b7d62;">
                    <thead style="background:#2b7d62; color:white; font-weight:bold;">
                        <tr>
                            <th style="color: white; font-weight:700;">ID</th>
                            <th style="color: white; font-weight:700;">Date From</th>
                            <th style="color: white; font-weight:700;">Date To</th>
                            <th style="color: white; font-weight:700;">Action</th>
                        </tr>
                    </thead>
                    <tbody style="font-weight:600;">
                        <?php if (empty($paginated)): ?>
                            <tr><td colspan="4" class="text-center">No data found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($paginated as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td><?= htmlspecialchars($row['date']) ?></td>
                                    <td><?= htmlspecialchars($row['date']) ?></td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-sm d-flex align-items-center justify-content-center mr-1"
                                                    style="background-color:#2b7d62; border-radius:10px; width:30px; height:30px;">
                                                <i class="fas fa-edit text-white" style="font-size:14px;"></i>
                                            </button>
                                            <button class="btn btn-sm d-flex align-items-center justify-content-center"
                                                    style="background-color:#ffecec; border-radius:10px; width:30px; height:30px;">
                                                <i class="fas fa-trash-alt" style="color:#ff4d4d; font-size:14px;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="mt-3 d-flex justify-content-end">
                <a href="?<?= http_build_query(array_merge($_GET, ['page'=>max(1,$page-1)])) ?>"
                   class="btn mr-2 <?= $page==1?'disabled':'' ?>"
                   style="background-color: <?= $page==1?'#a3c2b5':'#2b7d62' ?>; color:white;">
                  &laquo; Prev
                </a>
                <?php for ($i=1; $i<=$totalPages; $i++): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page'=>$i])) ?>"
                   class="btn mx-1"
                   style="background-color: <?= $i==$page?'#2b7d62':'transparent' ?>;
                          color: <?= $i==$page?'white':'#2b7d62' ?>;
                          border: 1px solid #2b7d62;">
                  <?=$i?>
                </a>
                <?php endfor; ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page'=>min($totalPages,$page+1)])) ?>"
                   class="btn ml-2 <?= $page==$totalPages?'disabled':'' ?>"
                   style="background-color: <?= $page==$totalPages?'#a3c2b5':'#2b7d62' ?>; color:white;">
                  Next &raquo;
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addAllowanceModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius:16px;">
                <div class="modal-header border-0 pb-0">
                    <h4 class="modal-title font-weight-bold text-center w-100">Create Allowance</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:2rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <form>
                        <div class="form-row align-items-end mb-3">
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold mb-1">Date From</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold mb-1">Date To</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="form-group col-md-2">
                                <label class="font-weight-bold mb-1">Rate</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold mb-1">Transpo Allowance</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-success w-100 font-weight-bold" style="background:#2b7d62; border-radius:8px;">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" style="border:2px solid #2b7d62;">
                            <thead>
                                <tr style="background:#2b7d62; color:#fff; text-align:center;">
                                    <th>Committee</th>
                                    <th>Name</th>
                                    <th>Member ID</th>
                                    <th>Duty Hours</th>
                                    <th>Rate</th>
                                    <th>Transpo</th>
                                    <th>RCBC</th>
                                    <th>Less Bangus</th>
                                    <th>Rice</th>
                                    <th>RSD</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn font-weight-bold text-white px-4" style="background:#2b7d62; border-radius:8px;">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('clearFilterBtn').addEventListener('click', function() {
    document.querySelectorAll('.filter-input').forEach(function(input) {
        input.value = '';
    });
    this.closest('form').submit();
});
</script>

<style>
@media (min-width: 768px) {
    .form-row.align-items-end > .form-group {
        margin-bottom: 0;
    }
}
</style>