<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: /pages/login.php');
    exit;
}

$pageTitle = "Allowance Management";
$activePage = "monthlyallowance";

include '../includes/header.php';
include '../logic/MonthlyManagement/monthlyManagementLogic.php';
?>

<div class="main-content-container">
    <!-- Filter Form -->
    <div class="card card-primary card-outline elevation-2 p-3 mb-4">
        <form method="GET">
            <div class="form-row align-items-end">
                <div class="form-group col-md-3">
                    <label for="date_from" class="font-weight-bold">Date From</label>
                    <input type="date" id="date_from" name="date_from" class="form-control filter-input" value="<?= htmlspecialchars($date_from) ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="date_to" class="font-weight-bold">Date To</label>
                    <input type="date" id="date_to" name="date_to" class="form-control filter-input" value="<?= htmlspecialchars($date_to) ?>">
                </div>
                <div class="form-group col-md-3">
                    <div class="d-flex flex-column flex-md-row">
                        <button type="submit" class="btn btn-success mb-2 mb-md-0 mr-md-2 w-100">
                            <i class="fas fa-filter mr-2"></i> Filter
                        </button>
                        <button type="button" class="btn btn-outline-clear w-100" id="clearFilterBtn">
                            <i class="fas fa-times-circle mr-2"></i> Clear
                        </button>
                    </div>
                </div>
                <div class="form-group col-md-3 d-flex justify-content-md-end">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addAllowanceModal">
                        <i class="fas fa-plus-circle mr-2"></i> Add Allowance
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Allowance Table -->
    <div class="card card-primary card-outline elevation-2 p-3">
        <div class="card-body">
            <div class="table-responsive">
                <table id="allowanceTable" class="table table-bordered table-hover table-striped bg-white" style="border:4px solid #2b7d62;">
                    <thead class="thead" style="background:#2b7d62; color:#fff;">
                        <tr>
                            <th style="color: white; font-weight:700;">ID</th>
                            <th style="color: white; font-weight:700;">Date From</th>
                            <th style="color: white; font-weight:700;">Date To</th>
                            <th style="color: white; font-weight:700;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($paginated)): ?>
                            <tr><td colspan="4" class="text-center">No allowances found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($paginated as $index => $row): ?>
                                <tr>
                                    <td style="font-weight:700;"><?= (($page - 1) * $perPage) + $index + 1 ?></td>
                                    <td style="font-weight:700;"><?= htmlspecialchars($row['date_from']) ?></td>
                                    <td style="font-weight:700;"><?= htmlspecialchars($row['date_to']) ?></td>
                                    <td>
                                        <button class="d-inline-flex justify-content-center align-items-center action-anim edit-btn"
                                                style="background:#2b7d62; color:#fff; border:none; border-radius:8px; width:32px; height:32px; margin-right:6px; padding:0; transition: transform 0.15s, box-shadow 0.15s;"
                                                title="Edit"
                                                data-toggle="modal"
                                                data-target="#editAllowanceModal"
                                                data-id="<?= $row['id'] ?>"
                                                data-date_from="<?= htmlspecialchars($row['date_from']) ?>"
                                                data-date_to="<?= htmlspecialchars($row['date_to']) ?>">
                                            <i class="fas fa-edit" style="font-size:1.1rem;"></i>
                                        </button>
                                        <button class="d-inline-flex justify-content-center align-items-center action-anim delete-btn"
                                                style="background:#ffefef; color:#e74c3c; border:none; border-radius:8px; width:32px; height:32px; padding:0; transition: transform 0.15s, box-shadow 0.15s;"
                                                title="Delete"
                                                data-toggle="modal"
                                                data-target="#deleteConfirmModal"
                                                data-id="<?= $row['id'] ?>">
                                            <i class="fas fa-trash-alt" style="font-size:1.1rem;"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap">
                    <div class="text-muted mb-2 mb-md-0">
                        <?php
                            $startEntry = ($totalItems === 0) ? 0 : $offset + 1;
                            $endEntry = min($offset + $perPage, $totalItems);
                        ?>
                        Showing <?= $startEntry ?> to <?= $endEntry ?> of <?= $totalItems ?> entries
                    </div>

                    <div class="pagination-container d-flex flex-wrap justify-content-end">
                        <a href="?<?= http_build_query(array_merge($_GET, ['page' => max(1, $page - 1)])) ?>"
                           class="btn mr-2 <?= $page == 1 ? 'disabled' : '' ?>"
                           style="background-color: <?= $page == 1 ? '#a3c2b5' : '#2b7d62' ?>; color:white;">
                            &laquo; Previous
                        </a>
                        <?php for($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"
                               class="btn mx-1"
                               style="background-color: <?= $i == $page ? '#2b7d62' : 'transparent' ?>;
                                      color: <?= $i == $page ? 'white' : '#2b7d62' ?>;
                                      border:1px solid #2b7d62;">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        <a href="?<?= http_build_query(array_merge($_GET, ['page' => min($totalPages, $page + 1)])) ?>"
                           class="btn ml-2 <?= $page == $totalPages ? 'disabled' : '' ?>"
                           style="background-color: <?= $page == $totalPages ? '#a3c2b5' : '#2b7d62' ?>; color:white;">
                            Next &raquo;
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
include '../views/MonthlyManagement/addModal.php'; 
include '../views/MonthlyManagement/editModal.php'; 
include '../views/MonthlyManagement/deleteModal.php'; 
?>

<script src="../assets/js/monthlyManagement.js"></script>