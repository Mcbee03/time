<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<div class="modal fade" id="editMemberModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header primary">
        <h5 class="modal-title">Edit User</h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="editUserForm" action="../logic/UserManagement/editUserLogic.php" method="POST">
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
            <label>Fullname</label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Committee</label>
            <select name="committee_id" id="edit_committee_id" class="form-control" required style="font-size: 1.0rem; height: 43px">
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
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>