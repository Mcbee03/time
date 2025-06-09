<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DATE RATE TIME | <?= $pageTitle ?? 'Admin' ?></title>

    <?php include 'head.php'; ?>
    

    <!-- External Custom CSS -->
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

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
                <!-- UPDATED LOGOUT LINK -->
                <a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </li>
    </ul>
</nav>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="text-center p-4">
        <img src="/assets/images/logo.png" alt="NOVADECI Logo" class="img-fluid sidebar-logo">
    </div>
    <ul class="nav flex-column">
<<<<<<< HEAD:header.php
        <li class="nav-item"><a class="nav-link <?= $activePage === 'admin' ? 'active' : '' ?>" href="admin.php" data-title="Admin"><i class="fas fa-user-shield"></i> <span>Admin</span></a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'user' ? 'active' : '' ?>" href="user.php" data-title="User Management"><i class="fas fa-users"></i> <span>User Management</span></a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'deduction' ? 'active' : '' ?>" href="deduction.php" data-title="Setup"><i class="fas fa-calculator"></i> <span>Settings</span></a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'allowances' ? 'active' : '' ?>" href="monthly.php" data-title="Allowance Management"><i class="fas fa-user-shield"></i> <span>Monthly Allowance</span>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'report' ? 'active' : '' ?>" href="report.php" data-title="Report Management"><i class="fas fa-chart-bar"></i> <span>Report</span></a></li>
=======
<<<<<<< HEAD:header.php
        <li class="nav-item"><a class="nav-link <?= $activePage === 'admin' ? 'active' : '' ?>" href="admin.php" data-title="User Management"><i class="fas fa-users"></i> ADMIN</a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'user' ? 'active' : '' ?>" href="user.php" data-title="User Management"><i class="fas fa-users"></i> USER MANAGEMENT</a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'deduction' ? 'active' : '' ?>" href="deduction.php" data-title="Setup"><i class="fas fa-calculator"></i> SETUP</a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'monthly' ? 'active' : '' ?>" href="monthly.php" data-title="Monthly Allowance"><i class="fas fa-money-bill-wave"></i> MONTHLY ALLOWANCE</a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'report' ? 'active' : '' ?>" href="report.php" data-title="Reports"><i class="fas fa-chart-bar"></i> REPORT</a></li>
=======
        <li class="nav-item"><a class="nav-link <?= $activePage === 'admin' ? 'active' : '' ?>" href="adminManagement.php" data-title="Admin"><i class="fas fa-user-shield"></i> <span>Admin</span></a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'users' ? 'active' : '' ?>" href="userManagement.php" data-title="User Management"><i class="fas fa-users"></i> <span>User Management</span></a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'deduction' ? 'active' : '' ?>" href="deductionManagement.php" data-title="Setup"><i class="fas fa-calculator"></i> <span>Settings</span></a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'monthlyallowance' ? 'active' : '' ?>" href="monthlyManagement.php" data-title="Allowance Management"><i class="fas fa-money-bill-wave"></i> <span>Monthly Allowance</span>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'report' ? 'active' : '' ?>" href="reportManagement.php" data-title="Report Management"><i class="fas fa-chart-bar"></i> <span>Report</span></a></li>
>>>>>>> cd25a4b6fa627cfcbeefa6e0a77040910b47e8c3:includes/header.php
>>>>>>> b0ee5c6af78a9d84e63ed6f9d8e5a644a62c5a16:includes/header.php
    </ul>
</div>


<!-- Main Content -->
<<<<<<< HEAD:header.php
<div class="main-content">`
=======
<div class="main-content">
>>>>>>> b0ee5c6af78a9d84e63ed6f9d8e5a644a62c5a16:includes/header.php
