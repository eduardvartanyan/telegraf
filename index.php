<?php

define('LOG_FILE_PATH', 'log.txt');
define('CALLBACK_FILE_PATH', 'callback.txt');

interface LoggerInterface {

    public function logMessage(string $message);

    public function lastMessages(int $count) : array;

}

interface EventListenerInterface {

    public function attachEvent(string $eventFunctionName, string $callbackFunctionName);

    public function detouchEvent(string $eventFunctionName);

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

            $this->text = $value;
            $this->storeText();

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

class Storage implements LoggerInterface, EventListenerInterface {

    public function create(&$text)
    {

    }

    public function read($textId)
    {

    }

    public function update($textId, $newText)
    {

    }

    public function delete($textId)
    {

    }

    public function list()
    {

    }

    public function logMessage(string $message)
    {
        $textInArray = [];

        if (file_exists(LOG_FILE_PATH)) {

            $textInString = file_get_contents(LOG_FILE_PATH);
            $textInArray = unserialize($textInString);

        }

        $textInArray[] = [
            'date' => date('d.m.Y H:i:s'),
            'text' => $message,
        ];
        $textInString = serialize($textInArray);
        file_put_contents(LOG_FILE_PATH, $textInString);
    }

    public function lastMessages(int $count): array
    {
        $textInArray = [];

        if (file_exists(LOG_FILE_PATH)) {

            $textInString = file_get_contents(LOG_FILE_PATH);
            $textInArray = unserialize($textInString);

        }

        $resultArray = $textInArray;

        if ($count < count($textInArray)) {

            $resultArray = [];

            for ($i = count($textInArray) - $count; $i < count($textInArray); $i++) {

                $resultArray[] = $textInArray[$i];

            }

        }

        return $resultArray;

    }

    public function attachEvent(string $eventFunctionName, string $callbackFunctionName)
    {

        $textInArray = [];

        if (file_exists(CALLBACK_FILE_PATH)) {

            $textInString = file_get_contents(CALLBACK_FILE_PATH);
            $textInArray = unserialize($textInString);

        }

        $textInArray[] = [
            'eventFunctionName' => $eventFunctionName,
            'callbackFunctionName' => $callbackFunctionName,
        ];

        $textInString = serialize($textInArray);
        file_put_contents(CALLBACK_FILE_PATH, $textInString);

    }

    public function detouchEvent(string $eventFunctionName)
    {

        if (file_exists(CALLBACK_FILE_PATH)) {

            $textInString = file_get_contents(CALLBACK_FILE_PATH);
            $textInArray = unserialize($textInString);

            if (count($textInArray) > 0) {

                foreach ($textInArray as $key=>$functions) {

                    if ($functions['eventFunctionName'] == $eventFunctionName) {

                        unset($textInArray[$key]);

                    }

                }

            }

            $textInString = serialize($textInArray);
            file_put_contents(CALLBACK_FILE_PATH, $textInString);

        }

    }
}

abstract class View {

    public $storage;

    function __constract ($storage)
    {

        $this->storage = $storage;

    }

    abstract public function displayTextById($id);

    abstract public function displayTextByUrl($url);

}

class User implements EventListenerInterface {

    protected $id, $name, $role;

    public function getTextsToEdit() {



    }

    public function attachEvent(string $eventFunctionName, string $callbackFunctionName)
    {

        $textInArray = [];

        if (file_exists(CALLBACK_FILE_PATH)) {

            $textInString = file_get_contents(CALLBACK_FILE_PATH);
            $textInArray = unserialize($textInString);

        }

        $textInArray[] = [
            'eventFunctionName' => $eventFunctionName,
            'callbackFunctionName' => $callbackFunctionName,
        ];

        $textInString = serialize($textInArray);
        file_put_contents(CALLBACK_FILE_PATH, $textInString);

    }

    public function detouchEvent(string $eventFunctionName)
    {

        if (file_exists(CALLBACK_FILE_PATH)) {

            $textInString = file_get_contents(CALLBACK_FILE_PATH);
            $textInArray = unserialize($textInString);

            if (count($textInArray) > 0) {

                foreach ($textInArray as $key=>$functions) {

                    if ($functions['eventFunctionName'] == $eventFunctionName) {

                        unset($textInArray[$key]);

                    }

                }

            }

            $textInString = serialize($textInArray);
            file_put_contents(CALLBACK_FILE_PATH, $textInString);

        }

    }
}

// Класс содердит методы, позволяющий работать с хранилищем файлов
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

$testText = new TelegraphText('Eduard Vartanyan', 'test-text');
$testText->text = 'Марк Клодий Пупиен Максим (лат. Marcus Clodius Pupienus Maximus), более известный в римской историографии как Пупиен, — римский император, правивший в 238 году.';
echo $testText->text;
