<?php
$pageTitle = "Management";
$activePage = "deduction";

$users = [
    ['id' => 1, 'name' => 'John Doe', 'member_id' => '2025HG67C', 'pb_number' => '100F6783', 'committee' => 'Program Committee'],
    ['id' => 2, 'name' => 'Jane Smith', 'member_id' => '2025HG68D', 'pb_number' => '100F6784', 'committee' => 'Finance Committee'],
    ['id' => 3, 'name' => 'Mark Lee', 'member_id' => '2025HG69E', 'pb_number' => '100F6785', 'committee' => 'Logistics Committee'],
    ['id' => 4, 'name' => 'Lara Croft', 'member_id' => '2025HG70F', 'pb_number' => '100F6786', 'committee' => 'Marketing Committee'],
    ['id' => 5, 'name' => 'Bruce Wayne', 'member_id' => '2025HG71G', 'pb_number' => '100F6787', 'committee' => 'Security'],
    ['id' => 6, 'name' => 'Clark Kent', 'member_id' => '2025HG72H', 'pb_number' => '100F6788', 'committee' => 'Media'],
    ['id' => 7, 'name' => 'Diana Prince', 'member_id' => '2025HG73I', 'pb_number' => '100F6789', 'committee' => 'Program Committee'],
    ['id' => 8, 'name' => 'Peter Parker', 'member_id' => '2025HG74J', 'pb_number' => '100F6790', 'committee' => 'Marketing Committee'],
    ['id' => 9, 'name' => 'Tony Stark', 'member_id' => '2025HG75K', 'pb_number' => '100F6791', 'committee' => 'Finance Committee'],
    ['id' => 10, 'name' => 'Steve Rogers', 'member_id' => '2025HG76L', 'pb_number' => '100F6792', 'committee' => 'Logistics Committee'],
    ['id' => 11, 'name' => 'Natasha Romanoff', 'member_id' => '2025HG77M', 'pb_number' => '100F6793', 'committee' => 'Security'],
];

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// Default filteredUsers to all users if no search
$filteredUsers = $users;

// Default filteredUsers to all users if no search
$filteredUsers = $users;

if ($searchQuery !== '') {
    $filteredUsers = array_filter($users, function ($user) use ($searchQuery) {
        return stripos($user['name'], $searchQuery) !== false ||
               stripos($user['member_id'], $searchQuery) !== false ||
               stripos($user['pb_number'], $searchQuery) !== false;
               stripos($user['member_id'], $searchQuery) !== false ||
               stripos($user['pb_number'], $searchQuery) !== false;
    });
}

$usersPerPage = 5;
$usersPerPage = 5;
$totalUsers = count($filteredUsers);
$totalPages = max(1, ceil($totalUsers / $usersPerPage));
$totalPages = max(1, ceil($totalUsers / $usersPerPage));
$currentPage = isset($_GET['page']) ? max(1, min((int)$_GET['page'], $totalPages)) : 1;
$offset = ($currentPage - 1) * $usersPerPage;
$paginatedUsers = array_slice($filteredUsers, $offset, $usersPerPage);

include '../includes/header.php';
?>

