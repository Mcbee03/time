<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header primary">
        <h5 class="modal-title">Add New User</h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="addUserForm" method="POST" action="../logic/UserManagement/addUserLogic.php">
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
            <label>Committee</label>
            <select name="committee_id" class="form-control" required>
              <option value="" disabled selected>~ Select Committee ~</option>
              <?php foreach ($committees as $committee): ?>
                <option value="<?= $committee['ID'] ?>"><?= htmlspecialchars($committee['Committee']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn primary">
            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            Submit  
          </button>
        </div>
      </form>
    </div>
  </div>
</div>