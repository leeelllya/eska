<?php


$uploadDir = 'uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['files'])) {
        $files = $_FILES['files'];
        $results = [];

        foreach ($files['tmp_name'] as $key => $tmpName) {
            $originalName = $files['name'][$key];
            $destination = $uploadDir . basename($originalName);

            $directoryPath = dirname($destination);
            if (!is_dir($directoryPath)) {
                mkdir($directoryPath, 0755, true);
            }

            if (move_uploaded_file($tmpName, $destination)) {
                if (is_file($destination)) {
                    $results[] = "$originalName является файлом.";
                } elseif (is_dir($destination)) {
                    $results[] = "$originalName является директорией.";
                }
            } else {
                $results[] = "Не удалось загрузить $originalName.";
            }
        }

        echo implode('<br>', $results);
    } else {
        echo "Не удалось получить загруженные файлы.";
    }
} else {
    echo "Некорректный запрос.";
}
?>