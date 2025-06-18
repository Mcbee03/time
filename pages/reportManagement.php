<?php
session_start();
include '../config/db.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['admin_id'])) {
    // Store current URL for redirect back after login
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: /pages/login.php');
    exit;
}

$pageTitle = "Report Management";
$activePage = "report";

<<<<<<< HEAD
session_start();
=======

>>>>>>> 95b3aff88a9c36e700340ea5563d2726737de462

if (file_exists('../includes/db_connection.php')) {
    include '../includes/db_connection.php';
    
    try {
        $checkStmt = $conn->query("SELECT COUNT(*) as count FROM tbl_report");
        if ($checkStmt) {
            $row = $checkStmt->fetch_assoc();
            if ($row['count'] > 0) {
                header("Location: report.php");
                exit();
            }
        }
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
    }
}

include '../includes/header.php';

if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert" style="max-width: 800px; margin: 20px auto;">
        <i class="fas fa-exclamation-circle mr-2"></i> ' . $_SESSION['error_message'] . '
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>';
    unset($_SESSION['error_message']);
}
?>

<!-- Adjusted Container with more margin-top -->
<div class="container d-flex align-items-start justify-content-center" style="margin-top: 100px;">
    <div class="card card-primary card-outline elevation-2 p-3 mb-3">
        <div class="card-header" style="background: #2b7d62; color: #fff; border-radius: 12px 12px 0 0; border-bottom: none;">
            <h5 class="mb-0" style="font-weight: 700; letter-spacing: 1px;">Generate Report</h5>
        </div>
        <div class="card-body" style="background: #fff; border-radius: 0 0 12px 12px;">
            <form action="report.php" method="post" class="report-form" id="reportForm">

                <!-- Report Selection -->
                <div class="form-group">
                    <label style="font-weight:600;">Report</label>
                    <select class="form-control" name="reportType" required>
                        <option value="" selected disabled>Select Report</option>
                        <option value="monthly">Monthly Report</option>
                        <option value="monthly">DTR Report</option>
                     
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
                    <input type="text" class="form-control" name="memberId" placeholder="Enter PB#/Member ID" required>
                </div>

                

                <!-- Generate Button -->
                <button type="submit" class="btn btn-block" style="background: #2b7d62; color: #fff; font-weight:700; border-radius: 6px;">
                    <i class="fas fa-file-export"></i> Generate
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- JavaScript for confirmation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('reportForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (confirm('Are you sure you want to generate the report?')) {
            form.submit();
        }
    });
});
</script>

<<<<<<< HEAD
<?php include '../includes/footer.php'; ?>
=======
>>>>>>> 95b3aff88a9c36e700340ea5563d2726737de462
