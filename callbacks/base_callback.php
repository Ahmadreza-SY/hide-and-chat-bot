<?php

class base_callback
{
    public $name;

    public function __construct($message)
    {
        $this->message = $message;
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
