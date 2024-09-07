<?php
include '../includes/db.php';

// Set the content-type to application/json
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM people WHERE id = ?");
    $stmt->bind_param("i", $id);  // "i" denotes the type as integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'No record found.']);
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid ID.']);
}

// Close the database connection
$conn->close();
?>
