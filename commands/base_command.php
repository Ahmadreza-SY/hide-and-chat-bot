<?php

class base_command {
	public $name;
	public $description;
	public $permission = USER;
	public $max_permission = SUPERADMIN;

	function __construct($message) {
		$this->message = $message;
    }
    
	public function __call($name, $args) {
		$this->text = $args[1];
		if ($name == 'run') {
			$has_permission = call_user_func_array([$this, "check_permissions"], []);
			$this->calculate_command_args();
			if (!$has_permission)
				return false;
			call_user_func_array([$this, $name], $args);
		}
    }
    
	function check_permissions() {
		global $db, $tlg;
		$permission = $this->permission;
		$max_permission = $this->max_permission;
		$user_permission = $db->get_user_permission();
		if (!($permission <= $user_permission && $user_permission <= $max_permission)) {
			$tlg->reply("شما دسترسی به این دستور را ندارید");
			return false;
		} else {
			return true;
		}
	}
	private function run() {
		return false;
	}
	protected function get_command_args() {
		if ($this->command_args == NULL)
			$this->calculate_command_args();
		return $this->command_args;
	}
	protected function calculate_command_args($text = NULL) {
		if ($text != NULL)
			$this->text = $text;
		$this->command_args = trim(str_replace('/start', '', $this->text));
	}
}
