<?php

class TelegraphText {

    private $title, $text, $author, $published, $slug;

    public function __construct(string $author, string $slug)
    {

        $this->author = $author;
        $this->slug = $slug;
        $this->published = date('d.m.Y H:i:s');

    }

    public function storeText()
    {

        $textInArray = [
            'text' => $this->text,
            'title' => $this->title,
            'author' => $this->author,
            'published' => $this->published,
        ];
        $textInString = serialize($textInArray);
        $path = 'texts/' . $this->slug . '.txt';

        file_put_contents($path, $textInString);

    }

    public function loadText() : string
    {

        $path = 'texts/' . $this->slug . '.txt';

        if (file_exists($path)) {

            $textInString = file_get_contents($path);
            $textInArray = unserialize($textInString);
            $this->text = $textInArray['text'];
            $this->title = $textInArray['title'];
            $this->author = $textInArray['author'];
            $this->published = $textInArray['published'];

            if (isset($this->text)) {

                return $this->text;

            }

            return 'Текст не задан.';

        }

        return 'Текст еще не сохранялся в файл.';

    }

    public function editText(string $title, string $text) : void
    {

        $this->title = $title;
        $this->text = $text;

    }

}

$testText = new TelegraphText('Eduard Vartanyan', 'test-text');
$testText->editText('Пупиен', 'Марк Клодий Пупиен Максим (лат. Marcus Clodius Pupienus Maximus), более известный в римской историографии как Пупиен, — римский император, правивший в 238 году.');
$testText->storeText();
echo $testText->loadText();
