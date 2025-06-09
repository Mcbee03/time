<?php
$pageTitle = "User Management";
$activePage = "users";


include '../includes/header.php';
?>

<?php include 'footer.php'; ?>

<!-- Top User Info Card -->
<div class="user-info card card-primary card-outline elevation-2 p-3 mb-3">
    <div class="row">
        <div class="col-md-4 col-12 mb-2">
            <div class="user-info-label">Name:</div>
            <div>John Doe</div>
        </div>
        <div class="col-md-4 col-12 mb-2">
            <div class="user-info-label">Member ID:</div>
            <div>2025HG67C</div>
        </div>
        <div class="col-md-4 col-12 mb-2">
            <div class="user-info-label">PB#:</div>
            <div>100F6783</div>
        </div>
    </div>
</div>


<!-- Card with the requested classes -->
<div class="card card-primary card-outline elevation-2 p-3">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="mb-2 mb-md-0">User Management</h5>
        <form method="GET" class="search-box d-flex align-items-center">
            <i class="fas fa-search search-icon mr-2"></i>
            <input type="text" name="search" class="form-control" placeholder="Search" value="<?= htmlspecialchars($searchQuery) ?>">
        </form>
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
                        <th>Committee</th>
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
                            <td><span class="badge badge-secondary"><?= htmlspecialchars($user['committee']) ?></span></td>
                            <td>
                                <button 
                                    class="btn btn-sm btn-outline-primary edit-btn"
                                    data-id="<?= $user['id'] ?>"
                                    data-name="<?= htmlspecialchars($user['name']) ?>"
                                    data-member-id="<?= htmlspecialchars($user['member_id']) ?>"
                                    data-pb-number="<?= htmlspecialchars($user['pb_number']) ?>"
                                    data-committee="<?= htmlspecialchars($user['committee']) ?>"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <!-- Delete Button triggers modal -->
                                <button 
                                    class="btn btn-sm btn-outline-danger delete-btn" 
                                    data-toggle="modal" 
                                    data-target="#deleteConfirmModal"
                                    data-id="<?= $user['id'] ?>"
                                    data-name="<?= htmlspecialchars($user['name']) ?>"
                                >
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
    // Populate Delete Modal with user data when delete button clicked
    $('.delete-btn').on('click', function () {
        var button = $(this);
        var userId = button.data('id');
        var userName = button.data('name');

        $('#delete_id').val(userId);
        $('#deleteUserName').text(userName);
    });

    // You can add your edit button JS here if needed
});
</script>

<?php include '..//footer.php'; ?>

