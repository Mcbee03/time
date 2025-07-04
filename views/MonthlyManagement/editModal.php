<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!-- 🔧 Edit Allowance Modal -->
<div class="modal fade" id="editAllowanceModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius: 10px;">
      <div class="modal-header text-white" style="background-color: #2b7d62;">
        <h5 class="modal-title font-weight-bold w-100 text-center">Edit Monthly Allowance</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <form method="POST" action="../logic/MonthlyManagement/editMonthlyLogic.php" id="editAllowanceForm">
        <div class="modal-body p-4">
          <!-- CSRF + Hidden ID -->
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <input type="hidden" name="allowance_id" id="editAllowanceId">

          <!-- 🔍 Search Box -->
          <div class="form-group mb-3">
            <div class="input-group" style="max-width: 300px;">
              <input type="text" class="form-control form-control-sm" placeholder="Search name..." id="editMemberSearch">
              <div class="input-group-append">
                <span class="input-group-text" style="background-color: #2b7d62; color: white;">
                  <i class="fas fa-search"></i>
                </span>
              </div>
            </div>
          </div>

          <!-- 📅 Date Range -->
          <div class="form-row mb-3">
            <div class="col-md-6">
              <label class="small font-weight-bold">Date From</label>
              <input type="date" name="date_from" id="editDateFrom" class="form-control form-control-sm" readonly style="background-color:#e9ecef;">
            </div>
            <div class="col-md-6">
              <label class="small font-weight-bold">Date To</label>
              <input type="date" name="date_to" id="editDateTo" class="form-control form-control-sm" readonly style="background-color:#e9ecef;">
            </div>
          </div>

          <!-- 📋 Editable Table -->
          <div class="table-responsive">
            <table class="table table-bordered table-sm" id="editAllowanceTable">
              <thead style="background-color: #2b7d62;" class="text-center text-white">
                <tr>
                  <th>Committee</th>
                  <th>Name</th>
                  <th>Member ID</th>
                  <th>Duty Hours</th>
                  <th>Rate</th>
                  <th>Transpo Allowance</th>
                  <th id="editDeductionsHeader" colspan="0">Less Deductions</th>
                  <th>Regular Savings</th>
                </tr>
              </thead>
              <tbody id="editAllowanceTableBody">
                <!-- Rows populated by JS (fetchAllowanceData) -->
              </tbody>
            </table>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times-circle mr-1"></i> Cancel
          </button>
          <button type="submit" class="btn" style="background-color: #2b7d62; color: white;">
            <i class="fas fa-save mr-1"></i> Save
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
      