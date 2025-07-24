<?php 
include 'logic/Index/indexLogic.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="images/logo.png" type="image/png" />
    <title>OFFICERS | DTR</title>
    <?php include 'includes/head.php'; ?>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<div class="container-fluid dtr-container">
    <div class="login-box">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <img src="https://www.novadeci.com/wp-content/uploads/2017/03/nvdc-BANNER.png"
                     alt="Novaliches Development Cooperative Logo" class="img-fluid coop-logo">
            </div>
            <div class="card-body">
                <!-- Message Alerts -->
                <?php if ($message): ?>
                    <div class="alert alert-<?= strpos($message, 'error') !== false ? 'danger' : 'success' ?> alert-dismissible fade show alert-dtr">
                        <div class="d-flex align-items-center">
                            <i class="fas <?= strpos($message, 'error') !== false ? 'fa-exclamation-circle' : 'fa-check-circle' ?> mr-2"></i>
                            <div>
                                <strong><?= htmlspecialchars($message) ?></strong>
                                <?php if ($totalHours): ?>
                                    <div class="small"><?= htmlspecialchars($totalHours) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                
                <h4 class="dtr-title text-center">OFFICER DAILY TIME RECORD</h4>
                <hr class="divider">

                <!-- Real Time Clock -->
                <?php if (!$searchedMemberID): ?>
                    <div class="mb-4 text-center">
                        <span id="realtime-clock" class="realtime-clock">
                            <i class="far fa-clock mr-2"></i>
                            <span id="clock-text"></span>
                        </span>
                    </div>
                <?php endif; ?>
                
                <!-- Search Form -->
                <form method="POST" class="mb-3">
                    <div class="input-group input-group-lg shadow-sm">
                        <input type="text" name="member_id" class="form-control search-input" 
                               placeholder="Enter Member ID / PB#" required
                               value="<?= htmlspecialchars($searchedMemberID ?? '') ?>">
                        <div class="input-group-append">
                            <button class="btn btn-search text-white" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Profile Display -->
                <?php if ($searchedMemberID !== null && $userData): ?>
                    <div class="profile-display text-center mb-3">
                        <div class="profile-frame mx-auto">
                            <div class="profile-container">
                                <?php if (!empty($userData['Profile'])): ?>
                                    <img src="data:image/jpeg;base64,<?= base64_encode($userData['Profile']) ?>" 
                                        class="profile-img" 
                                        alt="Officer Profile"
                                        style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 3px solid #28a745;">
                                <?php else: ?>
                                    <img src="/assets/images/default.png" 
                                        class="profile-img" 
                                        alt="Default Profile"
                                        style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 3px solid #28a745;">
                                <?php endif; ?>
                            </div>
                        </div>
                    
                    </div>
                <?php endif; ?>


                
                <?php if ($searchedMemberID !== null && $userData): ?>
                    <!-- Time In/Out Button -->
                    <form method="POST" class="mb-3">
                        <input type="hidden" name="member_id" value="<?= htmlspecialchars($searchedMemberID) ?>">
                        <input type="hidden" name="action" value="<?= $timedInStatus ? 'time_out' : 'time_in' ?>">
                        <button class="btn btn-time <?= $timedInStatus ? 'btn-timeout' : 'btn-timein' ?>"
                            type="submit">
                            <i class="fas fa-clock mr-2"></i>
                            <?= $timedInStatus ? 'TIME OUT' : 'TIME IN' ?>
                        </button>
                    </form>
                    
                    <!-- Time Status Card -->
                    <div class="card status-card mt-3">
                        <div class="card-body py-3 px-4">
                            <h5 class="card-title status-title mb-1">
                                <i class="fas fa-user-circle mr-2"></i>
                                <?= htmlspecialchars($userData['Name']) ?> 
                                <span class="badge <?= $timedInStatus ? 'badge-success' : 'badge-secondary' ?> ml-2">
                                    <?= $timedInStatus ? 'TIMED IN' : 'NOT CLOCKED IN' ?>
                                </span>
                            </h5>
                            <p class="card-text status-id mb-1">
                                <i class="fas fa-id-card mr-2"></i>
                                <?= htmlspecialchars($userData['MemberID']) ?> / PB#<?= htmlspecialchars($userData['PBNum']) ?>
                            </p>
                            
                            <?php if ($timedInStatus): ?>
                                <?php
                                $stmt = $conn->prepare("SELECT TimeIN FROM tbl_dtr 
                                                        WHERE Users_Id = ? AND Date = ? 
                                                        AND TimeOUT IS NULL ORDER BY TimeIN DESC LIMIT 1");
                                $stmt->bind_param("is", $userData['Id'], $today);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $timeInRecord = $result->fetch_assoc();
                                $stmt->close();
                                ?>
                                <p class="card-text status-time mb-0 mt-2">
                                    <i class="far fa-calendar-alt mr-2"></i>
                                    <?= date("M j, Y, g:i:s A", strtotime($timeInRecord['TimeIN'])) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Focus trigger for Time In/Out button if member found -->
<?php if ($searchedMemberID !== null && $userData): ?>
<script>
    window.addEventListener('DOMContentLoaded', function () {
        const timeBtn = document.querySelector('.btn-time');
        if (timeBtn) {
            timeBtn.focus();
            timeBtn.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    timeBtn.click();
                }
            });
        }
    });
</script>
<?php endif; ?>

<script src="/assets/js/index.js"></script>
</body>
</html>