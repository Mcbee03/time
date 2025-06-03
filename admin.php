<?php
$pageTitle = "User Management";
$activePage = "users";

// Your page-specific PHP code here
$users = [
    [
        'id' => 1,
        'name' => 'John Doe',
        'member_id' => '2025HG67C',
        'pb_number' => '100F6783',
        'committee' => 'Program Committee'
    ],
];

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$filteredUsers = $users;

if ($searchQuery !== '') {
    $filteredUsers = array_filter($users, function($user) use ($searchQuery) {
        return stripos($user['name'], $searchQuery) !== false || 
               stripos($user['member_id'], $searchQuery) !== false ||
               stripos($user['pb_number'], $searchQuery) !== false;
    });
}

include 'header.php';
?>

    <div class="user-info">
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

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-2 mb-md-0">User Management</h5>
            <form method="GET" class="search-box">
                <i class="fas fa-search search-icon"></i>
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
                        <?php foreach ($filteredUsers as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['member_id']) ?></td>
                            <td><?= htmlspecialchars($user['pb_number']) ?></td>
                            <td><span class="badge badge-committee"><?= htmlspecialchars($user['committee']) ?></span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>