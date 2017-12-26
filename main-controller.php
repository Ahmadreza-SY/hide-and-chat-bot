<?php

define("THANK_MESSAGE", 'با موفقیت انجام شد.');

//--------------------- Enum of permissions ---------------------
define("USER", 0);
define("SUPERADMIN", 10);

//--------------------- Keyboards -------------------------------
$keyboard_buttons = [

    "start" => [
        "contact" => array("name" => "تماس با ما", "permission" => USER),
    ],

];

//--------------------- Enum of STATEs ---------------------------
define("IDLE", 0);

//--------------------- base class ----------------------------
require 'MyDatabase.php';
require 'MyTelegram.php';
// require 'MyKeyboard.php';
// require 'MyCallback.php';

//--------------------- Debug functions ------------------------
function log_debug($data, $chat_id = 92454)
{
    $text = var_export($data, true);
    global $token;
    /* global $telegram;
    $telegram->sendMessage([
    'chat_id' => $chat_id,
    'text'    => $text,
    ]); */
    file_get_contents('https://api.telegram.org/bot' . $token . '/sendMessage?chat_id=' . $chat_id . '&text=' . $text);
}
