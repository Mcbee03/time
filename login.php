<?php
session_start();

$error = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signin'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Static authentication (hardcoded credentials)
    $valid_username = 'admin';
    $valid_password = 'password123';
    
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $username;
        header("Location: admin.php");
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

    <!-- Bootstrap 4.6 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" />
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/login.logout.css" />
</head>
<body class="login-page">

    <div class="container-login">
        <div class="login-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center bg-white">
                    <img src="images/nova.png" alt="NOVADECI Logo" class="logo-img" />
                    <?php if ($error): ?>
                        <div class="alert alert-danger mt-2 mb-0 font-weight-bold">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <h5><b>Sign into your Account</b></h5>
                    <form method="POST">
                        <div class="input-group mb-3">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Username"
                                name="username"
                                required
                                autofocus
                            />
                        </div>

                        <div class="input-group mb-3">
                            <input
                                type="password"
                                class="form-control"
                                placeholder="Password"
                                name="password"
                                required
                            />
                            <div class="input-group-append">
                                <div class="input-group-text" style="cursor:pointer;">
                                    <i class="fas fa-eye-slash toggle-password"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button
                                type="submit"
                                name="signin"
                                class="btn btn-success btn-block font-weight-bold"
                            >
                                Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

    <script>
        // Toggle password visibility
        $(document).ready(function() {
            $('.toggle-password').click(function() {
                const passwordInput = $(this).parent().parent().prev('input');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });
        });
    </script>
</body>
</html>