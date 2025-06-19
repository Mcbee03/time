<?php
// Sample printable receipt for report generation

// Get parameters
$dateFrom = $_GET['dateFrom'] ?? '';
$dateTo = $_GET['dateTo'] ?? '';
$memberId = $_GET['memberId'] ?? '';
$reportType = $_GET['reportType'] ?? '';

// Set headers for download as PDF/printable HTML
if (isset($_GET['print'])) {
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=report_receipt_" . date('Ymd_His') . ".html");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Printable Report Receipt</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 4.6 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --main-green: #2b7d62;
        }
        .bg-main-green {
            background-color: var(--main-green) !important;
        }
        .text-main-green {
            color: var(--main-green) !important;
        }
        .border-main-green {
            border-color: var(--main-green) !important;
        }
        .btn-main-green {
            background-color: var(--main-green) !important;
            color: #fff !important;
            font-weight: 700;
        }
        .btn-main-green:hover, .btn-main-green:focus {
            background-color: #256a54 !important;
            color: #fff !important;
        }
        @media print {
            .no-print { display: none !important; }
            .card {
                border: 4px solid var(--main-green) !important;
                box-shadow: none !important;
            }
            body { background: #fff !important; }
        }
    </style>
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6">
                <div class="card shadow border-0 mt-5">
                    <div class="card-header text-center"
                         style="background: #2b7d62; color: #fff; border-radius: .5rem .5rem 0 0;">
                        <img src="https://www.novadeci.com/wp-content/uploads/2017/03/nvdc-BANNER.png"
                             alt="Logo"
                             class="img-fluid mb-2"
                             style="max-height:70px;">
                    </div>
                    <div class="card-body bg-white">
                        <h5 class="mb-4 font-weight-bold text-center" style="color:#2b7d62; letter-spacing:1px;">Generate Report</h5>
                        <div class="form-row mb-3">
                            <div class="form-group col-6">
                                <label class="font-weight-bold mb-0 text-main-green">Date From</label>
                                <div><?= htmlspecialchars($dateFrom) ?></div>
                            </div>
                            <div class="form-group col-6">
                                <label class="font-weight-bold mb-0 text-main-green">Date To</label>
                                <div><?= htmlspecialchars($dateTo) ?></div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold mb-0 text-main-green">PB#/Member ID</label>
                            <div><?= htmlspecialchars($memberId) ?></div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold mb-0 text-main-green">Report</label>
                            <div><?= htmlspecialchars($reportType ? ucwords(str_replace('_', ' ', $reportType)) : '') ?></div>
                        </div>
                        <div class="alert text-center font-weight-bold mb-3 bg-main-green text-white" style="border-radius:8px;">
                            <i class="fas fa-check-circle"></i> Report successfully generated!
                        </div>
                        <div class="text-center text-muted mb-2" style="font-size:0.97rem;">
                            This is a system-generated receipt.<br>
                            <span style="font-size:0.9em;">Thank you!</span>
                        </div>
                        <div class="no-print text-center mt-3">
                            <button onclick="window.print()" class="btn btn-main-green font-weight-bold mr-2 mb-2">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <a href="?<?= http_build_query($_GET) ?>&print=1"
                               class="btn font-weight-bold mb-2"
                               style="background: linear-gradient(90deg, #ffe600 60%, #f5fbe0 100%); color: #2b7d62; border: none;"
                               onmousedown="this.style.background='#ffe600';this.style.color='#2b7d62';"
                               onmouseup="this.style.background='linear-gradient(90deg, #ffe600 60%, #f5fbe0 100%)';this.style.color='#2b7d62';">
                                <i class="fas fa-download"></i> Download Receipt
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap 4.6 JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
</body>
</html>