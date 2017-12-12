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
