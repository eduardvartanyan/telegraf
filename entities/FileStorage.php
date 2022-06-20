<?php

class FileStorage extends Storage {

    // Метод, позволяющий создать файл и сохранить в него текст
    public function create(&$text)
    {

        $textInArray = [
            'text' => $text->text,
            'title' => $text->title,
            'author' => $text->author,
            'published' => $text->published,
        ];
        $textInString = serialize($textInArray);
        $newSlug = $text->slug . '_' . date('d-m-Y');
        $path = 'texts/' . $newSlug . '.txt';

        // Если для переданного текста уже существует файл от сегодняшней даты, то создаем новый файл со свободным индексом.
        if (file_exists($path)) {

            $i = 0;
            do {

                $i++;

                $path = 'texts/' . $newSlug . '_' . $i . '.txt';

            } while (file_exists($path));

            $newSlug .= '_' . $i;
            $text->slug = $newSlug . '_' . $i;

        }

        $text->slug = $newSlug; // Обновляем slug текста с учетом даты и индекса
        file_put_contents($path, $textInString);

        $this->logMessage('Текст сохранен в файл ' . $newSlug . '.txt');

        return $newSlug; // Метод возвращаем актуальное имя файла, куда сохранен текст

    }

    // Метод позволяет достать текст из указанного файла
    public function read($textId)
    {

        $path = 'texts/' . $textId . '.txt';

        if (file_exists($path)) {

            $textInString = file_get_contents($path);
            $textInArray = unserialize($textInString);
            $text = new TelegraphText($textInArray['author'], $textId);
            $text->editText($textInArray['title'], $textInArray['text'], $textInArray['published']);

            $count = 3;
            echo 'Последние ' . $count . ' сообщений логов:' . PHP_EOL;
            print_r($this->lastMessages($count));

            return $text;

        }

        return 'Файл с именем ' . $textId . ' не найден.' . PHP_EOL; // Если имя файла задано не корректно

    }

    // Метод позволяет обновить текст в указанном файле
    public function update($textId, $newText): void
    {

        $path = 'texts/' . $textId . '.txt';

        if (file_exists($path)) {

            $textInArray = [
                'text' => $newText->text,
                'title' => $newText->title,
                'author' => $newText->author,
                'published' => $newText->published,
            ];
            $textInString = serialize($textInArray);
            file_put_contents($path, $textInString);

        }

    }

    // Метод позволяет удалить указанный файл
    public function delete($textId): void
    {

        $path = 'texts/' . $textId . '.txt';

        if (file_exists($path)) {

            unlink($path);

        }

    }

    // Метод возвращает тексты из всех файлов франилища
    public function list()
    {

        $files = scandir('texts/');
        $texts = [];

        foreach ($files as $file) {

            $path = 'texts/' . $file;
            $textInString = file_get_contents($path);
            $textInArray = unserialize($textInString);
            $text = new Text($textInArray['author'], rtrim($file, '.txt'));
            $text->editText($textInArray['title'], $textInArray['text'], $textInArray['published']);
            $texts[] = $text;

        }

        if (count($texts) > 0) {

            return $texts;

        } else {

            return false; // Если файлов в храналище нет, то возвращается false

        }

    }

}