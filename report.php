<?php
$pageTitle = "Report Generation";
$activePage = "report";

include 'header.php';

if (file_exists('db_connectio.php')) {
    include 'db_connection.php';
}
?>

<div class="main-content-container">
    <div class="card card-custom">
        <div class="card-body">
            <form action="committee_management.php" method="post" class="report-form">

               <!-- Date Range Row -->
<div class="form-row">
        <div class="form-group">
            <label for="dateFrom">Date From</label>
            <input type="date" class="form-control date-input" id="dateFrom" name="dateFrom" required>
        </div>
        <div class="form-group">
            <label for="dateTo">Date To</label>
            <input type="date" class="form-control date-input" id="dateTo" name="dateTo" required>
        </div>
    </div>

    <!-- PB#/Member ID -->
    <div class="form-group">
        <label>PB#/Member ID</label>
        <input type="text" class="form-control" name="memberId" placeholder="Enter PB#/Member ID" required>
    </div>

    <!-- Report Selection -->
    <div class="form-group">
        <label>Report</label>
        <select class="form-control" name="reportType" required>
            <option value="" selected disabled>Select Report</option>
            <option value="monthly">Monthly Summary</option>
            <option value="quarterly">Quarterly Analysis</option>
            <option value="annual">Annual Report</option>
            <option value="custom">Custom Report</option>
        </select>
    </div>

    <!-- Generate Button -->
    <button type="submit" class="generate-btn">
        <i class="fas fa-file-export"></i> Generate
    </button>
</form>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="assets/css/report.css">

<?php include 'footer.php'; ?>