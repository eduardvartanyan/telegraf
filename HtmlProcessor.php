<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input["raw_text"])) {

        $text = $input["raw_text"];

        if (strlen($text) > 0) {

            preg_match_all('/<a href="[^>]+">.+?<\/a>/', $text, $matches, PREG_OFFSET_CAPTURE);

            if (count($matches[0]) > 0) {

                foreach ($matches[0] as $match) {

                    preg_match('/href="[^>]+" /', $match[0], $link);

                    if (count($link) > 0) {

                        $text = str_replace($match[0], substr($link[0], 6, strlen($link[0]) - 8), $text);

                    }

                }
            }

            header('Content-type: Application/json');
            echo json_encode(array('formatted_text' => $text));

        } else {

            http_response_code(500);

        }
    }
}