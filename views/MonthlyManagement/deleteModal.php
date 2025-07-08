<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<div class="modal fade" id="deleteMonthlyModal" tabindex="-1" role="dialog" aria-labelledby="deleteMonthlyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteMonthlyModalLabel">Confirm Delete</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this allowance record?</p>
        <p class="text-danger mb-0">This action cannot be undone and will delete all associated data.</p>
        <input type="hidden" id="deleteCsrfToken" value="<?= $_SESSION['csrf_token'] ?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
          <i class="fas fa-trash mr-1"></i> Delete
        </button>
      </div>
    </div>
  </div>
</div>