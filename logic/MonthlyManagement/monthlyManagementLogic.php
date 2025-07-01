<?php
include '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: /pages/login.php');
    exit;
}

$date_from = $_GET['date_from'] ?? '';
$date_to   = $_GET['date_to'] ?? '';

// Sample static data for now
$allowances = [ /* same 8 sample data here... */ ];

// Filter logic
$filtered = array_filter($allowances, function($row) use ($date_from, $date_to) {
    if ($date_from && $date_to) {
        return $row['date_from'] >= $date_from && $row['date_to'] <= $date_to;
    } elseif ($date_from) {
        return $row['date_from'] >= $date_from;
    } elseif ($date_to) {
        return $row['date_to'] <= $date_to;
    }
    return true;
});

// Pagination logic
$perPage     = 5;
$totalItems  = count($filtered);
$totalPages  = max(1, ceil($totalItems / $perPage));
$page        = isset($_GET['page']) ? max(1, min((int)$_GET['page'], $totalPages)) : 1;
$offset      = ($page - 1) * $perPage;
$paginated   = array_slice($filtered, $offset, $perPage);
