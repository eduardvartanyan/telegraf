<!DOCTYPE html>
<html>
<head>
    <title>Форма</title>
    <style>
        .message-form {
            max-width: 500px;
            margin: 0 20px;
        }
        .message-form > div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .message-form_input {
            width: 80%;
        }
    </style>
</head>
<body>
    <form action="html_import_processor.php" class="message-form" method="post">
        <h1>Копирование контента со стороннего сайта</h1>
        <div>
            <label >Страница:</label>
            <input type="text" name="url" class="message-form_input">
        </div>
        <div>
            <input type="submit" value="Отправить">
        </div>
    </form>
</body>
</html>

<?php

if (isset($_POST['url']) && !empty($_POST['url'])) {

    $ch1 = curl_init($_POST['url']);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    $json = json_encode(["raw_text" => curl_exec($ch1)]);

    $json = str_replace('\n', '', $json);
    $json = str_replace('\t', '', $json);

    curl_close($ch1);

    $ch2 = curl_init('http://localhost/telegraph/HtmlProcessor.php');
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_POSTFIELDS, $json);
    $response = json_decode(curl_exec($ch2), true);
    $info = curl_getinfo($ch2);

    if ($info["http_code"] === 500) {

        echo 'Ошибка 500: Мы не обрабатываем пустой текст.';

    } else {

        echo $response['formatted_text'];

    }

    curl_close($ch2);

}

?>