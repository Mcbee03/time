<?php if (empty($_SESSION['csrf_token'])) { 
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
} ?>

<div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header primary">
        <h5 class="modal-title">Add New User</h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="addUserForm" method="POST" action="../logic/UserManagement/addUserLogic.php" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

          <!-- Updated Image Upload Section to match reference -->
          <div class="row">
              <div class="col-12 d-flex justify-content-center mb-2">
                  <div class="img-upload-container">
                      <img id="addProfilePreview" src="../assets/images/uploadicon.png" alt="Picture" width="100" height="100">
                  </div>
              </div>

              <div class="col-12 mb-3">
                  <div class="input-group mt-2">
                      <div class="custom-file">
                          <input type="file" class="custom-file-input" id="addProfileUpload" name="profile" accept="image/jpeg, image/png, image/jpg">
                          <label class="custom-file-label" for="addProfileUpload">Upload Picture</label>
                      </div>
                  </div>
              </div>
          </div>

          <div class="form-group">
            <label>PB Number</label>
            <input type="text" name="pb_number" class="form-control">
          </div>
          <div class="form-group">
            <label>Member ID</label>
            <input type="text" name="member_id" class="form-control">
          </div>
          <div class="form-group">
            <label>Fullname</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Committee</label>
            <select name="committee_id" class="form-control" required style="font-size: 1.0rem; height: 43px">
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