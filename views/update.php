<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email_id = $_POST['email_id'];
    $contact_number = $_POST['contact_number'];
    $state = $_POST['state'];
    $city = $_POST['city'];

    $sql = "UPDATE people SET name = ?, email_id = ?, contact_number = ?, state = ?, city = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $name, $email_id, $contact_number, $state, $city, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
    $stmt->close();
}
?>
