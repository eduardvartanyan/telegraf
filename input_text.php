<!DOCTYPE html>
<html>
<head>
    <title>Телеграф</title>
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
        .message-form textarea {
            height: 150px;
        }
        .sendform-status {
            max-width: 450px;
            margin: 0 20px;
            padding: 30px;
            color:white;
        }
        .success {
            background-color: limegreen;
            font-weight: bold;
        }
        .error {
            background-color: hotpink;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php

require_once 'autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Обработка фатальных ошибок, не отловленных блоками catch
function errorHandler($level, $msg, $line, $file)
{

    echo '<div class="sendform-status error">' . $msg . '</div>';

}

set_error_handler('errorHandler');

// Переменные для вывода сообщений об успешной или неуспешной отправки формы
$isSuccess = false;
$isError = false;
$successMassage = '';
$errorMassage = '';


if (isset($_POST['author']) && isset($_POST['text'])) {

    $author = $_POST['author'];
    $text = $_POST['text'];


    $newMessage = new TelegraphText($author, 'form-text');

    try {

        $newMessage->text = $text;

    } catch (TelegraphTextException $e) {

        $isError = true;
        $errorMassage .= $e->getMessage();

    }

    if ($_POST['email'] != "") {

        $to = $_POST['email'];
        $title = "$author, your message has been sent";
        $body = "<h2>Your message:</h2>$text";

        $mail = new PHPMailer();

        try {

            $mail->isSMTP();
            $mail->CharSet = "UTF-8";
            $mail->SMTPAuth = true;
            $mail->SMTPDebug = 2;
            $mail->Debugoutput = function($str, $level) {$GLOBALS['status'][] = $str;};

            $mail->Host = 'smtp.gmail.com';
            $mail->Username = 'evartanyan1989@gmail.com';
            $mail->Password = 'yjekqwshdtzgjzgw';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->setFrom('evartanyan1989@gmail.com', 'Telegraph');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->Body = $body;

            $mail->send();

            $isSuccess = true;
            $successMassage .= "Сообщение отправлено";

        } catch (Exception $e) {

            $isError = true;
            $errorMassage .= "Сообщение не было отправлено. Причина: {$mail->ErrorInfo}";

        }

    }
}

if ($isSuccess) {

    echo '<div class="sendform-status success">' . $successMassage . '</div>';

}

if ($isError) {

    echo '<div class="sendform-status error">' . $errorMassage . '</div>';

}

?>


    <form action="input_text.php" class="message-form" method="post">
        <h1>Отправка сообщений в телеграф</h1>
        <div>
            <label for="author">Автор:</label>
            <input type="text" id="author" name="author" class="message-form_input">
        </div>
        <div>
            <label for="text">Текст:</label>
            <textarea id="text" name="text" class="message-form_input"></textarea>
        </div>
        <div>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" class="message-form_input">
        </div>
        <div>
            <input type="reset" value="Очистить форму">
            <input type="submit" value="Отправить">
        </div>
    </form>
</body>
</html>