<?php

class base_button
{
    public $name;
    public $permission = USER;
    public $max_permission = SUPERADMIN;

    public function __construct()
    {
    }
    public function __call($name, $args)
    {
        $this->text = $args[1];
        if ($name == 'run') {
            call_user_func_array([$this, $name], $args);
        }
    }
    private function run()
    {
        return false;
    }
}
