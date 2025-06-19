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

// Sample committee members
$members = [
    ['id' => 1, 'name' => 'John Doe', 'member_id' => '2025HG67C', 'pb_number' => '100FG783', 'committee' => 'Program Committee'],
    ['id' => 2, 'name' => 'Jane Smith', 'member_id' => '2025HG68D', 'pb_number' => '100FG784', 'committee' => 'Finance Committee'],
    ['id' => 3, 'name' => 'Mark Johnson', 'member_id' => '2025HG69E', 'pb_number' => '100FG785', 'committee' => 'Audit Committee'],
    ['id' => 4, 'name' => 'Lucy Brown', 'member_id' => '2025HG70F', 'pb_number' => '100FG786', 'committee' => 'Program Committee'],
    ['id' => 5, 'name' => 'Tom Hanks', 'member_id' => '2025HG71G', 'pb_number' => '100FG787', 'committee' => 'Membership Committee'],
    ['id' => 6, 'name' => 'Alice Cooper', 'member_id' => '2025HG72H', 'pb_number' => '100FG788', 'committee' => 'Finance Committee'],
];

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$filteredMembers = $members;

if ($searchQuery !== '') {
    $filteredMembers = array_filter($members, function ($member) use ($searchQuery) {
        return stripos($member['name'], $searchQuery) !== false ||
               stripos($member['member_id'], $searchQuery) !== false ||
               stripos($member['pb_number'], $searchQuery) !== false ||
               stripos($member['committee'], $searchQuery) !== false;
    });
}

$perPage = 5;
$total = count($filteredMembers);
$totalPages = max(1, ceil($total / $perPage));
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, min($page, $totalPages));
$offset = ($page - 1) * $perPage;
$paginated = array_slice($filteredMembers, $offset, $perPage);

include '../includes/header.php';
?>

<!-- Member Management Table -->
<div class="card card-primary card-outline elevation-2 p-3">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="mb-2 mb-md-0"></h5>
        <div class="d-flex align-items-center gap-2">
            <div class="input-group mb-0 mr-2" style="max-width:300px;">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0" style="color:#2b7d62;">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <input type="text" id="searchInput" class="form-control border-left-0" placeholder="Search..." aria-label="Search">
            </div>
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
            <table id="memberTable" class="table table-bordered table-hover table-striped bg-white" style="border:4px solid #2b7d62;">
                <thead class="thead" style="background:#2b7d62; color:#fff;">
                    <tr>
                        <th style="color: white;">ID</th>
                        <th style="color: white;">PB#</th>
                        <th style="color: white;">Member ID</th>
                        <th style="color: white;">Name</th>
                        <th style="color: white;">Committee</th>
                        <th style="color: white;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($paginated)): ?>
                        <tr><td colspan="6" class="text-center">No members found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($paginated as $member): ?>
                            <tr>
                                <td><?= $member['id'] ?></td>
                                <td><strong><?= htmlspecialchars($member['pb_number']) ?></strong></td>
                                <td><strong><?= htmlspecialchars($member['member_id']) ?></strong></td>
                                <td><strong><?= htmlspecialchars($member['name']) ?></strong></td>
                                <td><strong><?= htmlspecialchars($member['committee']) ?></strong></td>
                                <td>
                                    <button class="d-inline-flex justify-content-center align-items-center action-anim edit-btn"
                                            style="background:#2b7d62; color:#fff; border:none; border-radius:8px; width:32px; height:32px; margin-right:6px;"
                                            title="Edit"
                                            data-toggle="modal"
                                            data-target="#editMemberModal"
                                            data-id="<?= $member['id'] ?>"
                                            data-name="<?= htmlspecialchars($member['name']) ?>"
                                            data-member_id="<?= htmlspecialchars($member['member_id']) ?>"
                                            data-pb_number="<?= htmlspecialchars($member['pb_number']) ?>"
                                            data-committee="<?= htmlspecialchars($member['committee']) ?>">
                                        <i class="fas fa-edit" style="font-size:1.1rem;"></i>
                                    </button>
                                    <button class="d-inline-flex justify-content-center align-items-center action-anim delete-btn"
                                            style="background:#ffefef; color:#e74c3c; border:none; border-radius:8px; width:32px; height:32px;"
                                            title="Delete"
                                            data-toggle="modal"
                                            data-target="#deleteConfirmModal"
                                            data-id="<?= $member['id'] ?>"
                                            data-name="<?= htmlspecialchars($member['name']) ?>">
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
        <div class="mt-3 d-flex justify-content-end">
            <a href="?search=<?= urlencode($searchQuery) ?>&page=<?= max(1, $page - 1) ?>"
               class="btn mr-2 <?= $page == 1 ? 'disabled' : '' ?>"
               style="background-color: <?= $page == 1 ? '#a3c2b5' : '#2b7d62' ?>; color: white;">
               &laquo; Previous
            </a>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?search=<?= urlencode($searchQuery) ?>&page=<?= $i ?>"
                   class="btn mx-1"
                   style="background-color: <?= $i == $page ? '#2b7d62' : 'transparent' ?>;
                          color: <?= $i == $page ? 'white' : '#2b7d62' ?>;
                          border: 1px solid #2b7d62;">
                   <?= $i ?>
                </a>
            <?php endfor; ?>
            <a href="?search=<?= urlencode($searchQuery) ?>&page=<?= min($totalPages, $page + 1) ?>"
               class="btn ml-2 <?= $page == $totalPages ? 'disabled' : '' ?>"
               style="background-color: <?= $page == $totalPages ? '#a3c2b5' : '#2b7d62' ?>; color: white;">
               Next &raquo;
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modals -->
<?php include '../includes/userModals.php'; ?>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('searchInput');
  const table = document.getElementById('memberTable');
  if (!searchInput || !table) return;
  const rows = table.querySelectorAll('tbody tr');

  searchInput.addEventListener('keyup', function () {
    const filter = this.value.toLowerCase();
    rows.forEach(function (row) {
      const rowText = row.textContent.toLowerCase();
      row.style.display = rowText.includes(filter) ? '' : 'none';
    });
  });

  $('.edit-btn').on('click', function () {
    $('#edit_member_id').val($(this).data('id'));
    $('#edit_pb_number').val($(this).data('pb_number'));
    $('#edit_member_id_input').val($(this).data('member_id'));
    $('#edit_name').val($(this).data('name'));
    $('#edit_committee').val($(this).data('committee'));
  });

  $('.delete-btn').on('click', function () {
    $('#delete_id').val($(this).data('id'));
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
