<?php
$pageTitle = "Management";
$activePage = "users";

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
$filteredUsers = $users; // Initialize with all users

if ($searchQuery !== '') {
    $filteredUsers = array_filter($users, function ($user) use ($searchQuery) {
        return stripos($user['name'], $searchQuery) !== false ||
               stripos($user['member_id'], $searchQuery) !== false ||
               stripos($user['pb_number'], $searchQuery) !== false ||
               stripos($user['committee'], $searchQuery) !== false;
    });
}

$usersPerPage = 5;
$totalUsers = count($filteredUsers);
$totalPages = ceil($totalUsers / $usersPerPage);
$currentPage = isset($_GET['page']) ? max(1, min((int)$_GET['page'], $totalPages)) : 1;
$offset = ($currentPage - 1) * $usersPerPage;
$paginatedUsers = array_slice($filteredUsers, $offset, $usersPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle; ?></title>
    <!-- Bootstrap 4.6 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-content-container {
            padding: 20px;
        }
        .search-box {
            position: relative;
        }
        .search-icon {
            position: absolute;
            top: 10px;
            left: 10px;
            color: #6c757d;
        }
        .search-box input {
            padding-left: 35px;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .btn-outline-primary {
            color: #2b7d62;
            border-color: #2b7d62;
        }
        .btn-outline-primary:hover {
            background-color: #2b7d62;
            color: white;
        }
        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="main-content-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="page-title">MANAGEMENT</h3>
            <div>
                <form method="GET" class="form-inline">
                    <div class="input-group mr-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" name="search" class="form-control" placeholder="Search" value="<?= htmlspecialchars($searchQuery) ?>">
                    </div>
                    <button class="btn btn-primary" style="background-color: #2b7d62; border-color: #2b7d62;" data-toggle="modal" data-target="#addUserModal">
                        <i class="fas fa-plus"></i> ADD
                    </button>
                </form>
            </div>
        </div>

        <div class="card card-outline-primary shadow-sm">
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
                            <?php if (count($paginatedUsers) > 0): ?>
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
                                            data-id="<?= $user['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No users found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="mt-3 d-flex justify-content-center">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <li class="page-item <?= $currentPage == 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?search=<?= urlencode($searchQuery) ?>&page=<?= $currentPage - 1 ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="?search=<?= urlencode($searchQuery) ?>&page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?= $currentPage == $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?search=<?= urlencode($searchQuery) ?>&page=<?= $currentPage + 1 ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            </div>
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
                <form method="POST" action="process_user.php">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="addUserName">Name</label>
                            <input type="text" class="form-control" id="addUserName" name="name" placeholder="Enter name" required>
                        </div>
                        <div class="form-group">
                            <label for="addUserMemberId">Member ID</label>
                            <input type="text" class="form-control" id="addUserMemberId" name="member_id" placeholder="Enter member ID" required>
                        </div>
                        <div class="form-group">
                            <label for="addUserPbNumber">PB#</label>
                            <input type="text" class="form-control" id="addUserPbNumber" name="pb_number" placeholder="Enter PB number" required>
                        </div>
                        <div class="form-group">
                            <label for="addUserCommittee">Committee</label>
                            <select class="form-control" id="addUserCommittee" name="committee" required>
                                <option value="">Select Committee</option>
                                <option value="Program Committee">Program Committee</option>
                                <option value="Finance Committee">Finance Committee</option>
                                <option value="Logistics Committee">Logistics Committee</option>
                                <option value="Marketing Committee">Marketing Committee</option>
                                <option value="Security">Security</option>
                                <option value="Media">Media</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_user" class="btn btn-primary" style="background-color: #2b7d62; border-color: #2b7d62;">Add User</button>
                    </div>
                </form>
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
                <form method="POST" action="process_user.php">
                    <div class="modal-body">
                        <input type="hidden" id="updateUserId" name="id">
                        <div class="form-group">
                            <label for="updateUserName">Name</label>
                            <input type="text" class="form-control" id="updateUserName" name="name" placeholder="Enter name" required>
                        </div>
                        <div class="form-group">
                            <label for="updateUserMemberId">Member ID</label>
                            <input type="text" class="form-control" id="updateUserMemberId" name="member_id" placeholder="Enter member ID" required>
                        </div>
                        <div class="form-group">
                            <label for="updateUserPbNumber">PB#</label>
                            <input type="text" class="form-control" id="updateUserPbNumber" name="pb_number" placeholder="Enter PB number" required>
                        </div>
                        <div class="form-group">
                            <label for="updateUserCommittee">Committee</label>
                            <select class="form-control" id="updateUserCommittee" name="committee" required>
                                <option value="">Select Committee</option>
                                <option value="Program Committee">Program Committee</option>
                                <option value="Finance Committee">Finance Committee</option>
                                <option value="Logistics Committee">Logistics Committee</option>
                                <option value="Marketing Committee">Marketing Committee</option>
                                <option value="Security">Security</option>
                                <option value="Media">Media</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_user" class="btn btn-primary" style="background-color: #2b7d62; border-color: #2b7d62;">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="process_user.php">
                    <input type="hidden" id="deleteUserId" name="id">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this user?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_user" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    
    <script>
    $(document).ready(function() {
        // Update modal with user data when edit button clicked
        $('.edit-btn').click(function() {
            var userId = $(this).data('id');
            var userName = $(this).data('name');
            var userMemberId = $(this).data('member-id');
            var userPbNumber = $(this).data('pb-number');
            var userCommittee = $(this).data('committee');
            
            $('#updateUserId').val(userId);
            $('#updateUserName').val(userName);
            $('#updateUserMemberId').val(userMemberId);
            $('#updateUserPbNumber').val(userPbNumber);
            $('#updateUserCommittee').val(userCommittee);
        });
        
        // Set user ID when delete button clicked
        $('.delete-btn').click(function() {
            var userId = $(this).data('id');
            $('#deleteUserId').val(userId);
        });
    });
    </script>

    </body>
</html>

    <?php include 'footer.php'; ?>