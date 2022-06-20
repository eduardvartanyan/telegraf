<?php

define('CALLBACK_FILE_PATH', 'callback.txt');

interface EventListenerInterface {

    public function attachEvent(string $eventFunctionName, string $callbackFunctionName);

    public function detouchEvent(string $eventFunctionName);

}