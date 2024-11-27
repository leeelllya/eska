<?php

class CustomArrayObject extends ArrayObject {
    // Метод для добавления элемента
    public function add($value) {
        $this[] = $value;
    }

    // Метод для удаления элемента по индексу
    public function remove($index) {
        if (isset($this[$index])) {
            unset($this[$index]);
        } else {
            echo "Индекс $index не найден.\n";
        }
    }

    // Метод для сортировки массива
    public function sortArray() {
        // Получаем массив из объекта и сортируем его
        $arrayCopy = $this->getArrayCopy();
        sort($arrayCopy);
        // Обновляем текущий объект с отсортированным массивом
        $this->exchangeArray($arrayCopy);
    }

    // Печать массива
    public function printArray() {
        foreach ($this as $key => $value) {
            echo "Индекс $key: $value\n";
        }
    }

    // Метод для получения массива
    public function toArray() {
        return $this->getArrayCopy(); // Преобразуем объект в массив
    }
}

// Ввод массива с клавиатуры
$input = readline("Введите элементы массива через запятую: ");
$arrayElements = explode(',', $input);
$arrayElements = array_map('trim', $arrayElements); // Удаляем пробелы

$customArray = new CustomArrayObject($arrayElements);

// Демонстрация работы методов
$customArray->printArray();
$customArray->add('Новый элемент');
$customArray->remove(1); // Удаляем элемент с индексом 1
$customArray->sortArray();
echo "После сортировки:\n";
$customArray->printArray();

// Преобразование объекта в массив
$array = $customArray->toArray();
print_r($array); // Для проверки