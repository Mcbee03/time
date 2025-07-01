<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<div class="modal fade" id="editDeductionModal" tabindex="-1" role="dialog" aria-labelledby="editDeductionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="editDeductionForm" method="POST" action="../logic/DeductionManagement/editDeductionLogic.php">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
      <input type="hidden" name="id" id="editDeductionId">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editDeductionModalLabel">Edit Deduction</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Deduction Name</label>
            <input type="text" name="deduction" id="editDeductionName" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Start Date</label>
            <input type="date" name="date_from" id="editDateFrom" class="form-control" required>
          </div>
          <div class="form-group">
            <label>End Date</label>
            <input type="date" name="date_to" id="editDateTo" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success" id="editDeductionBtn">Update Deduction</button>
        </div>
      </div>
    </form>
  </div>
</div>
