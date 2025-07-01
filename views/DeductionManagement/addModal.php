<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<div class="modal fade" id="addDeductionModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Deduction</h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="addDeductionForm" action="../logic/DeductionManagement/addDeductionLogic.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

          <div class="form-group">
            <label>Deduction Name</label>
            <input type="text" name="deduction" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Start Date</label>
            <input type="date" name="date_from" class="form-control" required>
          </div>

          <div class="form-group">
            <label>End Date</label>
            <input type="date" name="date_to" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn">
            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            Add Deduction
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
