<?php // vahid

// requirements
require 'vendor/autoload.php';
require_once 'config.php';
require 'main-controller.php';
// file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?chat_id='.$admin_id.'&text=debug');
use Telegram\Bot\Api;
 //aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaass
$tlg = new Tlg($admin_id, $token);

try {
	$tlg->run();
} catch (Exception $e) {
    log_debug(make_exception_array($e));
}
