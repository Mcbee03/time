<?php
session_start();

$error = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signin'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Static credentials
    $valid_username = 'admin';
    $valid_password = 'password123';

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $username;
        header("Location: Admin.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>NOVADECI Medical | Login</title>

    <!-- Bootstrap 4.6 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" />
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

<div class="card shadow-sm rounded-0" style="width: 350px; border-top: 4px solid #27a745; border-left: none; border-right: none; border-bottom: none;">
        <div class="card-header bg-white text-center border-0">
            <img src="images/nova.png" alt="NOVADECI Logo" style="max-width: 280px;">
            <?php if ($error): ?>
                <div class="alert alert-danger mt-2 mb-0 font-weight-bold">
                    <?= htmlspecialchars($error) ?>
                </div>  
            <?php endif; ?>
        </div>
        <div class="card-body">
            <h5 class="text-center font-weight-bold mb-4">Sign into your account</h5>
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <input type="text" name="username" id="username" class="form-control" required autofocus>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <div class="input-group-append">
                            <span class="input-group-text toggle-password" style="cursor:pointer;"><i class="fas fa-eye-slash"></i></span>
                        </div>
                    </div>
                </div>

                <button type="submit" name="signin" class="btn btn-success btn-block font-weight-bold">Login</button>
            </form>
        </div>
    </div>

    <!-- jQuery + Bootstrap Bundle -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Password Toggle -->
    <script>
        $(document).ready(function () {
            $('#showPassword').on('change', function () {
                const passwordInput = $('#password');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
            });

            $('.toggle-password').on('click', function () {
                const passwordInput = $('#password');
                const icon = $(this).find('i');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                icon.toggleClass('fa-eye fa-eye-slash');
            });
        });
    </script>
</body>
</html>