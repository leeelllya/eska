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

$sqlInner = "SELECT users.name, orders.total 
             FROM users 
             INNER JOIN orders ON users.id = orders.user_id";
$stmtInner = $pdo->query($sqlInner);
$resultsInner = $stmtInner->fetchAll();

echo "INNER JOIN Results:<br>";
foreach ($resultsInner as $row) {
    echo "User: {$row['name']}, Total Order: {$row['total']}<br>";
}

$sqlLeft = "SELECT users.name, orders.total 
            FROM users 
            LEFT JOIN orders ON users.id = orders.user_id";
$stmtLeft = $pdo->query($sqlLeft);
$resultsLeft = $stmtLeft->fetchAll();

echo "<br>LEFT JOIN Results:<br>";
foreach ($resultsLeft as $row) {
    echo "User: {$row['name']}, Total Order: " . ($row['total'] ?? 'No Orders') . "<br>";
}
?>
