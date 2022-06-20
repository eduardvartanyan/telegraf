<?php

require_once 'autoload.php';

$testText = new TelegraphText('Eduard Vartanyan', 'test-text');
$testText->text = 'Марк Клодий Пупиен Максим (лат. Marcus Clodius Pupienus Maximus), более известный в римской историографии как Пупиен, — римский император, правивший в 238 году.';
echo $testText->text;
