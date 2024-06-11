<?php
include '../includes/db.php';

if (isset($_POST['full_name']) && isset($_POST['phone']) && isset($_POST['username']) && isset($_POST['password'])) {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (full_name, phone, username, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $phone, $username, $password);
    $stmt->execute();
    $stmt->close();

    header("Location: login.html");
}
?>
