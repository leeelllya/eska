<?php
// Укажите адрес электронной почты для тестирования
$email = 'domas29063@craftapk.com'; // Замените на ваш email
// Генерация кода подтверждения
$confirmation_code = rand(100000, 999999);
echo "Код подтверждения: $confirmation_code<br>"; // Отладочное сообщение
$subject = "Тестовое письмо с кодом подтверждения";
$message = "Ваш код подтверждения: $confirmation_code";
$headers = "From: no-reply@yourdomain.com\r\n";
// Отправка письма
if (mail($email, $subject, $message, $headers)) {
echo "Письмо отправлено успешно на: $email";
} else {
echo "Ошибка при отправке письма.";
}
?>