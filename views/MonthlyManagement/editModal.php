<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

 
<!-- Edit Allowance Modal -->
<div class="modal fade" id="editAllowanceModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius: 10px;">
      <div class="modal-header text-white" style="background-color: #2b7d62;">
        <h5 class="modal-title font-weight-bold w-100 text-center">Edit Monthly Allowance</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form method="POST" action="update_allowance.php" id="editAllowanceForm">
        <div class="modal-body p-4">

          <!-- Hidden ID -->
          <input type="hidden" name="allowance_id" value="">

          <!-- Search Box -->
          <div class="form-group mb-3">
           <div class="input-group" style="max-width: 300px;">
             <input type="text" class="form-control form-control-sm" placeholder="Search name..." id="memberSearch">
               <div class="input-group-append">
               <span class="input-group-text" style="background-color: #2b7d62; color: white;">
                  <i class="fas fa-search"></i>
                    </span>
                     </div>
                    </div>
                       </div>

          <!-- Date Range -->
          <div class="form-row mb-3">
            <div class="col-md-6">
              <label class="small font-weight-bold">Date From</label>
              <input type="date" name="date_from" class="form-control form-control-sm" value="2025-06-01" readonly style="background-color:#e9ecef;">
            </div>
            <div class="col-md-6">
              <label class="small font-weight-bold">Date To</label>
              <input type="date" name="date_to" class="form-control form-control-sm" value="2025-06-30" readonly style="background-color:#e9ecef;">
            </div>
          </div>

          <!-- Table -->
          <div class="table-responsive">
            <table class="table table-bordered table-sm">
              <thead style="background-color: #2b7d62;" class="text-center">
                <tr>
                  <th style="color: white; font-weight:700;">Committee</th>
                  <th style="color: white; font-weight:700;">Name</th>
                  <th style="color: white; font-weight:700;">Member ID</th>
                  <th style="color: white; font-weight:700;">Duty Hours</th>
                  <th style="color: white; font-weight:700;">Rate</th>
                  <th style="color: white; font-weight:700;">Transpo Allowance</th>
                  <th style="color: white; font-weight:700;" colspan="3">Less Deductions</th>
                  <th style="color: white; font-weight:700;">Regular Savings</th>
                </tr>
                <tr>
                  <th colspan="6"></th>
                  <th style="color: white; font-weight:700;">RCBC</th>
                  <th style="color: white; font-weight:700;">NORF</th>
                  <th style="color: white; font-weight:700;">Rice</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><input type="text" name="committee[]" class="form-control form-control-sm" value="Commit" readonly style="background-color:#e9ecef;"></td>
                  <td><input type="text" name="name[]" class="form-control form-control-sm" value="John Doe" readonly style="background-color:#e9ecef;"></td>
                  <td><input type="text" name="member_id[]" class="form-control form-control-sm" value="M001" readonly style="background-color:#e9ecef;"></td>
                  <td><input type="number" name="duty_hours[]" class="form-control form-control-sm" value="8" required></td>
                  <td><input type="number" name="rate[]" class="form-control form-control-sm" value="100" required></td>
                  <td><input type="number" name="transpo_allowance[]" class="form-control form-control-sm" value="100" required></td>
                  <td><input type="number" name="rcbc[]" class="form-control form-control-sm" value="10"></td>
                  <td><input type="number" name="norf[]" class="form-control form-control-sm" value="5"></td>
                  <td><input type="number" name="rice[]" class="form-control form-control-sm" value="20"></td>
                  <td><input type="number" name="savings[]" class="form-control form-control-sm" value="50"></td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>

          <div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">
    <i class="fas fa-times-circle mr-1"></i> Cancel
</button>
<button type="submit" class="btn" style="background-color: #2b7d62; color: white;">
    <i class="fas fa-save mr-1"></i> Save
</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>