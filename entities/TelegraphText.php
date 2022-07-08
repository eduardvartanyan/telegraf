<?php

class TelegraphTextException extends Exception {

}


class TelegraphText {

    private $title, $text, $author, $published, $slug;

    // Конструктор класса устанавливает для текста автора и имя файла (без расширения)
    public function __construct(string $author, string $slug)
    {

        $this->title = 'Untitled';
        $this->author = $author;
        $this->slug = $slug;
        $this->published = date('d.m.Y');

    }

    public function __set($name, $value)
    {

        if ($name == 'author') {

            if (strlen($value) >= 120) {

                echo 'Ошибка: Имя автора не должно превышать 120 символов.' . PHP_EOL;

                return;

            }

            $this->author = $value;

        } elseif ($name == 'slug') {

            if (preg_match("/^([a-zA-Z0-9-_\/]*)$/i", $value)) {

                $this->slug = $value;

            } else {

                echo 'Ошибка: Имя файла должно содержать только буквы латинского алфавита, цифры и символы —_/.' . PHP_EOL;

            }

        } elseif ($name == 'published') {

            if (date_create_from_format('d.m.Y', $value) >= date_create_from_format('d.m.Y', date('d.m.Y'))) {

                echo 'Дата корректная';

                $this->published = $value;

            } else {

                echo 'Ошибка: Дата должна быть больше или равна текущей даты.' . PHP_EOL;

            }

        } elseif ($name == 'text') {

            $strLength = strlen($value);

            if ($strLength < 1) {

                throw new TelegraphTextException('Пустое сообщение');

            } elseif ($strLength > 500) {

                throw new TelegraphTextException('Слишком длинное сообщение (более 500 символов)');

            } else {

                $this->text = $value;
                $this->storeText();

            }

        } elseif ($name == 'title') {

            $this->title = $value;

        }

    }

    public function __get($name)
    {

        if ($name == 'author') {

            return $this->author;

        } elseif ($name == 'slug') {

            return $this->slug;

        } elseif ($name == 'published') {

            return $this->published;

        } elseif ($name == 'text') {

            return $this->loadText();

        } elseif ($name == 'title') {

            return $this->title;

        }

    }

    // Сохранение текста в файл с использованием метода класса FileStorage
    private function storeText()
    {

        $file = new FileStorage();

        return $file->create($this);

    }

    // Загрузка текста из файла с использованием метода класса FileStorege
    private function loadText() : string
    {

        $file = new FileStorage();

        if (file_exists('texts/' . $this->slug . '.txt')) {

            $loadedText = $file->read($this->slug);

            return $loadedText->text; // Возвращает загруженный текст

        } else {

            return $this->text;

        }

    }

    // Метод позволяет редактировать заголовок и текст. Необязательный параметр - дата публикации.
    public function editText(string $title, string $text, string $published = NULL) : void
    {

        $this->title = $title;
        $this->text = $text;

        if (isset($published)) {

            $this->published = $published;

        }

    }

}