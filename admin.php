<?php
$pageTitle = "Admin Management";
$activePage = "admin";

// Sample users
$users = [
    ['id' => 1, 'name' => 'John Doe', 'member_id' => '2025HG67C', 'pb_number' => '100F6783', 'role' => 'Superadmin'],
    ['id' => 2, 'name' => 'Jane Smith', 'member_id' => '2025HG68D', 'pb_number' => '100F6784', 'role' => 'Admin'],
    ['id' => 3, 'name' => 'Mark Johnson', 'member_id' => '2025HG69E', 'pb_number' => '100F6785', 'role' => 'Admin'],
    ['id' => 4, 'name' => 'Lucy Brown', 'member_id' => '2025HG70F', 'pb_number' => '100F6786', 'role' => 'Moderator'],
    ['id' => 5, 'name' => 'Tom Hanks', 'member_id' => '2025HG71G', 'pb_number' => '100F6787', 'role' => 'Admin'],
    ['id' => 6, 'name' => 'Alice Cooper', 'member_id' => '2025HG72H', 'pb_number' => '100F6788', 'role' => 'Admin'],
];

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$filteredUsers = $users;

if ($searchQuery !== '') {
    $filteredUsers = array_filter($users, function ($user) use ($searchQuery) {
        return stripos($user['name'], $searchQuery) !== false ||
               stripos($user['member_id'], $searchQuery) !== false ||
               stripos($user['pb_number'], $searchQuery) !== false;
    });
}

$usersPerPage = 5;
$totalUsers = count($filteredUsers);
$totalPages = max(1, ceil($totalUsers / $usersPerPage));

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages));
$offset = ($currentPage - 1) * $usersPerPage;
$paginatedUsers = array_slice($filteredUsers, $offset, $usersPerPage);

include 'header.php';
?>

<!-- Top User Info Card -->
<div class="card card-primary card-outline elevation-2 p-3 mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 col-12 mb-2">
                <strong>Name:</strong>
                <div>John Doe</div>
            </div>
            <div class="col-md-6 col-12 mb-2">
                <strong>Role:</strong>
                <div>Superadmin</div>
            </div>
        </div>
    </div>
</div>

<!-- User Management Table -->
<div class="card card-primary card-outline elevation-2 p-3">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="mb-2 mb-md-0">Admin Management</h5>
        <div class="d-flex align-items-center gap-2">
            <form method="GET" class="d-flex align-items-center mr-2">
                <i class="fas fa-search search-icon mr-2"></i>
                <input type="text" name="search" class="form-control" placeholder="Search" value="<?= htmlspecialchars($searchQuery) ?>">
            </form>
            <a href="#" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Add
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Member ID</th>
                        <th>PB#</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($paginatedUsers) === 0): ?>
                        <tr><td colspan="6" class="text-center">No users found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($paginatedUsers as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['member_id']) ?></td>
                            <td><?= htmlspecialchars($user['pb_number']) ?></td>
                            <td><span class="badge badge-secondary"><?= htmlspecialchars($user['role']) ?></span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary edit-btn"
                                    data-id="<?= $user['id'] ?>"
                                    data-name="<?= htmlspecialchars($user['name']) ?>"
                                    data-member-id="<?= htmlspecialchars($user['member_id']) ?>"
                                    data-pb-number="<?= htmlspecialchars($user['pb_number']) ?>"
                                    data-role="<?= htmlspecialchars($user['role']) ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-btn"
                                    data-toggle="modal"
                                    data-target="#deleteConfirmModal"
                                    data-id="<?= $user['id'] ?>"
                                    data-name="<?= htmlspecialchars($user['name']) ?>">
                                    <i class="fas fa-trash"></i>
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
        <div class="mt-3 d-flex justify-content-center">
            <a href="?search=<?= urlencode($searchQuery) ?>&page=<?= max(1, $currentPage - 1) ?>"
               class="btn mr-2 <?= $currentPage == 1 ? 'disabled' : '' ?>"
               style="background-color: <?= $currentPage == 1 ? '#a3c2b5' : '#2b7d62' ?>; color: white;">
               &laquo; Previous
            </a>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?search=<?= urlencode($searchQuery) ?>&page=<?= $i ?>"
                   class="btn mx-1"
                   style="background-color: <?= $i == $currentPage ? '#2b7d62' : 'transparent' ?>;
                          color: <?= $i == $currentPage ? 'white' : '#2b7d62' ?>;
                          border: 1px solid #2b7d62;">
                   <?= $i ?>
                </a>
            <?php endfor; ?>

            <a href="?search=<?= urlencode($searchQuery) ?>&page=<?= min($totalPages, $currentPage + 1) ?>"
               class="btn ml-2 <?= $currentPage == $totalPages ? 'disabled' : '' ?>"
               style="background-color: <?= $currentPage == $totalPages ? '#a3c2b5' : '#2b7d62' ?>; color: white;">
               Next &raquo;
            </a>
        </div>
        <?php endif; ?>
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
          <button type="button" class="close text-white" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">Are you sure you want to delete this?</div>
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
    $('.delete-btn').on('click', function () {
        var userId = $(this).data('id');
        $('#delete_id').val(userId);
    });
});
</script>

<?php include 'footer.php'; ?>
