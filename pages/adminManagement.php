<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';
require_once '../logic/AdminManagement/adminManagementLogic.php';

$pageTitle = "Admin Management";
$activePage = "admin";
<<<<<<< HEAD

// Sample admins
$admins = [
    ['id' => 1, 'name' => 'John Doe',     'member_id' => '2025HG67C', 'pb_number' => '100F6783'],
    ['id' => 2, 'name' => 'Jane Smith',   'member_id' => '2025HG68D', 'pb_number' => '100F6784'],
    ['id' => 3, 'name' => 'Mark Johnson', 'member_id' => '2025HG69E', 'pb_number' => '100F6785'],
    ['id' => 4, 'name' => 'Lucy Brown',   'member_id' => '2025HG70F', 'pb_number' => '100F6786'],
    ['id' => 5, 'name' => 'Tom Hanks',    'member_id' => '2025HG71G', 'pb_number' => '100F6787'],
    ['id' => 6, 'name' => 'Alice Cooper', 'member_id' => '2025HG72H', 'pb_number' => '100F6788'],
];

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filtered = $admins;

if ($search !== '') {
    $filtered = array_filter($admins, function($a) use($search) {
        return stripos($a['name'], $search)!==false
            || stripos($a['member_id'], $search)!==false
            || stripos($a['pb_number'], $search)!==false;
    });
}

$perPage = 5;
$total   = count($filtered);
$pages   = max(1, ceil($total/$perPage));
$page    = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page    = max(1, min($page, $pages));
$offset  = ($page-1)*$perPage;
$paginated = array_slice($filtered, $offset, $perPage);

include '../includes/header.php';
=======
>>>>>>> 95b3aff88a9c36e700340ea5563d2726737de462
?>

<!-- Admin Management Table -->
<div class="card card-primary card-outline elevation-2 p-3">
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
<<<<<<< HEAD
    <div class="d-flex align-items-center gap-2">
      <div class="input-group" style="max-width:220px;">
        <div class="input-group-prepend">
          <span class="input-group-text bg-white border-right-0" style="color:#2b7d62;">
            <i class="fas fa-search"></i>
          </span>
        </div>
        <input type="text" id="searchInput" class="form-control border-left-0" placeholder="Search">
      </div>
    </div>
    <button class="btn btn-success d-flex align-items-center"
            style="background:#2b7d62; color:#fff; font-weight:600; border-radius:6px; border:none; padding:7px 16px;"
            data-toggle="modal" data-target="#addAdminModal">
      <span style="font-size:1.3rem; margin-right:7px; line-height:1;">
        <i class="fas fa-plus-circle"></i>
      </span>
      <span style="font-size:1rem;">Add</span>
    </button>
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
          <?php if (empty($paginated)): ?>
            <tr><td colspan="5" class="text-center">No admins found.</td></tr>
          <?php else: ?>
            <?php foreach ($paginated as $admin): ?>
              <tr>
                <td style="font-weight:700;"><?= $admin['id'] ?></td>
                <td style="font-weight:700;"><?= htmlspecialchars($admin['name']) ?></td>
                <td style="font-weight:700;"><?= htmlspecialchars($admin['member_id']) ?></td>
                <td style="font-weight:700;"><?= htmlspecialchars($admin['pb_number']) ?></td>
                <td>
                    <button class="d-inline-flex justify-content-center align-items-center action-anim edit-btn"
                            style="background:#2b7d62; color:#fff; border:none; border-radius:8px; width:32px; height:32px; margin-right:6px; padding:0; transition: transform 0.15s, box-shadow 0.15s;"
                            title="Edit"
                            data-toggle="modal"
                            data-target="#editAdminModal"
                            data-id="<?= $admin['id'] ?>"
                            data-name="<?= htmlspecialchars($admin['name']) ?>"
                            data-member_id="<?= htmlspecialchars($admin['member_id']) ?>"
                            data-pb_number="<?= htmlspecialchars($admin['pb_number']) ?>">
                        <i class="fas fa-edit" style="font-size:1.1rem;"></i>
                    </button>
                    <button class="d-inline-flex justify-content-center align-items-center action-anim delete-btn"
                            style="background:#ffefef; color:#e74c3c; border:none; border-radius:8px; width:32px; height:32px; padding:0; transition: transform 0.15s, box-shadow 0.15s;"
                            title="Delete"
                            data-toggle="modal"
                            data-target="#deleteConfirmModal"
                            data-id="<?= $admin['id'] ?>"
                            data-name="<?= htmlspecialchars($admin['name']) ?>">
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
    <?php if ($pages > 1): ?>
      <div class="mt-3 d-flex justify-content-end">
        <a href="?search=<?= urlencode($search) ?>&page=<?= max(1,$page-1) ?>"
           class="btn mr-2 <?= $page==1?'disabled':'' ?>"
           style="background-color: <?= $page==1?'#a3c2b5':'#2b7d62' ?>; color:white;">
          &laquo; Previous
        </a>
        <?php for($i=1;$i<=$pages;$i++): ?>
          <a href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"
             class="btn mx-1"
             style="background-color: <?= $i==$page?'#2b7d62':'transparent' ?>;
                    color: <?= $i==$page?'white':'#2b7d62' ?>;
                    border:1px solid #2b7d62;">
            <?= $i ?>
          </a>
        <?php endfor; ?>
        <a href="?search=<?= urlencode($search) ?>&page=<?= min($pages,$page+1) ?>"
           class="btn ml-2 <?= $page==$pages?'disabled':'' ?>"
           style="background-color: <?= $page==$pages?'#a3c2b5':'#2b7d62' ?>; color:white;">
          Next &raquo;
        </a>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Add Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1" role="dialog" aria-labelledby="addAdminModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" action="add_admin.php">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #2b7d62; color: white;">
          <h5 class="modal-title">Add Admin</h5>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Member ID</label>
            <input type="text" name="member_id" class="form-control" required>
          </div>
          <div class="form-group">
            <label>PB Number</label>
            <input type="text" name="pb_number" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn" style="background-color: #2b7d62; color: white;">Save Admin</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Admin Modal -->
