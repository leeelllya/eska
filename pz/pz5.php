<?php

class CustomArrayObject extends ArrayObject {
    public function filter(callable $callback) {
        $filteredArray = [];
        foreach ($this as $value) {
            if ($callback($value)) {
                $filteredArray[] = $value;
            }
        }
        return new self($filteredArray);
    }

    public function sortArray() {
        $this->asort(); 
    }

    public function unique() {
        return new self(array_unique($this->getArrayCopy()));
    }
}

$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['array_input'])) {
    $inputArray = array_map('trim', explode(',', $_POST['array_input']));
    $customArray = new CustomArrayObject($inputArray);

    $originalArray = $customArray->getArrayCopy();

    $filtered = $customArray->filter(function($value) {
        return is_numeric($value) && $value % 2 === 0;
    });

    $customArray->sortArray();
    $sortedArray = $customArray->getArrayCopy();

    $unique = $customArray->unique();

    $result = [
        'original' => implode(", ", $originalArray),
        'sorted' => implode(", ", $sortedArray),
        'filtered' => implode(", ", $filtered->getArrayCopy()),
        'unique' => implode(", ", $unique->getArrayCopy())
    ];
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Array Object</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px -10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .result {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Введите массив</h2>
        <form method="POST">
            <input type="text" name="array_input" placeholder="Введите числа, разделенные запятыми" required>
            <input type="submit" value="Обработать">
        </form>

        <?php if ($result): ?>
        <div class="result">
            <h3>Результаты</h3>
            <p><strong>Оригинальный массив:</strong> <?php echo $result['original']; ?></p>
            <p><strong>Отсортированный массив:</strong> <?php echo $result['sorted']; ?></p>
            <p><strong>Отфильтрованный массив (четные числа):</strong> <?php echo $result['filtered']; ?></p>
            <p><strong>Уникальные значения:</strong> <?php echo $result['unique']; ?></p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>