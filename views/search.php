<?php
include '../includes/db.php'; // Adjust the path as needed

$searchTerm = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Prepare the query with prioritization
$sql = "
    SELECT *
    FROM people
    WHERE name LIKE '%$searchTerm%'
    ORDER BY
        CASE
            WHEN name LIKE '$searchTerm%' THEN 1
            ELSE 2
        END,
        name
";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $serial_number = 1; // Assuming you want to display the serial number
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $serial_number . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['contact_number']) . "</td>";
        echo "<td>" . htmlspecialchars($row['state']) . "</td>";
        echo "<td>" . htmlspecialchars($row['city']) . "</td>";
        echo "<td>
                <div class='btn-group btn-group-sm' role='group'>
                    <button class='btn btn-warning edit-btn' data-id='" . htmlspecialchars($row['id']) . "'>Edit</button>
                    <button class='btn btn-danger delete-btn' data-id='" . htmlspecialchars($row['id']) . "'>Delete</button>
                </div>
              </td>";
        echo "</tr>";
        $serial_number++;
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>No records found</td></tr>";
}
?>
