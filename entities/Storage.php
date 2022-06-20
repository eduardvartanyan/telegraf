<?php

require_once './interfaces/EventListenerInterface.php';
require_once './interfaces/LoggerInterface.php';

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