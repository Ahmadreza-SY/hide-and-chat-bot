<?php
// BR: add a method run_sql and DRY

class Db
{
    protected $db_name;
    protected $db_user;
    protected $db_pass;
    protected $db;
    protected $chat_id;

    public function __construct($db_name, $db_user, $db_pass, $chat_id = 0)
    {
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db = mysqli_connect('localhost', $this->db_user, $this->db_pass, $this->db_name) or die('Error connecting to MySQL server.');
        $this->chat_id = $chat_id;
    }
    public function __destruct()
    {
        mysqli_close($this->db);
    }
    public function get_user_row()
    {
        return mysqli_query($this->db, "SELECT * FROM `chats` WHERE chat_id = '$this->chat_id' ");
    }
    public function insert($text, $username = '', $fullname = '', $permission = 0, $data = '""')
    {
        $default_cats = json_encode([0, 0, 0, 0, 0, 0]);
        $state = IDLE;
        return mysqli_query($this->db, "INSERT INTO `chats` (chat_id, state, last_message, permission, data, username, fullname) VALUES ('$this->chat_id', '$state', '$text', '$permission', '$data', '$username', '$fullname') ");
    }
    public function set_permission($permission, $chat_id = false)
    {
        $chat_id = $this->get_chat_id($chat_id);

        return mysqli_query($this->db, "UPDATE `chats` SET permission = '$permission' WHERE chat_id = '$chat_id' ");
    }
    public function set_thread($thread, $chat_id = false)
    {
        $chat_id = $this->get_chat_id($chat_id);

        return mysqli_query($this->db, "UPDATE `chats` SET thread = '$thread' WHERE chat_id = '$chat_id' ");
    }
    public function set_state($state, $chat_id = false)
    {
        $chat_id = $this->get_chat_id($chat_id);

        return mysqli_query($this->db, "UPDATE `chats` SET state = '$state' WHERE chat_id = '$chat_id' ");
    }
    public function set_data($data, $chat_id = false)
    {
        $chat_id = $this->get_chat_id($chat_id);

        $data = json_encode($data);
        $data = addslashes($data);
        return mysqli_query($this->db, "UPDATE `chats` SET data = '$data' WHERE chat_id = '$chat_id' ");

    }
    public function set_username($username, $chat_id = false)
    {
        $chat_id = $this->get_chat_id($chat_id);

        return mysqli_query($this->db, "UPDATE `chats` SET username = '$username' WHERE chat_id = '$chat_id' ");
    }
    public function set_fullname($fullname, $chat_id = false)
    {
        $chat_id = $this->get_chat_id($chat_id);

        return mysqli_query($this->db, "UPDATE `chats` SET fullname = '$fullname' WHERE chat_id = '$chat_id' ");
    }
    public function set_last_message($text, $chat_id = false)
    {
        $chat_id = $this->get_chat_id($chat_id);

        return mysqli_query($this->db, "UPDATE `chats` SET last_message = '$text' WHERE chat_id = '$chat_id' ");
    }
    public function get_state($chat_id = false)
    {
        $chat_id = $this->get_chat_id($chat_id);

        $result = mysqli_query($this->db, "SELECT `state` FROM `chats` WHERE chat_id = '$chat_id' ");
        return (int) $result->fetch_assoc()['state'];
    }
    public function get_data($chat_id = false)
    {
        $chat_id = $this->get_chat_id($chat_id);

        $result = mysqli_query($this->db, "SELECT `data` FROM `chats` WHERE chat_id = '$chat_id' ");
        $data = (string) $result->fetch_assoc()['data'];
        $data = json_decode($data, true);
        return $data;
    }
    public function get_username($chat_id = false, $add_at_sign = false)
    {
        $chat_id = $this->get_chat_id($chat_id);

        $result = mysqli_query($this->db, "SELECT `username` FROM `chats` WHERE chat_id = '$chat_id' ");
        $return_value = (string) $result->fetch_assoc()['username'];
        return ($add_at_sign) ? '@' . $return_value : $return_value;
    }
    public function get_fullname($chat_id = false)
    {
        $chat_id = $this->get_chat_id($chat_id);

        $result = mysqli_query($this->db, "SELECT `fullname` FROM `chats` WHERE chat_id = '$chat_id' ");
        return (string) $result->fetch_assoc()['fullname'];
    }
    public function get_user_permission($chat_id = false)
    {
        $chat_id = $this->get_chat_id($chat_id);

        $result = mysqli_query($this->db, "SELECT `permission` FROM `chats` WHERE chat_id = '$chat_id' ");
        return (int) $result->fetch_assoc()['permission'];
    }
    public function reset_state($chat_id = false)
    {
        return $this->set_state(0, $chat_id);
    }
    public function reset_data($chat_id = false)
    {
        return $this->set_data('', $chat_id);
    }
    public function check_user_permission($permission)
    {
        $result = mysqli_query($this->db, "SELECT * FROM `chats` WHERE (chat_id, permission) = ('$this->chat_id', '$permission') ");
        return mysqli_num_rows($result) == 1;
    }
    public function user_is_new()
    {
        $result = $this->get_user_row();
        return mysqli_num_rows($result) == 0;
    }
    public function get_chat_id($chat_id = false)
    {
        if ($chat_id === false) {
            return $this->chat_id;
        }

        return $chat_id;
    }
    public function get_message_id()
    {
        return $this->message_id;
    }
    public function get_users_with_permission($permission)
    {
        $result = mysqli_query($this->db, "SELECT `chat_id` FROM `chats` WHERE permission = '$permission' ");
        while ($row = mysqli_fetch_assoc($result)) {
            $result_array[] = $row['chat_id'];
        }
        return $result_array;
    }
    public function get_all_users_chat_id()
    {
        $result = mysqli_query($this->db, "SELECT `chat_id` FROM `chats` ");
        while ($row = mysqli_fetch_assoc($result)) {
            $result_array[] = $row['chat_id'];
        }
        return $result_array;
    }
/*
// channelposts table
function get_channelposts() {
$result = mysqli_query($this->db, "SELECT * FROM channelposts ");
while($row = mysqli_fetch_assoc($result)) {
$result_array[] = ['id' => $row['id'] , 'data' => $row['data']];
}
return $result_array;
}
function add_channelpost($post_line) {
return mysqli_query($this->db, "INSERT INTO `channelposts` (data) VALUES ('$post_line') ");
}
function read_channelpost() {
$result = mysqli_query($this->db, "SELECT data FROM channelposts LIMIT 1 ");

if (mysqli_num_rows($result) == 0)
return false;

$data = (string)$result->fetch_assoc()['data'];
$data = json_decode($data, true);
mysqli_query($this->db, "DELETE FROM `channelposts` LIMIT 1 "); // delete the row
return $data;
}
function remove_last_channelpost() {
return mysqli_query($this->db, "DELETE FROM `channelposts` order by id desc LIMIT 1 ");
}
function remove_channelpost($id) {
return mysqli_query($this->db, "DELETE FROM `channelposts` WHERE id = '$id' ");
}
function get_num_of_channelposts_left() {
$result = mysqli_query($this->db, "SELECT * FROM `channelposts`");
return mysqli_num_rows($result);
}
 */
}
