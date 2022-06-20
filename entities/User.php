<?php

require_once './interfaces/EventListenerInterface.php';

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