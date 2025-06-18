<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';
require_once '../logic/AdminManagement/adminManagementLogic.php';

$pageTitle = "Admin Management";
$activePage = "admin";
?>

<!-- Admin Management Table -->
<div class="card card-primary card-outline elevation-2 p-3">
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
    <h5 class="mb-2 mb-md-0">Admin Management</h5>
    <div class="d-flex align-items-center gap-2">
      <!-- Search bar -->
      <form method="GET" class="d-flex align-items-center mr-2">
        <div class="input-group" style="max-width:220px;">
          <div class="input-group-prepend">
            <span class="input-group-text bg-white border-right-0" style="color:#2b7d62;">
              <i class="fas fa-search"></i>
            </span>
          </div>
          <input type="text" name="search" class="form-control border-left-0" placeholder="Search name..." value="<?= htmlspecialchars($searchQuery) ?>">
        </div>
      </form>
      <!-- Add Admin Button -->
      <button class="btn btn-success d-flex align-items-center"
              style="background:#2b7d62; color:#fff; font-weight:600; border-radius:6px; border:none; padding:7px 16px;"
              data-toggle="modal" data-target="#addModal">
        <span style="font-size:1.3rem; margin-right:7px; line-height:1;">
          <i class="fas fa-plus-circle"></i>
        </span>
        <span style="font-size:1rem;">Admin</span>
      </button>
    </div>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table id="adminTable" class="table table-bordered table-hover table-striped bg-white" style="border:4px solid #2b7d62;">
        <thead class="thead" style="background:#2b7d62; color:#fff;">
          <tr>
            <th style="color: white; font-weight:700;">ID</th>
            <th style="color: white; font-weight:700;">Name</th>
            <th style="color: white; font-weight:700;">Member ID</th>
            <th style="color: white; font-weight:700;">PB#</th>
            <th style="color: white; font-weight:700;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($paginatedUsers)): ?>
            <tr><td colspan="5" class="text-center">No admins found.</td></tr>
          <?php else: ?>
            <?php foreach ($paginatedUsers as $index => $user): ?>
              <tr data-id="<?= $user['Id'] ?>" class="admin-row">
                <td style="font-weight:700;"><?= (($currentPage - 1) * $perPage) + $index + 1 ?></td>
                <td style="font-weight:700;"><?= htmlspecialchars($user['Name']) ?></td>
                <td style="font-weight:700;"><?= htmlspecialchars($user['MemberID']) ?></td>
                <td style="font-weight:700;"><?= htmlspecialchars($user['PBNum']) ?></td>
                <td>
                    <button class="d-inline-flex justify-content-center align-items-center action-anim edit-btn"
                            style="background:#2b7d62; color:#fff; border:none; border-radius:8px; width:32px; height:32px; margin-right:6px; padding:0; transition: transform 0.15s, box-shadow 0.15s;"
                            title="Edit"
                            data-toggle="modal"
                            data-target="#editModal"
                            data-id="<?= $user['Id'] ?>">
                        <i class="fas fa-edit" style="font-size:1.1rem;"></i>
                    </button>
                    <button class="d-inline-flex justify-content-center align-items-center action-anim delete-btn"
                            style="background:#ffefef; color:#e74c3c; border:none; border-radius:8px; width:32px; height:32px; padding:0; transition: transform 0.15s, box-shadow 0.15s;"
                            title="Delete"
                            data-toggle="modal"
                            data-target="#deleteModal"
                            data-id="<?= $user['Id'] ?>"
                            data-name="<?= htmlspecialchars($user['Name']) ?>">
                        <i class="fas fa-trash-alt" style="font-size:1.1rem;"></i>
                    </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if ($totalPages > 1): ?>
      <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap">
        <div class="text-muted mb-2 mb-md-0">
          <?php
            $startEntry = ($totalRows === 0) ? 0 : $offset + 1;
            $endEntry = min($offset + $perPage, $totalRows);
          ?>
          Showing <?= $startEntry ?> to <?= $endEntry ?> of <?= $totalRows ?> entries
        </div>
        <div class="pagination-container d-flex flex-wrap justify-content-end">
          <!-- Pagination links -->
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Include Modals -->
<?php 
include '../views/AdminManagement/addModal.php';
include '../views/AdminManagement/editModal.php';
include '../views/AdminManagement/deleteModal.php';
?>

<script src="../assets/js/adminManagement.js"></script>

<?php include '../includes/footer.php'; ?>