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
    [
        'id' => 2,
        'name' => 'Jane Smith',
        'member_id' => '2025HG68D',
        'pb_number' => '100F6784',
        'committee' => 'Finance Committee'
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

<div class="main-content-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">USER MANAGEMENT</h1>
        <div>
            <form method="GET" class="search-box d-inline-block mr-2">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="search" class="form-control" placeholder="Search" value="<?= htmlspecialchars($searchQuery) ?>">
            </form>
            <button class="btn btn-primary">
                <i class="fas fa-plus"></i> ADD
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
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
</div>

<?php include 'footer.php'; ?>