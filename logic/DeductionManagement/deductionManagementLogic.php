<?php
include '../config/db.php';

// Authentication check
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../pages/login.php');
    exit;
}

// Initialize CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Pagination and search setup
$searchQuery = $_GET['search'] ?? '';
$perPage = 5;
$currentPage = max(1, (int)($_GET['page'] ?? 1));
$offset = ($currentPage - 1) * $perPage;

// Base query with ascending order (newest at bottom)
$query = "SELECT Id, DeductionType AS deduction_name, 
          DATE_FORMAT(DateFrom, '%Y-%m-%d') AS start_date, 
          DATE_FORMAT(DateTo, '%Y-%m-%d') AS end_date 
          FROM tbl_deduction WHERE 1=1";

// Add search conditions if needed
if (!empty($searchQuery)) {
    $searchTerm = "%$searchQuery%";
    $query .= " AND (DeductionType LIKE ? OR DateFrom LIKE ? OR DateTo LIKE ?)";
}

// Complete query with sorting and pagination
$query .= " ORDER BY Id ASC LIMIT ? OFFSET ?";

// Prepare and execute
$stmt = $conn->prepare($query);
if (!empty($searchQuery)) {
    $stmt->bind_param('sssii', $searchTerm, $searchTerm, $searchTerm, $perPage, $offset);
} else {
    $stmt->bind_param('ii', $perPage, $offset);
}

$stmt->execute();
$paginatedDeductions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get total count for pagination
$countQuery = "SELECT COUNT(*) as total FROM tbl_deduction";
$totalRows = $conn->query($countQuery)->fetch_assoc()['total'];
$totalPages = max(1, ceil($totalRows / $perPage));
?>