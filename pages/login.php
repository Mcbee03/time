<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>NOVADECI Medical | Login</title>
    <!-- External Bootstrap, jQuery, etc -->
    <?php include '../includes/head.php'; ?>
    <!-- External Custom CSS -->
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body class="hold-transition login-page login-body">

<div class="container-login">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <img src="../assets/images/nova.png" alt="NOVADECI Logo" width="250">
                <div class="alert alert-danger mt-2 mb-0 error-text d-none font-weight-bold" role="alert">
                    text message error
                </div>
            </div>
            <div class="card-body">
                <h5 class="login-title"><b>Login to Account</b></h5>
                <h6 class="login-subtitle mb-4">Enter your credentials to access your account.</h6>
                
                <form method="POST" id="loginForm">
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" placeholder="Username" name="username" required autofocus>
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
                        <button type="submit" name="signin" class="btn btn-success btn-block font-weight-bold">Sign In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/login.js"></script>

</body>
</html>