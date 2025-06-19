<?php

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Add New Admin</h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="addAdminForm" action="../../logic/AdminManagement/addAdminLogic.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          
          <div class="form-group">
            <label>PB Number</label>
            <input type="text" name="pb_number" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label>Member ID</label>
            <input type="text" name="member_id" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label>Password</label>
            <div class="input-group">
              <input type="password" name="password" class="form-control" required>
              <div class="input-group-append">
                <button class="btn btn-outline-secondary password-toggle" type="button">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <label>Confirm Password</label>
            <div class="input-group">
              <input type="password" name="confirm_password" class="form-control" required>
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
          <button type="submit" class="btn btn-success">
            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            Submit  
          </button>
        </div>
      </form>
    </div>
  </div>
</div>