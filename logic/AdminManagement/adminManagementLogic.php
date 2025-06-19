<?php
    
include '../config/db.php';


// Main logic for admin management page
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$perPage = 5;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $perPage;

// Base query
$query = "SELECT Id, Name, MemberID, PBNum FROM tbl_adminlogin WHERE 1=1";
$countQuery = "SELECT COUNT(*) as total FROM tbl_adminlogin WHERE 1=1";

// Add search conditions
if (!empty($searchQuery)) {
    $searchTerm = "%$searchQuery%";
    $query .= " AND (Name LIKE ? OR MemberID LIKE ? OR PBNum LIKE ?)";
    $countQuery .= " AND (Name LIKE ? OR MemberID LIKE ? OR PBNum LIKE ?)";
}

// Get total count
$stmt = $conn->prepare($countQuery);
if (!empty($searchQuery)) {
    $stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
}
$stmt->execute();
$totalRows = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Get paginated data
$query .= " LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);

if (!empty($searchQuery)) {
    $stmt->bind_param('sssii', $searchTerm, $searchTerm, $searchTerm, $perPage, $offset);
} else {
    $stmt->bind_param('ii', $perPage, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
$paginatedUsers = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$totalPages = max(1, ceil($totalRows / $perPage));
$startEntry = ($totalRows === 0) ? 0 : $offset + 1;
$endEntry = min($offset + $perPage, $totalRows);
?>