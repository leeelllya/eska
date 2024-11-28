<?php

$host = 'localhost';
$db = 'bd1';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$sqlReport = "SELECT users.name AS user_name, 
                     COUNT(orders.id) AS order_count, 
                     SUM(orders.total) AS total_sales 
              FROM users 
              LEFT JOIN orders ON users.id = orders.user_id 
              GROUP BY users.id 
              ORDER BY total_sales DESC";

$stmtReport = $pdo->query($sqlReport);
$resultsReport = $stmtReport->fetchAll();

echo "<h1>Отчет о продажах</h1>";
echo "<table border='1'>
        <tr>
            <th>Пользователь</th>
            <th>Количество заказов</th>
            <th>Общая сумма продаж</th>
        </tr>";

foreach ($resultsReport as $row) {
    echo "<tr>
            <td>{$row['user_name']}</td>
            <td>{$row['order_count']}</td>
            <td>{$row['total_sales']}</td>
          </tr>";
}

echo "</table>";
?>