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

    <!-- Table -->
    <div class="card card-primary card-outline elevation-2 p-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped bg-white">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date From</th>
                            <th>Date To</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($paginated)): ?>
                            <tr><td colspan="4" class="text-center">No data found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($paginated as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td><?= htmlspecialchars($row['date_from']) ?></td>
                                    <td><?= htmlspecialchars($row['date_to']) ?></td>
                                    <td>
                                        <div class="d-flex">
                                            <button class="btn btn-sm edit-btn mr-2"
                                                data-toggle="modal"
                                                data-target="#editAllowanceModal"
                                                data-id="<?= $row['id'] ?>"
                                                data-committee="<?= htmlspecialchars($row['committee']) ?>"
                                                data-name="<?= htmlspecialchars($row['name']) ?>"
                                                data-member_id="<?= htmlspecialchars($row['member_id']) ?>"
                                                data-date_from="<?= htmlspecialchars($row['date_from']) ?>"
                                                data-date_to="<?= htmlspecialchars($row['date_to']) ?>"
                                                data-duty_hours="<?= htmlspecialchars($row['duty_hours']) ?>"
                                                data-rate="<?= htmlspecialchars($row['rate']) ?>"
                                                data-transpo_allowance="<?= htmlspecialchars($row['transpo_allowance']) ?>"
                                                data-rcbc="<?= htmlspecialchars($row['rcbc']) ?>"
                                                data-norf="<?= htmlspecialchars($row['norf']) ?>"
                                                data-rice="<?= htmlspecialchars($row['rice']) ?>"
                                                data-savings="<?= htmlspecialchars($row['savings']) ?>"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm delete-btn"
                                                data-toggle="modal"
                                                data-target="#deleteConfirmModal"
                                                data-id="<?= $row['id'] ?>"
                                                data-name="<?= htmlspecialchars($row['name']) ?>"
                                                title="Delete">
                                                <i class="fas fa-trash-alt"></i>
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
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => max(1, $page - 1)])) ?>" class="btn mr-2 <?= $page == 1 ? 'disabled' : '' ?>">
                        &laquo; Previous
                    </a>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" class="btn mx-1 <?= $i == $page ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => min($totalPages, $page + 1)])) ?>" class="btn ml-2 <?= $page == $totalPages ? 'disabled' : '' ?>">
                        Next &raquo;
                    </a>
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
