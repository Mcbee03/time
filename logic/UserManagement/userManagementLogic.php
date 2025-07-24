<?php
include '../config/db.php';

$committees = [];
$stmt = $conn->prepare("SELECT ID, Committee FROM tbl_committee ORDER BY Committee ASC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $committees[] = $row;
}
$stmt->close();

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$perPage = 5;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $perPage;

$query = "
  SELECT u.Id, u.Profile, u.Name, u.MemberID, u.PBNum, c.Committee, u.Committee_ID
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

$params = [];
$types = '';

if (!empty($searchQuery)) {
    $searchTerm = "%$searchQuery%";
    $query .= " AND (u.Name LIKE ? OR u.MemberID LIKE ? OR u.PBNum LIKE ?)";
    $countQuery .= " AND (u.Name LIKE ? OR u.MemberID LIKE ? OR u.PBNum LIKE ?)";
    
    $types = 'sss';
    $params = [$searchTerm, $searchTerm, $searchTerm];
}

// Prepare count query
$stmt = $conn->prepare($countQuery);
if (!empty($searchQuery)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$totalRows = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Append order and limit directly to query string
$query .= " ORDER BY u.Id ASC LIMIT $perPage OFFSET $offset";

// Prepare main query
$stmt = $conn->prepare($query);
if (!empty($searchQuery)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$paginated = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$totalPages = max(1, ceil($totalRows / $perPage));
$startEntry = ($totalRows === 0) ? 0 : $offset + 1;
$endEntry = min($offset + $perPage, $totalRows);
