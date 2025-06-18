<?php
session_start();
include '../config/db.php';

// Check if user is already logged in
if (isset($_SESSION['admin_id'])) {
    // Check kung saan dapat i-redirect
    if (isset($_SESSION['redirect_url'])) {
        $redirect = $_SESSION['redirect_url'];
        unset($_SESSION['redirect_url']);
        header('Location: '.$redirect);
    } else {
        // Default redirect kung walang stored URL
        header('Location: /pages/adminManagement.php');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>DATE RATE TIME | Login</title>
    <?php include '../includes/head.php'; ?>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body class="hold-transition login-page login-body">

<div class="container-login">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <img src="../assets/images/nova.png" alt="NOVADECI Logo" width="250">
                <div class="alert alert-danger mt-2 mb-0 error-text d-none font-weight-bold" role="alert"></div>
            </div>
            <div class="card-body">
                <h5 class="login-title"><b>Sign in to Account</b></h5>
                <h6 class="login-subtitle mb-4">Enter your credentials to access your account.</h6>
                
                <form method="POST" id="loginForm">
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" placeholder="Username" name="username" id="username" required autofocus>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fas fa-eye-slash toggle-password" style="cursor: pointer;"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" name="signin" class="btn btn-success btn-block font-weight-bold">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <span class="submit-text">Sign In</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/login.js"></script>
</body>
</html>