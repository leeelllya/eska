<?php
session_start();

// Удаление всех сессионных переменных
$_SESSION = [];

// Уничтожение сессии
session_destroy();

// Перенаправление на страницу сообщения о выходе
header("Location: index.php");
exit();
?>