<?php

define("THANK_MESSAGE", 'با موفقیت انجام شد.');

//--------------------- Enum of permissions ---------------------
define("USER", 0);
define("SUPERADMIN", 4);

//--------------------- Keyboards -------------------------------
$keyboard_buttons = [
    "schedule_post" => [
        "moarefi_robot" => array("name" => 'معرفی ربات', "permission" => ADMIN),
    ],

    "start" => [
        "contact" => array("name" => "تماس با ما", "permission" => USER),
        // "post_validation"=>array("name"=>"تایید مطلب توسط مدیر برای نوشتن", "permission"=>AUTHOR),
        // "schedule_post"=>array("name"=>"ارسال مطلب به کانال", "permission"=>ADMIN),
        // "request_post"=>array("name"=>"درخواست مطلب آموزشی", "permission"=>USER),
        "categories" => array("name" => "دریافت مطالب سایت", "permission" => USER),
        "post_source" => array("name" => "پیشنهاد مطلب به نویسنده ها", "permission" => ADMIN),
        "get_site_recommend_post" => array("name" => "مطلب برای نوشتن تو سایت", "permission" => AUTHOR),
        "about_us" => array("name" => "درباره ما", "permission" => USER),
        "send_message_authors" => array("name" => "پیام به نویسنده ها", "permission" => ADMIN),
    ],

];

//--------------------- Enum of STATEs ---------------------------
define("IDLE", 0);

//--------------------- base class ----------------------------
require 'MyDatabase.php';
require 'MyTelegram.php';
require 'MyKeyboard.php';
require 'MyCallback.php';
