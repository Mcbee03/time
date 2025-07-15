    <?php
    session_start();
    include '../config/db.php';

    // Redirect to login if not authenticated
    if (!isset($_SESSION['admin_id'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('Location: /pages/login.php');
        exit;
    }

    $pageTitle = "Report Management";
    $activePage = "report";

    include '../includes/header.php';
    ?>

    <div class="container d-flex align-items-start justify-content-center" style="margin-top: 100px;">
        <div class="card card-primary card-outline elevation-2 p-3 mb-3">
            <div class="card-header" style="background: #2b7d62; color: #fff; border-radius: 12px 12px 0 0; border-bottom: none;">
                <h5 class="mb-0" style="font-weight: 700; letter-spacing: 1px;">Generate Report</h5>
            </div>
            <div class="card-body" style="background: #fff; border-radius: 0 0 12px 12px;">
                <form action="/logic/ReportManagement/generateReport.php" method="post" class="report-form" id="reportForm">
                    <!-- Report Selection -->
                    <div class="form-group">
                        <label style="font-weight:600;">Report</label>
                        <select class="form-control" name="reportType" id="reportType" required style="font-size: 1.0rem; height: 43px">
                            <option value="" selected disabled>Select Report</option>
                            <option value="monthly">Monthly Allowance Report</option>
                            <option value="dtr">DTR Report</option>
                        </select>
                    </div>

                    <!-- Date Range Row -->
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="dateFrom" style="font-weight:600;">Date From</label>
                            <input type="date" class="form-control date-input" id="dateFrom" name="dateFrom" required>
                        </div>
                        <div class="form-group col-6">
                            <label for="dateTo" style="font-weight:600;">Date To</label>
                            <input type="date" class="form-control date-input" id="dateTo" name="dateTo" required>
                        </div>
                    </div>

                    <!-- PB#/Member ID -->
                    <div class="form-group">
                        <label style="font-weight:600;">PB#/Member ID</label>
                        <input type="text" class="form-control" id="memberId" name="memberId" placeholder="Enter PB#/Member ID">
                    </div>

                    <!-- Format Selection -->
                    <div class="form-group">
                        <label style="font-weight:600;">Export Format</label>
                        <select class="form-control" name="exportFormat" required style="font-size: 1.0rem; height: 43px">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>

                    <!-- Generate Button -->
                    <button type="submit" class="btn btn-block" style="background: #2b7d62; color: #fff; font-weight:700; border-radius: 6px;">
                        <i class="fas fa-file-export"></i> Generate
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/reportManagement.js"></script>
   