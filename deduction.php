<?php
$pageTitle = "Deduction Management";
$activePage = "deductions";

// Sample data
$deductions = [
    ['id' => 1, 'deduction' => 'RCBC', 'date_from' => '6/1/2025', 'date_to' => '6/3/2025'],
    ['id' => 2, 'deduction' => 'Security Bank', 'date_from' => '6/2/2025', 'date_to' => '6/4/2025'],
    ['id' => 3, 'deduction' => 'BPI', 'date_from' => '6/3/2025', 'date_to' => '6/5/2025'],
    ['id' => 4, 'deduction' => 'Metrobank', 'date_from' => '6/4/2025', 'date_to' => '6/6/2025'],
    ['id' => 5, 'deduction' => 'UnionBank', 'date_from' => '6/5/2025', 'date_to' => '6/7/2025'],
    ['id' => 6, 'deduction' => 'Landbank', 'date_from' => '6/6/2025', 'date_to' => '6/8/2025'],
    ['id' => 7, 'deduction' => 'PNB', 'date_from' => '6/7/2025', 'date_to' => '6/9/2025'],
    ['id' => 8, 'deduction' => 'Chinabank', 'date_from' => '6/8/2025', 'date_to' => '6/10/2025'],
    ['id' => 9, 'deduction' => 'EastWest', 'date_from' => '6/9/2025', 'date_to' => '6/11/2025'],
    ['id' => 10, 'deduction' => 'BDO', 'date_from' => '6/10/2025', 'date_to' => '6/12/2025'],
    ['id' => 11, 'deduction' => 'HSBC', 'date_from' => '6/11/2025', 'date_to' => '6/13/2025'],
    ['id' => 12, 'deduction' => 'Citibank', 'date_from' => '6/12/2025', 'date_to' => '6/14/2025'],
    ['id' => 13, 'deduction' => 'Maybank', 'date_from' => '6/13/2025', 'date_to' => '6/15/2025'],
];

// Pagination logic
$perPage = 5;
$totalEntries = count($deductions);
$totalPages = ceil($totalEntries / $perPage);
$currentPage = isset($_GET['page']) ? max(1, min($totalPages, intval($_GET['page']))) : 1;
$offset = ($currentPage - 1) * $perPage;
$currentPageDeductions = array_slice($deductions, $offset, $perPage);

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$filteredDeductions = $deductions;

if ($searchQuery !== '') {
    $filteredDeductions = array_filter($deductions, function($deduction) use ($searchQuery) {
        return stripos($deduction['deduction'], $searchQuery) !== false || 
               stripos($deduction['date_from'], $searchQuery) !== false ||
               stripos($deduction['date_to'], $searchQuery) !== false;
    });
    $totalEntries = count($filteredDeductions);
    $totalPages = ceil($totalEntries / $perPage);
    $currentPageDeductions = array_slice($filteredDeductions, $offset, $perPage);
}

include 'header.php';
?>

<style>
    .btn-custom-green {
        background-color: #3DB272;
        border-color: #3DB272;
        color: white;
    }
    
    .btn-custom-green:hover {
        background-color: #35A068;
        border-color: #35A068;
        color: white;
    }
    
    .btn-outline-custom-green {
        color: #3DB272;
        border-color: #3DB272;
    }
    
    .btn-outline-custom-green:hover {
        background-color: #3DB272;
        color: white;
    }
    
    .bg-custom-green {
        background-color: #3DB272 !important;
    }
    
    .text-custom-green {
        color: #3DB272 !important;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #3DB272;
        border-color: #3DB272;
    }
    
    .pagination .page-link {
        color: #3DB272;
    }
</style>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <form method="GET" class="#">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="<?= htmlspecialchars($searchQuery) ?>">
                            <input type="hidden" name="page" value="1">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary btn-sm" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <button class="btn btn-custom-green btn-sm ml-3" data-toggle="modal" data-target="#addDeductionModal">
                        <i class="fas fa-plus mr-1"></i> ADD
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="30%">Deduction</th>
                                    <th width="20%">Date From</th>
                                    <th width="20%">Date To</th>
                                    <th width="25%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($currentPageDeductions) > 0): ?>
                                    <?php foreach ($currentPageDeductions as $deduction): ?>
                                    <tr>
                                        <td><?= $deduction['id'] ?></td>
                                        <td><?= htmlspecialchars($deduction['deduction']) ?></td>
                                        <td><?= htmlspecialchars($deduction['date_from']) ?></td>
                                        <td><?= htmlspecialchars($deduction['date_to']) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-custom-green mr-1 edit-deduction" 
                                                    data-toggle="modal" 
                                                    data-target="#editDeductionModal"
                                                    data-id="<?= $deduction['id'] ?>"
                                                    data-name="<?= htmlspecialchars($deduction['deduction']) ?>"
                                                    data-datefrom="<?= htmlspecialchars($deduction['date_from']) ?>"
                                                    data-dateto="<?= htmlspecialchars($deduction['date_to']) ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No deductions found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                        <div class="text-muted small">
                            Showing <?= $offset + 1 ?> to <?= min($offset + $perPage, $totalEntries) ?> of <?= $totalEntries ?> entries
                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                <?php if ($currentPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $currentPage - 1 ?>&search=<?= urlencode($searchQuery) ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($searchQuery) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($currentPage < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $currentPage + 1 ?>&search=<?= urlencode($searchQuery) ?>" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Deduction Modal -->
<div class="modal fade" id="addDeductionModal" tabindex="-1" role="dialog" aria-labelledby="addDeductionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-custom-green text-white">
                <h5 class="modal-title" id="addDeductionModalLabel">Create Deduction</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createDeductionForm">
                    <div class="form-group">
                        <label for="deductionName">Deduction Name</label>
                        <input type="text" class="form-control" id="deductionName" name="deduction_name" placeholder="Enter Deduction Name" required>
                    </div>
                    <div class="form-group">
                        <label for="dateFrom">Date From</label>
                        <input type="date" class="form-control" id="dateFrom" name="date_from" placeholder="Select Date" required>
                    </div>
                    <div class="form-group">
                        <label for="dateTo">Date To</label>
                        <input type="date" class="form-control" id="dateTo" name="date_to" placeholder="Select Date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" form="createDeductionForm" class="btn btn-custom-green">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Deduction Modal -->
<div class="modal fade" id="editDeductionModal" tabindex="-1" role="dialog" aria-labelledby="editDeductionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-custom-green text-white">
                <h5 class="modal-title" id="editDeductionModalLabel">Update Deduction</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateDeductionForm">
                    <input type="hidden" id="editDeductionId" name="id">
                    <div class="form-group">
                        <label for="editDeductionName">Deduction Name</label>
                        <input type="text" class="form-control" id="editDeductionName" name="deduction_name" placeholder="Enter Deduction Name" required>
                    </div>
                    <div class="form-group">
                        <label for="editDateFrom">Date From</label>
                        <input type="date" class="form-control" id="editDateFrom" name="date_from" placeholder="Select Date" required>
                    </div>
                    <div class="form-group">
                        <label for="editDateTo">Date To</label>
                        <input type="date" class="form-control" id="editDateTo" name="date_to" placeholder="Select Date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" form="updateDeductionForm" class="btn btn-custom-green">Submit</button>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>