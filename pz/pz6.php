<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Анализ производительности запросов в PostgreSQL</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        pre {
            background-color: #eaeaea;
            padding: 10px;
            border: 1px solid #ccc;
            overflow-x: auto;
            max-height: 400px;
            white-space: pre-wrap;
            font-family: monospace;
        }
    </style>
</head>
<body>

<?php 

$host = "localhost"; 
$dbname = "my_database"; 
$user = "eska"; 
$password = "eska77"; 

try {

    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("
        DROP TABLE IF EXISTS sales CASCADE;
        DROP TABLE IF EXISTS products CASCADE;

        CREATE TABLE products (
            id SERIAL PRIMARY KEY,
            name VARCHAR(100),
            category VARCHAR(50),
            price NUMERIC
        );

        CREATE TABLE sales (
            id SERIAL PRIMARY KEY,
            product_id INT REFERENCES products(id),
            sale_date DATE,
            quantity INT
        );

        INSERT INTO products (name, category, price) VALUES 
        ('Laptop', 'Electronics', 1200.00),
        ('Smartphone', 'Electronics', 800.00),
        ('Tablet', 'Electronics', 400.00),
        ('Chair', 'Furniture', 150.00),
        ('Desk', 'Furniture', 300.00);

        INSERT INTO sales (product_id, sale_date, quantity) VALUES 
        (1, '2023-10-01', 5),
        (2, '2023-10-02', 10),
        (3, '2023-10-01', 7),
        (1, '2023-10-05', 2),
        (4, '2023-10-03', 3);
    ");


    $sql = "
        EXPLAIN ANALYZE
        SELECT p.category, SUM(s.quantity) AS total_sold
        FROM products p
        JOIN sales s ON p.id = s.product_id
        WHERE s.sale_date >= '2023-10-01' AND s.sale_date <= '2023-10-05'
        GROUP BY p.category
        ORDER BY total_sold DESC;
    ";

    $stmt = $pdo->query($sql);
    $explainResultsBefore = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h1>План выполнения запроса (до оптимизации)</h1>";
    echo "<pre>";
    foreach ($explainResultsBefore as $row) {
        echo htmlspecialchars($row['QUERY PLAN']) . "\n";
    }
    echo "</pre>";


    $pdo->exec("
        CREATE INDEX idx_sale_date ON sales(sale_date);
        CREATE INDEX idx_product_id ON sales(product_id);
        CREATE INDEX idx_category ON products(category);
    ");


    $sql = "
        EXPLAIN ANALYZE
        SELECT p.category, SUM(s.quantity) AS total_sold
        FROM products p
        JOIN sales s ON p.id = s.product_id
        WHERE s.sale_date >= '2023-10-01' AND s.sale_date <= '2023-10-05'
        GROUP BY p.category
        ORDER BY total_sold DESC;
    ";

    $stmt = $pdo->query($sql);
    $explainResultsAfter = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h1>План выполнения запроса (после оптимизации)</h1>";
        echo "<pre>";
        foreach ($explainResultsAfter as $row) {
            echo htmlspecialchars($row['QUERY PLAN']) . "\n";
        }
        echo "</pre>";
    
        $sql = "
            SELECT p.category, SUM(s.quantity) AS total_sold
            FROM products p
            JOIN sales s ON p.id = s.product_id
            WHERE s.sale_date >= '2023-10-01' AND s.sale_date <= '2023-10-05'
            GROUP BY p.category
            ORDER BY total_sold DESC;
        ";

        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

        if ($results) {
            echo "<h1>Отчет о продажах</h1>";
            echo "<table class='sales-table'>";
            echo "<tr><th>Категория</th><th>Количество проданных</th></tr>";
            foreach ($results as $row) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                echo "<td>" . htmlspecialchars($row['total_sold']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Нет данных о продажах за указанный период.";
        }
    } catch (PDOException $e) {
        echo "Ошибка подключения: " . $e->getMessage();
    }
    ?>
    
    </body>
    </html>