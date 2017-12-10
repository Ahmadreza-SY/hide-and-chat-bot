<?php

class Kbd
{
    protected $lots_of_dots;
    protected $categories_array;
    protected $tlg;

    private $message;

    public static function init()
    {
        global $categories_array, $keyboard_buttons, $tlg;
        self::$lots_of_dots = str_repeat('.', 100);
        self::$categories_array = $categories_array;
        self::$keyboard_buttons = $keyboard_buttons;
        self::$tlg = $tlg;
    }
    public static function create_glassy_link_btn($text, $url)
    {
        return [
            'text' => $text,
            'url' => $url,
        ];
    }
    public static function create_glassy_keyboard($keyboard)
    {
        return Telegram\Bot\Keyboard\Keyboard::make(['inline_keyboard' => $keyboard]);
    }
    public static function create_glassy_btn($text, $callback_function, $params = [])
    {
        $callback_data = [
            'f' => $callback_function,
        ];
        $callback_data = json_encode(array_merge($callback_data, $params));

        if (mb_strlen($callback_data, '8bit') > 64) { //callback data can't be more than 64 bytes
            return false;
        }

        return [
            'text' => $text,
            'callback_data' => $callback_data,
        ];
    }
    public static function get_initial_keyboard()
    {
        $initial_keyboard = 'start';
        $keyboard = static::get_keyboard($initial_keyboard);
        $keyboard = array_duplex($keyboard);

        $reply_markup = $telegram->replyKeyboardMarkup([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);

        return $reply_markup;
    }
    public static function get_keyboard($keyboard_name)
    {
        global $db;
        $is_super_admin = $db->check_user_permission(SUPERADMIN);
        $is_admin = $db->check_user_permission(ADMIN);
        $is_author = $db->check_user_permission(AUTHOR);
        $permission = ($is_super_admin ? SUPERADMIN : ($is_admin ? ADMIN : ($is_author ? AUTHOR : USER)));
        $keyboard = static::$keyboard_buttons[$keyboard_name];
        $commands = [];

        foreach ($keyboard as $button) {
            if ($button["permission"] <= $permission) {
                array_push($commands, $button["name"]);
            }
        }

        $reply_markup = self::create_keyboard($commands);

        return $reply_markup;
    }
    public static function show_keyboard($keyboard_name, $text)
    {
        $reply_markup = static::get_keyboard($keyboard_name);

        self::$tlg->send_message([
            'chat_id' => self::$tlg->chat_id,
            'text' => $text,
            'reply_markup' => $reply_markup,
        ]);
    }

    public static function handle_button($message)
    {
        self::$message = $message;
        $button = self::get_button($message['text']);
        return self::run_button($button);
    }
    private function get_button($text)
    {
        foreach (self::$keyboard_buttons as $keyboard_name => $btns) {
            foreach ($btns as $btn_name => $btn) {
                if ($text == $btn['name']) {
                    return $btn_name;
                }
            }
        }
        return '';
    }
    private function run_button($button)
    {
        $button_class = 'btn_' . $button;
        if (class_exists($button_class)) {
            $obj = new $button_class();
            $obj->run(self::$message);
            return true;
        }
    }

    // Private
    private function create_keyboard($keys)
    {
        global $telegram;
        $reply_markup = $telegram->replyKeyboardMarkup([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);
        return $reply_markup;
    }
}

require "./buttons/base_button.php";
foreach (glob("./buttons/button_*.php") as $filename) {
    require $filename;
}

MyKeyboard:init();
