<?php
session_start();

if (!isset($_SESSION['register_data'])) {
    header("Location: register.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm'])) {
    $input_code = $_POST['confirmation_code'];

    // Подключение к базе данных
    $servername = "localhost";
    $username = "root";
    $password = "root"; // Укажите ваш пароль
    $dbname = "birzha_truda";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Проверка кода подтверждения
    $email = $_SESSION['register_data']['email'];
    $sql = "SELECT * FROM users WHERE email='$email' AND confirmation_code='$input_code'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Код подтвержден, обновляем статус
        $sql = "UPDATE users SET is_confirmed = 1 WHERE email='$email'";
        $conn->query($sql);

        // Удаление данных из сессии
        unset($_SESSION['register_data']);
        header("Location: dashboard.php"); // Перенаправление на dashboard
        exit();
    } else {
        echo "Неверный код подтверждения.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение регистрации</title>
    <style>
        body { font-family: Arial, sans-serif; }
        form { max-width: 300px; margin: auto; }
        input { display: block; margin: 10px 0; width: 100%; padding: 10px; }
    </style>
</head>
<body>
<h2>Подтверждение регистрации</h2>
<form action="confirm.php" method="POST">
    <input type="text" name="confirmation_code" placeholder="Введите код подтверждения" required>
    <button type="submit" name="confirm">Подтвердить</button>
</form>
</body>
</html>