<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.html");
    exit();
}

include '../includes/db.php';

if (isset($_POST['car']) && isset($_POST['description']) && isset($_POST['date_time'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        header("Location: new_application.html?error=" . urlencode("Не удалось определить пользователя. Пожалуйста, войдите снова."));
        exit();
    }

    $car = $_POST['car'];
    $description = $_POST['description'];
    $date_time = $_POST['date_time'];

    $stmt = $conn->prepare("INSERT INTO applications (user_id, car, description, date_time, status) VALUES (?, ?, ?, ?, 'новое')");
    if ($stmt === false) {
        $error = $conn->error;
        header("Location: new_application.html?error=" . urlencode("Ошибка подготовки запроса: $error"));
        exit();
    }

    $stmt->bind_param("isss", $user_id, $car, $description, $date_time);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: index.php");
    } else {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        header("Location: new_application.html?error=" . urlencode("Ошибка при выполнении запроса: $error"));
    }
} else {
    header("Location: new_application.html?error=" . urlencode("Пожалуйста, заполните все поля"));
}
?>
