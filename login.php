<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= bin2hex(random_bytes(32)) ?>">
    
    <title>NOVADECI Medical | Login</title>
    
    <!-- Favicon -->
    <link rel="icon" href="images/nova.png" type="image/png">
    
    <!-- Bootstrap 4.6 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/login.css">
</head>
<body class="login-page">
    <!-- Preloader -->
    <div class="preloader">
        <img src="images/nova.png" alt="Logo" class="preloader-logo">
    </div>
    
    <div class="container-login">
        <div class="login-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <div class="logo-container">
                        <img src="images/nova.png" alt="NOVADECI Logo" class="logo-img">
                    </div>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger mt-2 mb-0 font-weight-bold"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <h5><b>Login to Account</b></h5>
                    <h6 class="mb-4 text-muted">Enter your credentials to access your account.</h6>

                    <form id="loginForm" method="POST">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Username" id="username" name="username" required autofocus>
                        </div>

                        <div class="input-group mb-2">
                            <input type="password" class="form-control" placeholder="Password" id="password" name="password" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <i class="fas fa-eye-slash" id="togglePassword" style="cursor: pointer;"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <a href="#" class="text-secondary font-weight-bold">Forgot Password?</a>
                        </div>

                        <div class="form-group">
                            <button type="submit" name="signin" class="btn btn-success btn-block font-weight-bold">Sign In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    <script>
        // Password toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            }
            
            // Hide preloader when page loads
            window.addEventListener('load', function() {
                const preloader = document.querySelector('.preloader');
                if (preloader) {
                    preloader.style.opacity = '0';
                    setTimeout(() => {
                        preloader.style.display = 'none';
                    }, 500);
                }
            });
            
            // AJAX setup for CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
</body>
</html>