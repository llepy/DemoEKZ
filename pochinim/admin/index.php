<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../includes/db.php';

$auth_user = 'newfit';
$auth_pass = 'qsw123';

if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != $auth_user || $_SERVER['PHP_AUTH_PW'] != $auth_pass) {
    header('WWW-Authenticate: Basic realm="Admin Panel"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Требуется авторизация';
    exit();
}

// Подготовка SQL-запроса с JOIN для получения имени пользователя
$query = "SELECT applications.id, users.full_name, users.phone, applications.date_time, applications.car, applications.description, applications.status 
          FROM applications 
          JOIN users ON applications.user_id = users.id";

$stmt = $conn->prepare($query);

// Проверка успешности подготовки запроса
if ($stmt === false) {
    die("Ошибка подготовки запроса: " . $conn->error);
}

// Выполнение запроса
if (!$stmt->execute()) {
    die("Ошибка выполнения запроса: " . $stmt->error);
}

$stmt->store_result();
$stmt->bind_result($id, $full_name, $phone, $date_time, $car, $description, $status);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель администратора</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2 class="title">Все заявки</h2>
        <table>
            <thead>
                <tr>
                    <th>ФИО</th>
                    <th>Телефон</th>
                    <th>Дата и время</th>
                    <th>Автомобиль</th>
                    <th>Описание проблемы</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($stmt->fetch()) {
                    echo "<tr>
                            <td>$full_name</td>
                            <td>$phone</td>
                            <td>$date_time</td>
                            <td>$car</td>
                            <td>$description</td>
                            <td>$status</td>
                            <td>
                                <form action='update_status.php' method='post'>
                                    <input type='hidden' name='id' value='$id'>
                                    <select name='status'>
                                        <option value='подтверждено'>Подтвердить</option>
                                        <option value='отклонено'>Отклонить</option>
                                    </select>
                                    <button type='submit'>Обновить</button>
                                </form>
                            </td>
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
