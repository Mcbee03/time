<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DATE RATE TIME. | <?= $pageTitle ?? 'Admin' ?></title>

    <!-- Bootstrap & Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        
    <!-- External Custom CSS -->
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <button id="sidebarToggle" class="btn btn-link text-white mr-3"><i class="fas fa-bars"></i></button>
    <a class="navbar-brand text-white" href="#">DTR </a>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-dark" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user-circle fa-lg"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
                <a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </li>
    </ul>
</nav>  

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="text-center p-3">
        <img src="images/logo.png" alt="NOVADECI Logo" class="img-fluid sidebar-logo">
    </div>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link <?= $activePage === 'admin' ? 'active' : '' ?>" href="admin.php" data-title="User Management"><i class="fas fa-users"></i> USER MANAGEMENT</a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'deduction' ? 'active' : '' ?>" href="deduction.php" data-title="Setup"><i class="fas fa-calculator"></i> SETUP</a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'monthly' ? 'active' : '' ?>" href="monthly.php" data-title="Monthly Allowance"><i class="fas fa-money-bill-wave"></i> MONTHLY ALLOWANCE</a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'report' ? 'active' : '' ?>" href="report.php" data-title="Reports"><i class="fas fa-chart-bar"></i> REPORT</a></li>
    </ul>
</div>
<!-- Main Content -->
<div class="main-content">
