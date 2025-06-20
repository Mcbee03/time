<?php
// ✅ CSRF Token (make sure session_start() was called in the parent file)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<div class="modal fade" id="deleteMemberModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <form id="deleteUserForm" action="../logic/UserManagement/deleteUserLogic.php" method="POST">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <!-- User ID -->
        <input type="hidden" name="id" id="delete_id">
        
        <div class="modal-body">
          <p>Are you sure you want to delete this user?</p>
          <p class="font-weight-bold text-success" id="userToDelete"></p>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">
            <span class="spinner-border spinner-border-sm d-none mr-1" role="status"></span>
            Delete
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
