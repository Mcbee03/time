<?php
session_start();
include '../config/db.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['admin_id'])) {
    // Store current URL for redirect back after login
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: /pages/login.php');
    exit;
}

$pageTitle = "Deduction Management";
$activePage = "deduction";

// Sample deductions data
$deductions = [
    ['id'=>1, 'deduction'=>'RCBC',      'date_from'=>'2025-06-01', 'date_to'=>'2025-06-30'],
    ['id'=>2, 'deduction'=>'METROBANK', 'date_from'=>'2025-06-01', 'date_to'=>'2025-06-30'],
    ['id'=>3, 'deduction'=>'BDO',       'date_from'=>'2025-06-01', 'date_to'=>'2025-06-30'],
    ['id'=>4, 'deduction'=>'LANDBANK',  'date_from'=>'2025-06-01', 'date_to'=>'2025-06-30'],
    ['id'=>5, 'deduction'=>'PNB',       'date_from'=>'2025-06-01', 'date_to'=>'2025-06-30'],
];

include '../includes/header.php';
?>

<!-- Deduction Management Table -->
<div class="card card-primary card-outline elevation-2 p-3">
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
    <h5 class="mb-2 mb-md-0"></h5>
    <div class="d-flex align-items-center gap-2">
      <!-- Search bar with icon beside +Add button -->
      <div class="input-group mr-2" style="max-width:220px;">
        <div class="input-group-prepend">
          <span class="input-group-text bg-white border-right-0" style="color:#2b7d62;">
            <i class="fas fa-search"></i>
          </span>
        </div>
        <input type="text" id="searchInput" class="form-control border-left-0" placeholder="Search deduction...">
      </div>
      <button class="btn btn-success d-flex align-items-center"
              style="background:#2b7d62; color:#fff; font-weight:600; border-radius:6px; border:none; padding:7px 16px;"
              data-toggle="modal" data-target="#addDeductionModal">
        <span style="font-size:1.3rem; margin-right:7px; line-height:1;">
          <i class="fas fa-plus-circle"></i>
        </span>
        <span style="font-size:1rem;">Add Deduction</span>
      </button>
    </div>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table id="deductionTable" class="table table-bordered table-hover table-striped bg-white" style="border:4px solid #2b7d62;">
        <thead class="thead" style="background:#2b7d62; color:#fff;">
          <tr>
            <th style="color: white; font-weight:700;">ID</th>
            <th style="color: white; font-weight:700;">Deduction</th>
            <th style="color: white; font-weight:700;">Date From</th>
            <th style="color: white; font-weight:700;">Date To</th>
            <th style="color: white; font-weight:700;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($deductions as $row): ?>
            <tr>
              <td style="font-weight:700;"><?= $row['id'] ?></td>
              <td style="font-weight:700;"><?= htmlspecialchars($row['deduction']) ?></td>
              <td style="font-weight:700;"><?= htmlspecialchars($row['date_from']) ?></td>
              <td style="font-weight:700;"><?= htmlspecialchars($row['date_to']) ?></td>
              <td>
                <button class="d-inline-flex justify-content-center align-items-center action-anim edit-btn"
                        style="background:#2b7d62; color:#fff; border:none; border-radius:8px; width:32px; height:32px; margin-right:6px; padding:0; transition: transform 0.15s, box-shadow 0.15s;"
                        title="Edit"
                        data-toggle="modal"
                        data-target="#editDeductionModal"
                        data-id="<?= $row['id'] ?>"
                        data-deduction="<?= htmlspecialchars($row['deduction']) ?>"
                        data-date_from="<?= htmlspecialchars($row['date_from']) ?>"
                        data-date_to="<?= htmlspecialchars($row['date_to']) ?>">
                    <i class="fas fa-edit" style="font-size:1.1rem;"></i>
                </button>
                <button class="d-inline-flex justify-content-center align-items-center action-anim delete-btn"
                        style="background:#ffefef; color:#e74c3c; border:none; border-radius:8px; width:32px; height:32px; padding:0; transition: transform 0.15s, box-shadow 0.15s;"
                        title="Delete"
                        data-toggle="modal"
                        data-target="#deleteDeductionModal"
                        data-id="<?= $row['id'] ?>">
                    <i class="fas fa-trash-alt" style="font-size:1.1rem;"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Deduction Modal (sample only) -->
<div class="modal fade" id="addDeductionModal" tabindex="-1" role="dialog" aria-labelledby="addDeductionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form>
      <div class="modal-content">
        <div class="modal-header" style="background-color: #2b7d62; color: white;">
          <h5 class="modal-title">Add Deduction</h5>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Deduction</label>
            <input type="text" name="deduction" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Date From</label>
            <input type="date" name="date_from" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Date To</label>
            <input type="date" name="date_to" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn" style="background-color: #2b7d62; color: white;">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Deduction Modal (sample only) -->
<div class="modal fade" id="editDeductionModal" tabindex="-1" role="dialog" aria-labelledby="editDeductionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form>
      <div class="modal-content">
        <div class="modal-header" style="background-color: #2b7d62; color: white;">
          <h5 class="modal-title">Edit Deduction</h5>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <!-- Form fields for editing -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn" style="background-color: #2b7d62; color: white;">Save Changes</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Delete Deduction Modal (sample only) -->
<div class="modal fade" id="deleteDeductionModal" tabindex="-1" role="dialog" aria-labelledby="deleteDeductionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form>
      <div class="modal-content">
        <div class="modal-header" style="background-color: #2b7d62; color: white;">
          <h5 class="modal-title">Delete Deduction</h5>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this deduction?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn" style="background-color: #2b7d62; color: white;">Delete</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
// Instant search for deduction table
document.getElementById('searchInput').addEventListener('keyup', function() {
  var filter = this.value.toLowerCase();
  var rows = document.querySelectorAll('#deductionTable tbody tr');
  rows.forEach(function(row) {
    var deductionCell = row.cells[1]; // 2nd column is Deduction
    if (deductionCell && deductionCell.textContent.toLowerCase().indexOf(filter) > -1) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
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
