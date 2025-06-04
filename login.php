<?php
// login.php
$pageTitle = "Login";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> | NOVADECI</title>

    <!-- Bootstrap 4.6 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/login.logout.css">
</head>
<body class="bg-light">

    <!-- Logo at top center -->
<div class="text-center">
    <img src="nova.png" alt="NOVADECI Logo" class="login-logo">
</div>


    <div class="d-flex justify-content-center align-items-center">
        <div class="card shadow-sm" style="width: 24rem;">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <p class="text-muted font-weight-bold mb-0">Sign into your account</p>
                </div>

                <!-- Optional error message -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger font-weight-bold">
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <form action="login_process.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="showPassword">
                        <label class="form-check-label" for="showPassword">Show password</label>
                    </div>

                    <button type="submit" class="btn btn-success btn-block font-weight-bold">Login</button>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery + Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle password visibility
        $('#showPassword').on('change', function () {
            const passwordField = $('#password');
            passwordField.attr('type', this.checked ? 'text' : 'password');
        });
    </script>
</body>
</html>
