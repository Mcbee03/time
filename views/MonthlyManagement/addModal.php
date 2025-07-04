<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<div class="modal fade" id="addAllowanceModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius: 10px;">
      <div class="modal-header text-white" style="background-color: #2b7d62;">
        <h5 class="modal-title font-weight-bold w-100 text-center">Add Monthly Allowance</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form method="POST" action="../logic/MonthlyManagement/addMonthlyLogic.php" id="addAllowanceForm">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" id="form_date_from" name="date_from">
        <input type="hidden" id="form_date_to" name="date_to">
        
        <div class="modal-body p-4">
          <!-- Date Selection -->
          <div class="form-row mb-3 align-items-end">
            <div class="col-md-4">
              <label class="small font-weight-bold">Date From</label>
              <input type="date" id="modal_date_from" class="form-control form-control-sm" required>
            </div>
            <div class="col-md-4">
              <label class="small font-weight-bold">Date To</label>
              <input type="date" id="modal_date_to" class="form-control form-control-sm" required>
            </div>
            <div class="col-md-4 d-flex justify-content-end">
              <button type="button" id="generateBtn" class="btn btn-sm text-white" style="background-color: #2b7d62;">
                <i class="fas fa-plus-circle mr-1"></i> Generate
              </button>
            </div>
          </div>

          <!-- Allowance Table Section -->
          <div id="allowance-section" style="display: none;">
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
            
            <!-- Allowance Table -->
            <div class="table-responsive">
              <table class="table table-bordered table-sm" id="allowanceTable">
                <thead style="background-color: #2b7d62;" class="text-white text-center font-weight-bold">
                  <tr>
                    <th>Committee</th>
                    <th>Name</th>
                    <th>Member ID</th>
                    <th>Duty Hours</th>
                    <th>Rate</th>
                    <th>Transpo Allowance</th>
                    <th id="deductions-header" colspan="0">Less Deductions</th>
                    <th>Regular Savings</th>
                  </tr>
                </thead>
                <tbody id="allowanceTableBody">
                  <!-- Will be populated by JavaScript -->
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
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