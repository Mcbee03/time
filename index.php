<?php
session_start();
date_default_timezone_set('Asia/Manila');
$today = date('Y-m-d');

if (!isset($_SESSION['time_log'])) {
    $_SESSION['time_log'] = [];
}

$searchedMemberID = null;
$timedInStatus = false;
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$messageType = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'success';
$totalHours = isset($_SESSION['total_hours']) ? $_SESSION['total_hours'] : '';
unset($_SESSION['message']);
unset($_SESSION['message_type']);
unset($_SESSION['total_hours']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['member_id'])) {
    $memberID = trim($_POST['member_id']);
    if ($memberID !== '') {
        $searchedMemberID = $memberID;

        if (isset($_POST['action'])) {
            if ($_POST['action'] === 'time_in') {
                $_SESSION['time_log'][$today][$memberID] = [
                    'timed_in' => true,
                    'timed_out' => false,
                    'time_in_time' => date('H:i:s'),
                    'time_out_time' => null,
                ];
                $_SESSION['message'] = "Member $memberID timed in at " . date("M j, Y, g:i:s A", strtotime("$today " . $_SESSION['time_log'][$today][$memberID]['time_in_time']));
                $_SESSION['message_type'] = 'success';
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } elseif ($_POST['action'] === 'time_out' && isset($_SESSION['time_log'][$today][$memberID])) {
                $_SESSION['time_log'][$today][$memberID]['timed_out'] = true;
                $_SESSION['time_log'][$today][$memberID]['time_out_time'] = date('H:i:s');
                
                $timeIn = new DateTime($_SESSION['time_log'][$today][$memberID]['time_in_time']);
                $timeOut = new DateTime($_SESSION['time_log'][$today][$memberID]['time_out_time']);
                $interval = $timeIn->diff($timeOut);
                $hours = $interval->h;
                $minutes = $interval->i;
                $seconds = $interval->s;
                
                if ($hours > 0 || $minutes > 0 || $seconds > 1) {
                    $totalHoursWorked = "$hours hours, $minutes minutes, and $seconds seconds";
                    $_SESSION['total_hours'] = "Total time worked: $totalHoursWorked";
                }
                
                $_SESSION['message'] = "Member $memberID timed out at " . date("M j, Y, g:i:s A", strtotime("$today " . $_SESSION['time_log'][$today][$memberID]['time_out_time']));
                $_SESSION['message_type'] = 'danger';
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }
        } else {
            $timedInStatus = isset($_SESSION['time_log'][$today][$memberID]) &&
                $_SESSION['time_log'][$today][$memberID]['timed_in'] &&
                !$_SESSION['time_log'][$today][$memberID]['timed_out'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="images/logo.png" type="image/png" />
    <title>OFFICERS | DTR</title>
    
    <!-- Bootstrap 4.6 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .fade-out {
            opacity: 0;
            transition: opacity 0.5s ease-out;
        }
    </style>
</head>
<body>

<div class="container-fluid" style="background: #f6f8fa; min-height: 100vh;">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-12 d-flex justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="search-box text-center shadow p-4 rounded" style="background: #fff;">
                <a class="navbar-brand d-flex align-items-center justify-content-center mb-3" href="#">
                    <img src="https://www.novadeci.com/wp-content/uploads/2017/03/nvdc-BANNER.png"
                         alt="Logo" class="img-fluid logo-img" style="max-height: 70px;">
                </a>

                <?php if ($message): ?>
                    <div class="alert alert-<?= $messageType ?>" id="timed-message"><?= htmlspecialchars($message) ?></div>
                    <?php if ($totalHours && !preg_match('/^Total time worked: 0 hours, 0 minutes, and \d+ seconds$/', $totalHours)): ?>
                        <div class="alert alert-info" id="total-hours-message"><?= htmlspecialchars($totalHours) ?></div>
                    <?php endif; ?>
                <?php endif; ?>

                <form method="POST" class="mb-4">
                    <div class="input-group input-group-lg justify-content-center">
                        <input type="text" name="member_id" class="form-control search-input rounded-left text-center" placeholder="Enter Member ID / PB#" required
                               value="<?= htmlspecialchars($searchedMemberID ?? '') ?>">
                        <div class="input-group-append">
                            <button class="btn text-white rounded-right" type="submit" style="background-color: #3DB272;">
                                <i class="fas fa-search mr-1"></i> Search
                            </button>
                        </div>
                    </div>
                </form>

                <?php if ($searchedMemberID !== null): ?>
                    <form method="POST" class="mb-4">
                        <input type="hidden" name="member_id" value="<?= htmlspecialchars($searchedMemberID) ?>">
                        <input type="hidden" name="action" value="<?= $timedInStatus ? 'time_out' : 'time_in' ?>">
                        <button class="btn btn-block text-white py-2 btn-top-border"
                            type="submit"
                            style="background-color: <?= $timedInStatus ? '#dc3545' : '#3DB272' ?>; font-size: 1.2rem; font-weight: 600; border-radius: 8px;">
                            <i class="fas fa-clock mr-1"></i>
                            <?= $timedInStatus ? 'Time Out' : 'Time In' ?>
                        </button>
                    </form>

                    <div class="mb-3">
                        <h5 id="realtime-clock" class="font-weight-bold text-dark"></h5>
                    </div>

                    <!-- Removed the entire DTR table display -->
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- jQuery, Popper.js, Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

<script src="../assets/js/index.js"></script>


</body>
</html>