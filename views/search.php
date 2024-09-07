<?php
include '../includes/db.php';

$searchTerm = $_GET['search'];

$sql = "SELECT * FROM people WHERE name LIKE ?";
$likeTerm = "%$searchTerm%";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $likeTerm);
$stmt->execute();
$result = $stmt->get_result();

$people = [];
while ($row = $result->fetch_assoc()) {
    $people[] = $row;
}

echo json_encode($people);
?>