<div class="modal fade" id="editAdminModal" tabindex="-1" role="dialog" aria-labelledby="editAdminModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" action="edit_admin.php" id="editAdminForm">
      <input type="hidden" name="id" id="edit_admin_id">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #2b7d62; color: white;">
          <h5 class="modal-title" id="editAdminModalLabel">Edit Admin</h5>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" id="edit_admin_name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Member ID</label>
            <input type="text" name="member_id" id="edit_admin_member_id" class="form-control" required>
          </div>
          <div class="form-group">
            <label>PB Number</label>
            <input type="text" name="pb_number" id="edit_admin_pb_number" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn" style="background-color: #2b7d62; color: white;">Save Changes</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" action="delete_admin.php" id="deleteForm">
      <input type="hidden" name="delete_id" id="delete_id" value="">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #2b7d62; color: white;">
          <h5 class="modal-title">Confirm Delete</h5>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">Are you sure you want to delete this admin?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn" style="background-color: #2b7d62; color: white;">Yes, Delete</button>
=======
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
>>>>>>> 95b3aff88a9c36e700340ea5563d2726737de462
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<<<<<<< HEAD

<script>
document.getElementById('searchInput').addEventListener('keyup', function () {
  var filter = this.value.toLowerCase();
  var rows = document.querySelectorAll('#adminTable tbody tr');
  rows.forEach(function (row) {
    var match = false;
    row.querySelectorAll('td').forEach(function (cell) {
      if (cell.textContent.toLowerCase().indexOf(filter) > -1) {
        match = true;
      }
    });
    row.style.display = match ? '' : 'none';
  });
});
</script>


<style>
.action-anim:hover, .action-anim:focus {
    transform: scale(1.13) rotate(-4deg);
    box-shadow: 0 2px 8px 0 rgba(44,125,98,0.13);
    z-index: 2;
}
</style>

<?php include '../includes/footer.php'; ?>
=======
<!-- Include Modals -->
<?php 
include '../views/AdminManagement/addModal.php';
include '../views/AdminManagement/editModal.php';
include '../views/AdminManagement/deleteModal.php';
?>

<script src="../assets/js/adminManagement.js"></script>

<?php include '../includes/footer.php'; ?>
>>>>>>> 95b3aff88a9c36e700340ea5563d2726737de462
