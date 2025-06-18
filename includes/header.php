<?php
    

// Only redirect if not on login page
if (!isset($_SESSION['admin_id']) && basename($_SERVER['PHP_SELF']) !== 'login.php') {
    header('Location: /pages/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DAILY TIME RECORD| <?= $pageTitle ?? 'Admin' ?></title>
    <?php include 'head.php'; ?>
    <link rel="stylesheet" href="/assets/css/nav.css">
</head>
<body>
<!-- Navbar -->

<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <button id="sidebarToggle" class="btn btn-link text-white mr-3"><i class="fas fa-bars"></i></button>
    <a class="navbar-brand text-white" href="/pages/adminManagement.php"></a>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
<<<<<<< HEAD
            <a class="nav-link dropdown-toggle text-dark d-flex align-items-center" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user-circle fa-lg"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
                <a class="dropdown-item text-danger" href="/pages/login.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
=======
            <a class="nav-link dropdown-toggle text-dark" href="#" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-circle fa-lg"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
               <a class="dropdown-item text-danger" href="/logic/Login/logoutLogic.php?nocache=<?= time() ?>">
                  <i class="fas fa-sign-out-alt"></i> Logout
               </a>
>>>>>>> 95b3aff88a9c36e700340ea5563d2726737de462
            </div>
        </li>
    </ul>
</nav>
?>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="text-center p-4">
        <img src="/assets/images/logo.png" alt="NOVADECI Logo" class="img-fluid sidebar-logo">
    </div>
    <ul class="nav flex-column">
<<<<<<< HEAD
        <li class="nav-item"><a class="nav-link <?= $activePage === 'admin' ? 'active' : '' ?>" href="adminManagement.php" data-title="Admin"><i class="fas fa-user-shield"></i> <span>Admin Management</span></a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'users' ? 'active' : '' ?>" href="userManagement.php" data-title="User Management"><i class="fas fa-users"></i> <span>User Management</span></a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'deduction' ? 'active' : '' ?>" href="deductionManagement.php" data-title="Setup"><i class="fas fa-calculator"></i> <span>Deduction Setup</span></a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'monthlyallowance' ? 'active' : '' ?>" href="monthlyManagement.php" data-title="Allowance Management"><i class="fas fa-user-shield"></i> <span>Monthly Allowance</span></a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'report' ? 'active' : '' ?>" href="reportManagement.php" data-title="Report Management"><i class="fas fa-chart-bar"></i> <span>Report</span></a></li>
=======
        <li class="nav-item"><a class="nav-link <?= $activePage === 'admin' ? 'active' : '' ?>" href="/pages/adminManagement.php" data-title="Admin"><i class="fas fa-user-shield"></i> <span>Admin</span></a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'users' ? 'active' : '' ?>" href="/pages/userManagement.php" data-title="User Management"><i class="fas fa-users"></i> <span>User Management</span></a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'deduction' ? 'active' : '' ?>" href="/pages/deductionManagement.php" data-title="Setup"><i class="fas fa-calculator"></i> <span>Settings</span></a></li>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'monthlyallowance' ? 'active' : '' ?>" href="/pages/monthlyManagement.php" data-title="Allowance Management"><i class="fas fa-user-shield"></i> <span>Monthly Allowance</span>
        <li class="nav-item"><a class="nav-link <?= $activePage === 'report' ? 'active' : '' ?>" href="/pages/reportManagement.php" data-title="Report Management"><i class="fas fa-chart-bar"></i> <span>Report</span></a></li>
>>>>>>> 95b3aff88a9c36e700340ea5563d2726737de462
    </ul>
</div>

<!-- Main Content -->

<div class="main-content">

<<<<<<< HEAD
<script src = "/assets/js/nav.js"></script>
=======
<script src = "/assets/js/nav.js"></script>
    
>>>>>>> 95b3aff88a9c36e700340ea5563d2726737de462