<div class="main-content-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="page-title">DEDUCTION SETTINGS</h3>
        <div>
            <form method="GET" class="search-box d-inline-block mr-2">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="search" class="form-control" placeholder="Search" value="<?= htmlspecialchars($searchQuery) ?>">
            </form>
            <button class="btn btn-primary" style="background-color: #2b7d62; border-color: #2b7d62;" data-toggle="modal" data-target="#addUserModal">
                <i class="fas fa-plus"></i> ADD
            </button>
        </div>
    </div>

    <div class="card card-primary card-outline elevation-2 p-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Member ID</th>
                            <th>PB#</th>
                            <th>Committee</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($paginatedUsers as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['member_id']) ?></td>
                            <td><?= htmlspecialchars($user['pb_number']) ?></td>
                            <td><?= htmlspecialchars($user['committee']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary edit-btn" data-toggle="modal" data-target="#updateUserModal" 
                                    data-id="<?= $user['id'] ?>"
                                    data-name="<?= htmlspecialchars($user['name']) ?>"
                                    data-member-id="<?= htmlspecialchars($user['member_id']) ?>"
                                    data-pb-number="<?= htmlspecialchars($user['pb_number']) ?>"
                                    data-committee="<?= htmlspecialchars($user['committee']) ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-btn" 
                                    data-toggle="modal" data-target="#deleteConfirmModal" 
                                    data-id="<?= $user['id'] ?>" 
                                    data-name="<?= htmlspecialchars($user['name']) ?>">
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

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #2b7d62; color: white;">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: white;">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                    <form id="addUserForm">
                        <div class="form-group">
                            <label for="addUserName">Name</label>
                            <input type="text" class="form-control" id="addUserName" placeholder="Enter name" required>
                        </div>
                        <div class="form-group">
                            <label for="addUserMemberId">Member ID</label>
                            <input type="text" class="form-control" id="addUserMemberId" placeholder="Enter member ID" required>
                        </div>
                        <div class="form-group">
                            <label for="addUserPbNumber">PB#</label>
                            <input type="text" class="form-control" id="addUserPbNumber" placeholder="Enter PB number" required>
                        </div>
                        <div class="form-group">
                            <label for="addUserCommittee">Committee</label>
                            <select class="form-control" id="addUserCommittee" required>
                                <option value="">Select Committee</option>
                                <option value="Program Committee">Program Committee</option>
                                <option value="Finance Committee">Finance Committee</option>
                                <option value="Logistics Committee">Logistics Committee</option>
                                <option value="Marketing Committee">Marketing Committee</option>
                                <option value="Security">Security</option>
                                <option value="Media">Media</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" style="background-color: #2b7d62; border-color: #2b7d62;" id="addUserBtn">Add User</button>
                    <button type="button" class="btn btn-primary" style="background-color: #2b7d62; border-color: #2b7d62;" id="addUserBtn">Add User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update User Modal -->
    <div class="modal fade" id="updateUserModal" tabindex="-1" role="dialog" aria-labelledby="updateUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #2b7d62; color: white;">
                    <h5 class="modal-title" id="updateUserModalLabel">Update User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: white;">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateUserForm">
                    <form id="updateUserForm">
                        <input type="hidden" id="updateUserId">
                        <div class="form-group">
                            <label for="updateUserName">Name</label>
                            <input type="text" class="form-control" id="updateUserName" placeholder="Enter name" required>
                        </div>
                        <div class="form-group">
                            <label for="updateUserMemberId">Member ID</label>
                            <input type="text" class="form-control" id="updateUserMemberId" placeholder="Enter member ID" required>
                        </div>
                        <div class="form-group">
                            <label for="updateUserPbNumber">PB#</label>
                            <input type="text" class="form-control" id="updateUserPbNumber" placeholder="Enter PB number" required>
                        </div>
                        <div class="form-group">
                            <label for="updateUserCommittee">Committee</label>
                            <select class="form-control" id="updateUserCommittee" required>
                                <option value="">Select Committee</option>
                                <option value="Program Committee">Program Committee</option>
                                <option value="Finance Committee">Finance Committee</option>
                                <option value="Logistics Committee">Logistics Committee</option>
                                <option value="Marketing Committee">Marketing Committee</option>
                                <option value="Security">Security</option>
                                <option value="Media">Media</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" style="background-color: #2b7d62; border-color: #2b7d62;" id="updateUserBtn">Update User</button>
                </div>
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
    // Prefill update modal when clicking edit button
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            const memberId = btn.getAttribute('data-member-id');
            const pbNumber = btn.getAttribute('data-pb-number');
            const committee = btn.getAttribute('data-committee');

            document.getElementById('updateUserId').value = id;
            document.getElementById('updateUserName').value = name;
            document.getElementById('updateUserMemberId').value = memberId;
            document.getElementById('updateUserPbNumber').value = pbNumber;
            document.getElementById('updateUserCommittee').value = committee;
        });
    });

    // Prefill delete modal when clicking delete button
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            document.getElementById('delete_id').value = id;
            // You can optionally add the name inside modal body if you want
        });
    // Prefill update modal when clicking edit button
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            const memberId = btn.getAttribute('data-member-id');
            const pbNumber = btn.getAttribute('data-pb-number');
            const committee = btn.getAttribute('data-committee');

            document.getElementById('updateUserId').value = id;
            document.getElementById('updateUserName').value = name;
            document.getElementById('updateUserMemberId').value = memberId;
            document.getElementById('updateUserPbNumber').value = pbNumber;
            document.getElementById('updateUserCommittee').value = committee;
        });
    });

    // Prefill delete modal when clicking delete button
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            document.getElementById('delete_id').value = id;
            // You can optionally add the name inside modal body if you want
        });
    });

    // Add User button handler (example only)
    document.getElementById('addUserBtn').addEventListener('click', () => {
        // Validation and AJAX submit logic here
        alert('Add user functionality to be implemented');
        $('#addUserModal').modal('hide');
    });

    // Update User button handler (example only)
    document.getElementById('updateUserBtn').addEventListener('click', () => {
        // Validation and AJAX submit logic here
        alert('Update user functionality to be implemented');
        $('#updateUserModal').modal('hide');
    });
    // Add User button handler (example only)
    document.getElementById('addUserBtn').addEventListener('click', () => {
        // Validation and AJAX submit logic here
        alert('Add user functionality to be implemented');
        $('#addUserModal').modal('hide');
    });

    // Update User button handler (example only)
    document.getElementById('updateUserBtn').addEventListener('click', () => {
        // Validation and AJAX submit logic here
        alert('Update user functionality to be implemented');
        $('#updateUserModal').modal('hide');
    });
</script>

<?php include 'footer.php'; ?>