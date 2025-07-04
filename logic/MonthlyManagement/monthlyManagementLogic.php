<?php
include '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: /pages/login.php');
    exit;
}

$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Build the base query
$query = "SELECT ma.id, ma.DateFrom as date_from, ma.DateTo as date_to 
          FROM tbl_monthly_allowance ma";

$where = [];
$params = [];
$types = '';

if ($date_from) {
    $where[] = "ma.DateFrom >= ?";
    $params[] = $date_from;
    $types .= 's';
}

if ($date_to) {
    $where[] = "ma.DateTo <= ?";
    $params[] = $date_to;
    $types .= 's';
}

if (!empty($where)) {
    $query .= " WHERE " . implode(" AND ", $where);
}

$query .= " ORDER BY ma.DateFrom DESC";

// Prepare and execute the query
$stmt = $conn->prepare($query);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$allowances = [];
while ($row = $result->fetch_assoc()) {
    $allowances[] = $row;
}

// Pagination logic
$perPage = 5;
$totalItems = count($allowances);
$totalPages = max(1, ceil($totalItems / $perPage));
$page = isset($_GET['page']) ? max(1, min((int)$_GET['page'], $totalPages)) : 1;
$offset = ($page - 1) * $perPage;
$paginated = array_slice($allowances, $offset, $perPage);
?>