<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<div class="modal fade" id="deleteDeductionModal" tabindex="-1" role="dialog" aria-labelledby="deleteDeductionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteDeductionModalLabel">Confirm Delete</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="deleteForm" method="POST" action="../logic/DeductionManagement/deleteDeductionLogic.php">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="id" id="deleteId">
        <div class="modal-body">
          <p>Are you sure you want to delete <strong><span id="deleteDeductionName"></span></strong>?</p>
          <p class="text-danger">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
