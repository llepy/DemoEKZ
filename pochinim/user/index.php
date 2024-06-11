<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.html");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];

// Получаем информацию о пользователе
$stmt = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($full_name);
$stmt->fetch();
$stmt->close();

// Получаем заявки пользователя
$stmt = $conn->prepare("SELECT id, car, description, date_time, status FROM applications WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($id, $car, $description, $date_time, $status);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои заявки</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2 class="title">Добро пожаловать, <?php echo htmlspecialchars($full_name); ?>!</h2>
        <a href="new_application.html" class="button">Создать новую заявку</a>
        <h3>Мои заявки</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Автомобиль</th>
                    <th>Описание</th>
                    <th>Дата и время</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($stmt->fetch()) {
                    echo "<tr>
                            <td>$id</td>
                            <td>$car</td>
                            <td>$description</td>
                            <td>$date_time</td>
                            <td>$status</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
