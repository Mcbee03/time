<?php
include '../config/db.php';

// 🟢 Fetch committees for dropdown
$committees = [];
$stmt = $conn->prepare("SELECT ID, Committee FROM tbl_committee ORDER BY Committee ASC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $committees[] = $row;
}
$stmt->close();

// 🔍 Search, Pagination Logic
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$perPage = 5;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $perPage;

$query = "
  SELECT u.Id, u.Name, u.MemberID, u.PBNum, c.Committee 
  FROM tbl_users u
  LEFT JOIN tbl_committee c ON u.Committee_ID = c.ID
  WHERE 1=1
";
$countQuery = "
  SELECT COUNT(*) as total 
  FROM tbl_users u
  LEFT JOIN tbl_committee c ON u.Committee_ID = c.ID
  WHERE 1=1
";

if (!empty($searchQuery)) {
    $searchTerm = "%$searchQuery%";
    $query .= " AND (u.Name LIKE ? OR u.MemberID LIKE ? OR u.PBNum LIKE ?)";
    $countQuery .= " AND (u.Name LIKE ? OR u.MemberID LIKE ? OR u.PBNum LIKE ?)";
}

$stmt = $conn->prepare($countQuery);
if (!empty($searchQuery)) {
    $stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
}
$stmt->execute();
$totalRows = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// 🔄 ORDER BY ASC to display new entries at the bottom
$query .= " ORDER BY u.Id ASC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);

if (!empty($searchQuery)) {
    $stmt->bind_param('sssii', $searchTerm, $searchTerm, $searchTerm, $perPage, $offset);
} else {
    $stmt->bind_param('ii', $perPage, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
$paginated = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$totalPages = max(1, ceil($totalRows / $perPage));
$startEntry = ($totalRows === 0) ? 0 : $offset + 1;
$endEntry = min($offset + $perPage, $totalRows);
