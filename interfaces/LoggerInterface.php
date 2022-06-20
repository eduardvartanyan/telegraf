<?php

define('LOG_FILE_PATH', 'log.txt');

interface LoggerInterface {

    public function logMessage(string $message);

    public function lastMessages(int $count) : array;

}