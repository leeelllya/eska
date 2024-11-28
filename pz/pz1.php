<?php

class Utility {
    public static function generateId() {
        return uniqid('', true);
    }
}

$id = Utility::generateId();
echo "Сгенерированный уникальный идентификатор: $id";