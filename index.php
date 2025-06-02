<?php
// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = htmlspecialchars($_POST['employee_id']);
    
    if (isset($_POST['search'])) {
        $message = "Member ID: <strong>$employee_id</strong> found";
        $show_time_button = true;
    } elseif (isset($_POST['time_in']) || isset($_POST['time_out'])) {
        $action = isset($_POST['time_in']) ? 'Time In' : 'Time Out';
        $time_now = date('Y-m-d h:i a');
        $message = "✅ $action recorded for <strong>$employee_id</strong> at <strong>$time_now</strong>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOVADEC DTR System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container dtr-container">
        <div class="text-center mb-4">
            <!-- NOVADEC Logo -->
            <img src="images/nova.png" alt="NOVADEC Logo" class="coop-logo">
            <div class="current-time"><?php echo date('Y-m-d h:i a'); ?></div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <?php if (isset($message)): ?>
                    <div class="alert alert-info" role="alert">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <form method="post" action="">
                    <div class="form-group mb-3">
                        <label for="employee_id" class="form-label">Member ID/ PDR</label>
                        <div class="input-group">
                            <input type="text" id="employee_id" name="employee_id" class="form-control" 
                                   placeholder="Enter member ID" required>
                            <button class="btn btn-search" type="submit" name="search">
                                Search
                            </button>
                        </div>
                    </div>
                    
                    <?php if (isset($show_time_button) || isset($_POST['time_in']) || isset($_POST['time_out'])): ?>
                        <div class="time-buttons">
                            <button type="submit" name="time_in" class="btn btn-time-in">
                                TIME IN
                            </button>
                            <button type="submit" name="time_out" class="btn btn-time-out">
                                TIME OUT
                            </button>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>