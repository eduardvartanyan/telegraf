<?php

// Функция добавляет текст в массив
function add(string $title, string $text, array &$array) : void
{

    $array[] = [
        'title' => $title,
        'text' =>  $text,
    ];

}

// Функция удаляет текст с указанным индексом из массива
function remove(int $index, array &$array) : bool
{

    if (array_key_exists($index, $array)) { // Если индекс существует

        unset($array[$index]); // удаляем элемент

        return true;

    }

    return false;

}

// Функция заменяет текст и его закголовок по указанному индексу
function edit(int $index, string $title, string $text, array &$array) : bool
{

    if (array_key_exists($index, $array)) { // Если индекс существует

        // Заменяем текст и заголовок
        $array[$index]['title'] = $title;
        $array[$index]['text'] = $text;

        return true;

    }

    return false;

}

$textStorage = []; // создание массива, где будут храниться тексты


// Добавление текстов
add('title1', 'text1', $textStorage);
add('title2', 'text2', $textStorage);


// Удаление текстов
var_dump(remove(0, $textStorage));
var_dump(remove(5, $textStorage));
print_r($textStorage);

// Редактирование текстов
var_dump(edit(1, 'title3', 'text3', $textStorage));
var_dump(edit(5, 'title4', 'text4', $textStorage));
print_r($textStorage);
