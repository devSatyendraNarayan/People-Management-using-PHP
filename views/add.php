<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email_id = $_POST['email_id'];
    $contact_number = $_POST['contact_number'];
    $state = $_POST['state'];
    $city = $_POST['city'];

    $sql = "INSERT INTO people (name, email_id, contact_number, state, city) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email_id, $contact_number, $state, $city);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
    $stmt->close();
}
?>
