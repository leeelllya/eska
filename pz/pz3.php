<?php

$path = '';

if (is_file($path)) {
    echo "$path является файлом.";
} elseif (is_dir($path)) {
    echo "$path является директорией.";
} else {
    echo "$path не существует.";
}

?>