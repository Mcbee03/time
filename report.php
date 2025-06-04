<?php
$pageTitle = "Report";
$activePage = "report";

// User data
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
    [
        'id' => 3,
        'name' => 'Mark Johnson',
        'member_id' => '2025HG69E',
        'pb_number' => '100F6785',
        'committee' => 'Logistics Committee'
    ],
    [
        'id' => 4,
        'name' => 'Lucy Brown',
        'member_id' => '2025HG70F',
        'pb_number' => '100F6786',
        'committee' => 'Marketing Committee'
    ],
    [
        'id' => 5,
        'name' => 'Tom Hanks',
        'member_id' => '2025HG71G',
        'pb_number' => '100F6787',
        'committee' => 'Program Committee'
    ],
    [
        'id' => 6,
        'name' => 'Alice Cooper',
        'member_id' => '2025HG72H',
        'pb_number' => '100F6788',
        'committee' => 'Finance Committee'
    ],
];

// Sample money deductions data for report
$moneyDeductions = [
    [
        'id' => 1,
        'user' => 'John Doe',
        'datetime' => '2025-06-01 09:15:00',
        'amount' => 150.00,
        'description' => 'Late arrival penalty'
    ],
    [
        'id' => 2,
        'user' => 'Jane Smith',
        'datetime' => '2025-06-02 14:30:00',
        'amount' => 200.00,
        'description' => 'Uniform purchase'
    ],
    [
        'id' => 3,
        'user' => 'John Doe',
        'datetime' => '2025-06-03 10:00:00',
        'amount' => 100.00,
        'description' => 'Lost ID replacement'
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

// --- Pagination Setup ---
$usersPerPage = 5;
$totalUsers = count($filteredUsers);
$totalPages = max(1, ceil($totalUsers / $usersPerPage));

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;
if ($currentPage > $totalPages) $currentPage = $totalPages;

$offset = ($currentPage - 1) * $usersPerPage;
$paginatedUsers = array_slice($filteredUsers, $offset, $usersPerPage);

include 'header.php';
?>


<!-- Money Deduction Report Card -->
<div class="card card-primary card-outline elevation-2 p-3 mt-4">
    <div class="card-header">
        <h5>Money Deduction Report</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>User</th>
                        <th>Amount Deducted</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($moneyDeductions)): ?>
                        <tr>
                            <td colspan="4" class="text-center">No deductions recorded.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($moneyDeductions as $deduction): ?>
                        <tr>
                            <td><?= date('M d, Y H:i:s', strtotime($deduction['datetime'])) ?></td>
                            <td><?= htmlspecialchars($deduction['user']) ?></td>
                            <td><?= number_format($deduction['amount'], 2) ?></td>
                            <td><?= htmlspecialchars($deduction['description']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
