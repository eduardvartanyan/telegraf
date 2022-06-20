<?php

abstract class View {

    public $storage;

    function __construct ($storage)
    {

        $this->storage = $storage;

    }

    abstract public function displayTextById($id);

    abstract public function displayTextByUrl($url);

}