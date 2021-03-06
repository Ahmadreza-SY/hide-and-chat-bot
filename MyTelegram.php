<?php
use Telegram\Bot\Api;

class Tlg
{
    protected $telegram;
    protected $db;
    protected $admin_id;
    protected $token;

    public $update;

    public $chat_id;
    public $message_id;
    public $text;
    public $is_callback_query;

    private function extract_update()
    {
        log_debug($this->update);
        $callback_query = $update->getCallbackQuery();
        $this->callback = false;

        if ($callback_query) {
            $this->callback = [
                'id' => $callback_query->getId(),
                'from' => $callback_query->getFrom(),
                'message' => $callback_query->getMessage(),
                'data' => json_decode($callback_query->getData(), true),
            ];
            $message = $this->callback['message'];
        } else {
            $message = $update->getMessage();
        }
        $chat = $message->getChat();
        $this->chat_id = (int) $chat->getId();
        $text = $message->getText();
        $message_id = $message->getMessageId();
        $user = $message->getFrom();
        $username = $user->getUsername();
        $fullname = $user->getFirstName() . ' ' . $user->getLastName();

        $this->message = [
            'message' => $message,
            'text' => $text,
            'message_id' => $message_id,
        ];
        $this->user = [
            'user' => $user,
            'username' => $username,
            'fullname' => $fullname,
        ];
        $this->chat = $chat;
        $this->text = $text;
        log_debug($this->message);
        log_debug($this->callback);
    }
    public function __construct($admin_id, $token)
    {
        $this->admin_id = $admin_id;
        $this->token = $token;
        $this->telegram = new Api($token);
        $this->update = $this->telegram->getWebhookUpdates();
        $this->extract_update();
        $this->db = new Db($db_name, $db_user, $db_pass, $chat_id);
    }

    private function resolve_new_user()
    {
        if ($this->db->user_is_new()) {
            $this->db->insert($this->text, $this->username, $this->fullname);
        } else {
            $this->db->set_last_message($text);
            $this->db->set_username($username);
            $this->db->set_fullname($fullname);
        }
    }

    public function run()
    {
        $this->resolve_new_user();

        if ($this->callback) { // User's clicked on a glassy button
            // $this->resolve_callback_queries();
        } else { // User's sent a message
            if ($this->resolve_cancel_command()) {
                return;
            }

            // check if it's keyboard button or command and run them
            if (!$this->resolve_keyboard_button()) {
                $this->resolve_commands();
            }
        }
        $this->resolve_state();
    }

    /* public function resolve_callback_queries()
    {
    return Clbk::handle_callback($this->callback);
    }*/
    public function resolve_keyboard_button()
    {
        return false;
        // return Kbd::handle_button($this->message);
    }
    public function resolve_commands()
    {
        return false;
        // return cmd::handle_command($this->message);
    }
    private function resolve_cancel_command()
    {
        return false;
        // return cmd::handle_cancel_command($this->message);
    }
    public function run_thread($thread = false, $state = false)
    {
        if ($thread === false) {
            $thread = $this->thread;
        }
        if ($state === false) {
            $state = $this->state;
        }
        $thread_class = 'thread_' . $thread;
        if (class_exists($thread_class)) {
            $obj = new $thread_class($state);
            $obj->run($this->message); //BR
            return true;
        }
        return false;
    }
    /* public function set_callback_data($answer_data)
    {
    //BR: bayad aval thread tamoom she bad answer_data javab dade beshe
    $this->telegram->answerCallbackQuery($answer_data);
    } */
    public function resolve_state()
    {
        //BR
        $this->thread = $this->db->get_thread();
        $this->state = $this->db->get_state();
        $this->run_thread();

        return true;
    }

    public function send_message($messageObj)
    {
        $messageObj['chat_id'] = (isset($messageObj['chat_id'])) ? $messageObj['chat_id'] : $this->chat_id;
        try {
            $this->telegram->sendMessage($messageObj);
        } catch (Exception $e) {
            log_debug(make_exception_array($e));
        }
    }

    /* public function reply($text, $force_reply = false)
    {
    $chat_id = $this->get_chat_id();
    $data = [
    'chat_id' => $chat_id,
    'text' => $text,
    ];

    if ($force_reply) {
    $reply_markup = $this->telegram->forceReply();
    $data['reply_markup'] = $reply_markup;
    }

    $data['reply_to_message_id'] = $this->get_message_id();
    $this->send_message($data);
    } */

    /* public function send_message_to_superadmin($messageObj)
    {
    $this->send_message($messageObj);
    }
    public function send_text_to_superadmin($text, $reply_markup = false)
    {
    $data = [
    'chat_id' => $this->admin_id,
    'text' => $text,
    'reply-markup' => false,
    ];
    if ($reply_markup !== false) {
    $data['reply_markup'] = $reply_markup;
    }
    $this->send_message_to_superadmin($data);
    } */

    /* public function send_thank_message()
    {
    $this->reply(THANK_MESSAGE, false);
    } */

    /* public function reset_state($text = false)
    {
    if ($text !== false) {
    $reply_markup = get_initial_keyboard();
    $this->send_message([
    'chat_id' => $this->chat_id,
    'text' => $text,
    'reply_markup' => $reply_markup,
    ]);
    }
    $this->db->reset_state();
    $this->db->reset_data();
    } */

    // getters
    /* public function get_chat_id()
{
return $this->chat_id;
}
public function get_message_id()
{
return $this->message_id;
} */
}
