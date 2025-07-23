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
      <form id="editUserForm" method="POST" action="../logic/UserManagement/editUserLogic.php" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="id" id="editUserId">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

          <div class="row">
              <div class="col-12 d-flex justify-content-center mb-2">
                  <div class="img-upload-container">
                      <img id="editProfilePreview" src="../assets/images/uploadicon.png" alt="Profile Picture" width="100" height="100">
                  </div>
              </div>

              <div class="col-12 mb-3">
                  <div class="input-group mt-2">
                      <div class="custom-file">
                          <input type="file" class="custom-file-input" id="editProfileUpload" name="profile" accept="image/jpeg, image/png, image/jpg">
                          <label class="custom-file-label" for="editProfileUpload">Upload Picture</label>
                      </div>
                  </div>
              </div>
          </div>

          <div class="form-group">
            <label>PB Number</label>
            <input type="text" name="pb_number" id="editPBNumber" class="form-control">
          </div>
          <div class="form-group">
            <label>Member ID</label>
            <input type="text" name="member_id" id="editMemberID" class="form-control">
          </div>
          <div class="form-group">
            <label>Fullname</label>
            <input type="text" name="name" id="editName" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Committee</label>
            <select name="committee_id" id="editCommittee" class="form-control" required style="font-size: 1.0rem; height: 43px">
              <option value="" selected disabled>~ Select Committee ~</option>
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