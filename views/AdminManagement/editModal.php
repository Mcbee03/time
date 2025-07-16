<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!-- Edit Admin Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#2b7d62; color:#fff;">
        <h5 class="modal-title">Edit Admin</h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="editAdminForm" action="../logic/AdminManagement/editAdminLogic.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="id" id="edit_id">

        <div class="modal-body">
          <div class="form-group">
            <label>PB Number</label>
            <input type="text" name="pb_number" id="edit_pb_number" class="form-control">
          </div>

          <div class="form-group">
            <label>Member ID</label>
            <input type="text" name="member_id" id="edit_member_id" class="form-control">
          </div>

          <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" id="edit_username" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Password (Leave blank to keep current)</label>
            <div class="input-group">
              <input type="password" name="password" class="form-control">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary password-toggle" type="button">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn" style="background:#2b7d62; color:#fff;">
            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>