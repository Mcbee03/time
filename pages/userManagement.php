  <?php
  session_start();
  include '../config/db.php';

  // Redirect to login if not authenticated
  if (!isset($_SESSION['admin_id'])) {
      $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
      header('Location: /pages/login.php');
      exit;
  }

  $pageTitle = "Users Management";
  $activePage = "users";

  include '../logic/UserManagement/userManagementLogic.php';
  include '../includes/header.php';
  ?>

  <!-- User Management Table -->
  <div class="card card-primary card-outline elevation-2 p-3">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
      <h5 class="mb-2 mb-md-0"></h5>
      <div class="d-flex align-items-center gap-2">
        <form method="GET" class="d-flex align-items-center mr-2">
          <div class="input-group" style="max-width:220px;">
            <div class="input-group-prepend">
              <span class="input-group-text bg-white border-right-0" style="color:#2b7d62;">
                <i class="fas fa-search"></i>
              </span>
            </div>
            <input type="text" id="searchInput" name="search" class="form-control border-left-0" placeholder="Search..." value="<?= htmlspecialchars($searchQuery) ?>">
          </div>
        </form>
        <button class="btn btn-success d-flex align-items-center"
                style="background:#2b7d62; color:#fff; font-weight:600; border-radius:6px; border:none; padding:7px 16px;"
                data-toggle="modal" data-target="#addMemberModal">
          <span style="font-size:1.3rem; margin-right:7px; line-height:1;">
            <i class="fas fa-plus-circle"></i>
          </span>
          <span style="font-size:1rem;">Add</span>
        </button>
      </div>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table id="userTable" class="table table-bordered table-hover table-striped bg-white" style="border:4px solid #2b7d62;">
          <thead class="thead" style="background:#2b7d62; color:#fff;">
            <tr>
              <th>ID</th>
              <th>PB#</th>
              <th>Member ID</th>
              <th>Name</th>
              <th>Committee</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($paginated)): ?>
              <tr><td colspan="6" class="text-center">No users found.</td></tr>
            <?php else: ?>
              <?php foreach ($paginated as $index => $member): ?>
                <tr data-id="<?= $member['Id'] ?>" class="admin-row">
                  <td style="font-weight:700;"><?= (($currentPage - 1) * $perPage) + $index + 1 ?></td>
                  <td style="font-weight:700;"><?= htmlspecialchars($member['PBNum']) ?></td>
                  <td style="font-weight:700;"><?= htmlspecialchars($member['MemberID']) ?></td>
                  <td style="font-weight:700;"><?= htmlspecialchars($member['Name']) ?></td>
                  <td style="font-weight:700;"><?= htmlspecialchars($member['Committee']) ?></td>
                  <td>
                    <button class="d-inline-flex justify-content-center align-items-center action-anim edit-btn"
                            style="background:#2b7d62; color:#fff; border:none; border-radius:8px; width:32px; height:32px; margin-right:6px; padding:0; transition: transform 0.15s, box-shadow 0.15s;"
                            title="Edit"
                            data-toggle="modal"
                            data-target="#editMemberModal"
                            data-id="<?= $member['Id'] ?>"
                            data-name="<?= htmlspecialchars($member['Name']) ?>"
                            data-memberid="<?= htmlspecialchars($member['MemberID']) ?>"
                            data-pbnum="<?= htmlspecialchars($member['PBNum']) ?>"
                            data-committee="<?= htmlspecialchars($member['Committee']) ?>">
                      <i class="fas fa-edit" style="font-size:1.1rem;"></i>
                    </button>
                    <button class="d-inline-flex justify-content-center align-items-center action-anim delete-btn"
                            style="background:#ffefef; color:#e74c3c; border:none; border-radius:8px; width:32px; height:32px; padding:0; transition: transform 0.15s, box-shadow 0.15s;"
                            title="Delete"
                            data-toggle="modal"
                            data-target="#deleteMemberModal"
                            data-id="<?= $member['Id'] ?>"
                            data-name="<?= htmlspecialchars($member['Name']) ?>">
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
            Showing <?= $startEntry ?> to <?= $endEntry ?> of <?= $totalRows ?> entries
          </div>
          <div class="pagination-container d-flex flex-wrap justify-content-end">
            <a href="?search=<?= urlencode($searchQuery) ?>&page=<?= max(1, $currentPage - 1) ?>"
              class="btn mr-2 <?= $currentPage == 1 ? 'disabled' : '' ?>"
              style="background-color: <?= $currentPage == 1 ? '#a3c2b5' : '#2b7d62' ?>; color:white;">
              &laquo; Previous
            </a>
            <?php for($i = 1; $i <= $totalPages; $i++): ?>
              <a href="?search=<?= urlencode($searchQuery) ?>&page=<?= $i ?>"
                class="btn mx-1"
                style="background-color: <?= $i == $currentPage ? '#2b7d62' : 'transparent' ?>;
                        color: <?= $i == $currentPage ? 'white' : '#2b7d62' ?>;
                        border:1px solid #2b7d62;">
                <?= $i ?>
              </a>
            <?php endfor; ?>
            <a href="?search=<?= urlencode($searchQuery) ?>&page=<?= min($totalPages, $currentPage + 1) ?>"
              class="btn ml-2 <?= $currentPage == $totalPages ? 'disabled' : '' ?>"
              style="background-color: <?= $currentPage == $totalPages ? '#a3c2b5' : '#2b7d62' ?>; color:white;">
              Next &raquo;
            </a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <?php
  include '../views/UserManagement/addModal.php';
  include '../views/UserManagement/editModal.php';
  include '../views/UserManagement/deleteModal.php';
  ?>

  <script src="../assets/js/userManagement.js"></script>